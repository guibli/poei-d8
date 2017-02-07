<?php

namespace Drupal\hello\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Datetime\DateFormatter;
use Symfony\Component\DependencyInjection\ContainerInterface;

class HelloControllerLastUpdate extends ControllerBase
{

  protected $formatter;

  public function __construct(DateFormatter $date_formatter)
  {
    $this->formatter = $date_formatter;
  }

  public static function create(ContainerInterface $container){
    return new static(
      $container->get('date.formatter')
    );
  }

  /**
   * @return string
   * Return node's title on last update page
   */
  public function newtitle(){
    $nid = \Drupal::routeMatch()->getParameter('node');
    $node =  \Drupal::entityTypeManager()->getStorage('node')->load($nid);
    $new_title = $node->getTitle().'\'s last updates';
    return $new_title;
  }
  public function content()
  {
    $nid = \Drupal::routeMatch()->getParameter('node');

    $query = \Drupal::database()
      ->select('hello_node_history','nfh')
      ->fields('nfh',array('uid','update_time'))
      ->condition('nid', $nid)
      ->execute();

    $result = $query->fetchAll();
    $header = array('Author',	'Timestamp');
    foreach ($result as $item) {
      $user =  \Drupal::entityTypeManager()->getStorage('user')->load($item->uid);
      $date = $this->formatter->format($item->update_time);
      $parsed['author']=$user->getUsername();
      $parsed['time'] = $date;
      $result_parsed[]=$parsed;
    }
    return [
      '#type' => 'table',
      '#header' => $header,
      '#rows' => $result_parsed,
    ];

  }

}