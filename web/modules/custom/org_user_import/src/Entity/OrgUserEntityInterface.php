<?php

namespace Drupal\org_user_import\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Org user entity entities.
 *
 * @ingroup org_user_import
 */
interface OrgUserEntityInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityPublishedInterface, EntityOwnerInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the Org user entity name.
   *
   * @return string
   *   Name of the Org user entity.
   */
  public function getName();

  /**
   * Sets the Org user entity name.
   *
   * @param string $name
   *   The Org user entity name.
   *
   * @return \Drupal\org_user_import\Entity\OrgUserEntityInterface
   *   The called Org user entity entity.
   */
  public function setName($name);

  /**
   * Gets the Org user entity creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Org user entity.
   */
  public function getCreatedTime();

  /**
   * Sets the Org user entity creation timestamp.
   *
   * @param int $timestamp
   *   The Org user entity creation timestamp.
   *
   * @return \Drupal\org_user_import\Entity\OrgUserEntityInterface
   *   The called Org user entity entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Gets the Org user entity revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Org user entity revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\org_user_import\Entity\OrgUserEntityInterface
   *   The called Org user entity entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Org user entity revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Org user entity revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\org_user_import\Entity\OrgUserEntityInterface
   *   The called Org user entity entity.
   */
  public function setRevisionUserId($uid);

}
