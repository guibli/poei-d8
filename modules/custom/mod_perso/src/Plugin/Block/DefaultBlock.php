<?php

namespace Drupal\mod_perso\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\mod_perso\PremierServiceInterface;

/**
 * Provides a 'DefaultBlock' block.
 *
 * @Block(
 *  id = "default_block",
 *  admin_label = @Translation("Mon Block Perso"),
 * )
 */
class DefaultBlock extends BlockBase {

/*
  public function __construct(array $test){
    var_dump($test);
    $this->premierService = $test;
  }*/
  public function build() {
    return [
      '#theme' => 'mod_perso',
      '#toto' => \Drupal::service('mod_perso.premierservice')->PremiereFunction('TOTO'),
    ];
  }
}
