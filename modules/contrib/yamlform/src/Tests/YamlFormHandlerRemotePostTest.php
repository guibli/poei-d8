<?php

namespace Drupal\yamlform\Tests;

use Drupal\yamlform\Entity\YamlForm;

/**
 * Tests for remote post form handler functionality.
 *
 * @group YamlForm
 */
class YamlFormHandlerRemotePostTest extends YamlFormTestBase {

  /**
   * Test remote post handler.
   */
  public function testRemotePostHandler() {
    /** @var \Drupal\yamlform\YamlFormInterface $yamlform_handler_remote */
    $yamlform_handler_remote = YamlForm::load('test_handler_remote_post');

    $this->drupalLogin($this->adminFormUser);

    // Check remote post 'create' operation.
    $sid = $this->postSubmission($yamlform_handler_remote);
    $this->assertPattern('#<label>Remote operation</label>\s+insert#ms');
    $this->assertRaw('custom_insert: true');
    $this->assertRaw('custom_all: true');
    $this->assertRaw("custom_title: 'Test: Handler: Remote post: Submission #$sid'");
    $this->assertRaw('first_name: John');
    $this->assertRaw('last_name: Smith');
    $this->assertRaw('email: from@example.com');
    $this->assertRaw("subject: '{subject}'");
    $this->assertRaw("message: '{message}'");
    $this->assertNoRaw("sid: '$sid'");

    // Check remote post 'update' operation.
    $this->drupalPostForm("admin/structure/yamlform/manage/test_handler_remote_post/submission/$sid/edit", [], t('Save'));
    $this->assertRaw('custom_update: true');
    $this->assertRaw('custom_all: true');
    $this->assertRaw("custom_title: 'Test: Handler: Remote post: Submission #$sid'");
    $this->assertRaw('first_name: John');
    $this->assertPattern('#<label>Remote operation</label>\s+update#ms');

    // Check remote post 'delete' operation.
    $this->drupalPostForm("admin/structure/yamlform/manage/test_handler_remote_post/submission/$sid/delete", [], t('Delete'));
    $this->assertRaw('custom_delete: true');
    $this->assertRaw('custom_all: true');
    $this->assertRaw("custom_title: 'Test: Handler: Remote post: Submission #$sid'");
    $this->assertRaw('first_name: John');
    $this->assertPattern('#<label>Remote operation</label>\s+delete#ms');

    // Check including data.
    $handler = $yamlform_handler_remote->getHandler('remote_post');
    $configuration = $handler->getConfiguration();
    $configuration['settings']['excluded_data'] = [
      'subject' => 'subject',
      'message' => 'message',
    ];
    $handler->setConfiguration($configuration);
    $yamlform_handler_remote->save();
    $sid = $this->postSubmission($yamlform_handler_remote);
    $this->assertRaw('first_name: John');
    $this->assertRaw('last_name: Smith');
    $this->assertRaw('email: from@example.com');
    $this->assertNoRaw("subject: '{subject}'");
    $this->assertNoRaw("message: '{message}'");
    $this->assertRaw("sid: '$sid'");

    // @todo Figure out why the below test is failing on Drupal.org.
    // Check remote post 'create' 500 error handling.
    // $this->postSubmission($yamlform_handler_remote, ['first_name' => 'FAIL']);
    // $this->assertPattern('#<label>Response status code</label>\s+500#ms');

    // @todo Figure out why the below test is failing on Drupal.org.
    // Update the remote post handlers insert url to return a 404 error.
    // /** @var \Drupal\yamlform\Plugin\YamlFormHandler\RemotePostYamlFormHandler $handler */
    // $handler = $yamlform_handler_remote->getHandler('remote_post');
    // $configuration = $handler->getConfiguration();
    // $configuration['settings']['insert_url'] .= '/broken';
    // $handler->setConfiguration($configuration);
    // $yamlform_handler_remote->save();

    // $this->postSubmission($yamlform_handler_remote, ['first_name' => 'FAIL']);
    // $this->assertPattern('#<label>Response status code</label>\s+404#ms');
  }

}
