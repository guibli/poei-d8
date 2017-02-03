<?php

namespace Drupal\hello\Controller;

use Drupal\Core\Controller\ControllerBase;

class HelloControllerListeNoeud extends ControllerBase{

  public function content($entitytype = NULL ){

    $storage = \Drupal::entityTypeManager()->getStorage('node');

    if($entitytype != NULL){
      $ids = \Drupal::entityQuery('node')
        ->pager()
        ->condition('type', $entitytype)
        ->execute();
    }else{
      $ids = \Drupal::entityQuery('node')
        ->pager()
        ->execute();
    }

    $entities = $storage->loadMultiple($ids);
    
    foreach ($entities as $item) {
      $list[]=$item->toLink($item->getTitle());
    }

    $render[]=array(
      '#type'=>'pager',
    );
    $render[]=array(
      '#theme' => 'item_list',
      '#items' => $list,
      '#list_type' => 'ol',
    );
    $render[]=array(
      '#type'=>'pager',
    );
    return $render;
  }
}