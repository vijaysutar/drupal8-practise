<?php

namespace Drupal\d8_card_11\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Contact entity entities.
 *
 * @ingroup d8_card_11
 */
interface ContactEntityInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityPublishedInterface, EntityOwnerInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the Contact entity name.
   *
   * @return string
   *   Name of the Contact entity.
   */
  public function getName();

  /**
   * Sets the Contact entity name.
   *
   * @param string $name
   *   The Contact entity name.
   *
   * @return \Drupal\d8_card_11\Entity\ContactEntityInterface
   *   The called Contact entity entity.
   */
  public function setName($name);

  /**
   * Gets the Contact entity creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Contact entity.
   */
  public function getCreatedTime();

  /**
   * Sets the Contact entity creation timestamp.
   *
   * @param int $timestamp
   *   The Contact entity creation timestamp.
   *
   * @return \Drupal\d8_card_11\Entity\ContactEntityInterface
   *   The called Contact entity entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Gets the Contact entity revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Contact entity revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\d8_card_11\Entity\ContactEntityInterface
   *   The called Contact entity entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Contact entity revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Contact entity revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\d8_card_11\Entity\ContactEntityInterface
   *   The called Contact entity entity.
   */
  public function setRevisionUserId($uid);

}
