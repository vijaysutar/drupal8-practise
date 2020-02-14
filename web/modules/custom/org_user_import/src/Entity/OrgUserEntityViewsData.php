<?php

namespace Drupal\org_user_import\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Org user entity entities.
 */
class OrgUserEntityViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    // Additional information for Views integration, such as table joins, can be
    // put here.
    return $data;
  }

}
