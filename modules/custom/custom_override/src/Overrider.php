<?php

namespace Drupal\custom_override;

use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Config\ConfigFactoryOverrideInterface;
use Drupal\Core\Config\StorableConfigBase;
use Drupal\Core\Config\StorageInterface;
use Drupal\Core\Session\AccountProxy;

/**
 * Class Overrider.
 *
 * @package Drupal\config_override
 */
class Overrider implements ConfigFactoryOverrideInterface {

  /**
   * Drupal\Core\Session\AccountProxy definition.
   *
   * @var AccountProxy
   */
  protected $currentUser;

  /**
   * Constructor.
   */
  public function __construct(AccountProxy $current_user) {
    $this->currentUser = $current_user;
  }

  public function createConfigObject($name, $collection = StorageInterface::DEFAULT_COLLECTION): StorableConfigBase {
    return NULL;
  }

  public function getCacheSuffix() {
    return 'ConfigExampleOverrider';
  }

  public function getCacheableMetadata($name): CacheableMetadata {
    return new CacheableMetadata();
  }

  public function loadOverrides($names) {

    $overrides = array();
    if (in_array('system.site', $names)) {

      if ($this->currentUser->id() != 0) {
        $overrides['system.site'] = ['name' => 'Intranet Drupal 8'];
      }
      else {
        $overrides['system.site'] = ['name' => 'Drupal 8'];
      }
    }
    return $overrides;
  }

}