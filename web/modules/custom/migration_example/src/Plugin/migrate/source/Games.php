<?php

namespace Drupal\migration_example\Plugin\migrate\source;

use Drupal\migrate\Annotation\MigrateSource;
use Drupal\migrate\Plugin\migrate\source\SqlBase;
use Drupal\migrate\Row;

/**
 * Class Games
 *
 * @MigrateSource(
 *   id = "games",
 * )
 */
class Games extends SqlBase {

  public function query() {
    $query = $this->select('curling_games','g')
      ->fields('g',[
        'game_id',
        'title',
        'date',
        'time',
        'place'
      ]);
    return $query;
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    $fields = [
      'game_id' => $this->t('game_id' ),
      'title'   => $this->t('title' ),
      'date'    => $this->t('date'),
      'time'    => $this->t('time'),
      'place'   => $this->t('place' ),
    ];
    return $fields;
  }

  public function getIds() {
    return [
      'game_id' => [
        'type' => 'integer',
        'alias' => 'g'
      ]
    ];
  }

  public function prepareRow(Row $row) {

    $date = $row->getSourceProperty('date');
    $time = $row->getSourceProperty('time');
    $datetime = $date . 'T' . $time;
    $row->setSourceProperty('datetime', $datetime);
    return parent::prepareRow($row);
  }

}
