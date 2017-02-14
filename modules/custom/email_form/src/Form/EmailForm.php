<?php

namespace Drupal\email_form\Form;
use Drupal\reusable_forms\Form\ReusableFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Defines the EmailForm class.
 */
class EmailForm extends ReusableFormBase {

  /**
   * {@inheritdoc}.
   */
  public function getFormId() {
    return 'email_form';
  }

  /**
   * {@inheritdoc}.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['email'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Email'),
    );

    $form = parent::buildForm($form, $form_state);

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Handle form submission.
    $entity = $form_state->getBuildInfo('args')['args'];
    $email = $form_state->getValue('email');
    //Check if email already register for this article
    $query = \Drupal::database()
      ->select('email_form','ef')
      ->fields('ef',array('nid','email'))
      ->condition('nid', $entity[0]->id())
      ->condition('email', $email)
      ->execute();

    $result = $query->fetchAll();

    if(count($result)==0) {
      $query = \Drupal::database()->insert('email_form');
      $query->fields([
        'nid' => $entity[0]->id(),
        'email' => $email
      ]);
      $query->execute();
    }
  }
}
