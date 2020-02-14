<?php

namespace Drupal\org_user_import;

use Drupal\Core\Entity\ContentEntityStorageInterface;
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
interface OrgUserEntityStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Org user entity revision IDs for a specific Org user entity.
   *
   * @param \Drupal\org_user_import\Entity\OrgUserEntityInterface $entity
   *   The Org user entity entity.
   *
   * @return int[]
   *   Org user entity revision IDs (in ascending order).
   */
  public function revisionIds(OrgUserEntityInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Org user entity author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Org user entity revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\org_user_import\Entity\OrgUserEntityInterface $entity
   *   The Org user entity entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(OrgUserEntityInterface $entity);

  /**
   * Unsets the language for all Org user entity with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
