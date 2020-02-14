<?php

namespace Drupal\org_user_import;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Org user entity entities.
 *
 * @ingroup org_user_import
 */
class OrgUserEntityListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Org user entity ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var \Drupal\org_user_import\Entity\OrgUserEntity $entity */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.org_user_entity.edit_form',
      ['org_user_entity' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
