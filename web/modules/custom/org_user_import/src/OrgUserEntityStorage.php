<?php

namespace Drupal\org_user_import;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\org_user_import\Entity\OrgUserEntityInterface;

/**
 * Defines the storage handler class for Org user entity entities.
 *
 * This extends the base storage class, adding required special handling for
 * Org user entity entities.
 *
 * @ingroup org_user_import
 */
class OrgUserEntityStorage extends SqlContentEntityStorage implements OrgUserEntityStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(OrgUserEntityInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {org_user_entity_revision} WHERE id=:id ORDER BY vid',
      [':id' => $entity->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {org_user_entity_field_revision} WHERE uid = :uid ORDER BY vid',
      [':uid' => $account->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function countDefaultLanguageRevisions(OrgUserEntityInterface $entity) {
    return $this->database->query('SELECT COUNT(*) FROM {org_user_entity_field_revision} WHERE id = :id AND default_langcode = 1', [':id' => $entity->id()])
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function clearRevisionsLanguage(LanguageInterface $language) {
    return $this->database->update('org_user_entity_revision')
      ->fields(['langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED])
      ->condition('langcode', $language->getId())
      ->execute();
  }

}
