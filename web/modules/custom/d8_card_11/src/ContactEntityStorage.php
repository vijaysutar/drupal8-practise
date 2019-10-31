<?php

namespace Drupal\d8_card_11;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\d8_card_11\Entity\ContactEntityInterface;

/**
 * Defines the storage handler class for Contact entity entities.
 *
 * This extends the base storage class, adding required special handling for
 * Contact entity entities.
 *
 * @ingroup d8_card_11
 */
class ContactEntityStorage extends SqlContentEntityStorage implements ContactEntityStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(ContactEntityInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {contact_entity_revision} WHERE id=:id ORDER BY vid',
      [':id' => $entity->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {contact_entity_field_revision} WHERE uid = :uid ORDER BY vid',
      [':uid' => $account->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function countDefaultLanguageRevisions(ContactEntityInterface $entity) {
    return $this->database->query('SELECT COUNT(*) FROM {contact_entity_field_revision} WHERE id = :id AND default_langcode = 1', [':id' => $entity->id()])
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function clearRevisionsLanguage(LanguageInterface $language) {
    return $this->database->update('contact_entity_revision')
      ->fields(['langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED])
      ->condition('langcode', $language->getId())
      ->execute();
  }

}
