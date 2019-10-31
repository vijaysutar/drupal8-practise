<?php

namespace Drupal\d8_card_4\Plugin\migrate\source;

use Drupal\migrate\Annotation\MigrateSource;
use Drupal\migrate\Plugin\migrate\source\SqlBase;

/**
 * Source plugin for the movies.
 *
 * @MigrateSource(
 *  id = "movies"
 * )
 */
class Movies extends SqlBase {

  /**
   * {@inheritdoc}
   */
  public function query()
  {
    $query = $this->select('movies','d')
    ->fields('d', ['id', 'name', 'description']);

    return $query;
  }

  /**
   * {@inheritdoc}
   */
  public function fields()
  {
    $fields = [
      'id' => $this->t('Movie ID'),
      'name' => $this->t('Movie Name'),
      'description' => $this->t('Movie Description'),
      'genres' => $this->t('Movies Genres')
    ];

    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function getIds()
  {
    return [
      'id' => [
        'type' => 'integer',
        'alias' => 'd'
      ]
    ];
  }

  public function prepareRow(\Drupal\migrate\Row $row)
  {
    $genres = $this->select('movies_genres', 'g')
    ->fields('g', ['id'])
    ->condition('movie_id', $row->getSourceProperty('id'))
    ->execute()
    ->fetchCol();

    $row->setSourceProperty('genres', $genres);

    return parent::prepareRow($row);
  }
}
