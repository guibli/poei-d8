<?php
namespace Drupal\hello\Plugin\Block;

use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Database\Connection;
use Drupal\Core\Block\BlockBase;
/**
 * Provides a block with the number of active sessions
 *
 * @Block(
 *   id = "ActiveSessions",
 *   admin_label = @Translation("Active Sessions")
 * )
 */

class ActiveSessions extends BlockBase implements ContainerFactoryPluginInterface{

  protected $db;
  public function __construct( Connection $database,array $configuration, $plugin_id, $plugin_definition)
  {
    $this->db = $database;
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  public static function create(ContainerInterface $container,array $configuration, $plugin_id, $plugin_definition){
    return new static(
      $container->get('database'),
      $configuration,
      $plugin_id,
      $plugin_definition
    );
  }
  public function build(){
    /*$database = \Drupal::database();*/

    //count sessions
    $session_num = $this->db->select('sessions', 's')
                              ->countQuery()
                              ->execute();
    $num = $session_num->fetchField();

    //users unique
    $users = $this->db->select('sessions', 's')
                        ->groupBy('uid', array('uid'))
                        ->countQuery()
                        ->execute();
    $numusers = $users->fetchField();

    $build = array(
      '#markup' => $this->t('Il y a actuellement @num sessions ouvertes et @users users uniques sur le site.',array('@num' => $num,'@users' => $numusers)),
      '#cache' =>array(
        'max-age' => 10
      ),
    );
    return $build;
  }
}