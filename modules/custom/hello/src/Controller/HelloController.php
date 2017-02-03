<?php

namespace Drupal\hello\Controller;

use Drupal\Core\Controller\ControllerBase;

class HelloController extends ControllerBase{

  public function content($testparam){
    $name = $this->currentUser();
    return array(
      '#markup' => $this->t("Vous êtes sur la page @testparam. Votre nom d'utilisateur est @name",
        array('@testparam'=>$testparam,'@name'=>$name->getDisplayName())
        )
    );
  }
}