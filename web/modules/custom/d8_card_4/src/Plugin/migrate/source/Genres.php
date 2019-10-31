<?php

namespace Drupal\d8_card_4\Plugin\migrate\source;

use Drupal\migrate\Annotation\MigrateSource;
use Drupal\migrate\Plugin\migrate\source\SqlBase;

/**
 * Source plugin for the genres
 *
 * @MigrateSource(
 *  id = "genres"
 * )
 */
class Genres extends SqlBase {

  /**
   * {@inheritdoc}
   *
   * The query() method creates the query for the genre data
   */
  public function query()
  {
    $query = $this->select('movies_genres', 'g')
    ->fields('g', ['id','movie_id','name']);

    return $query;
  }

  /**
   * {@inheritdoc}
   *
   * the fields() method defines each individual row field
   */
  public function fields()
  {
    $fields = [
      'id' => $this->t('Genre ID'),
      'movie_id' => $this->t('Movie ID'),
      'name' => $this->t('Genre name'),
    ];

    return $fields;
  }

  /**
   * {@inheritdoc}
   *
   * the getIds() method specifies the source row field that acts as the unique ID.
   */
  public function getIds()
  {
    return [
      'id' => [
        'type' => 'integer',
        'alias' => 'g'
      ]
    ];
  }
}
