<?php

namespace Drupal\d8_card_11\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the Contact entity type entity.
 *
 * @ConfigEntityType(
 *   id = "contact_entity_type",
 *   label = @Translation("Contact entity type"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\d8_card_11\ContactEntityTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\d8_card_11\Form\ContactEntityTypeForm",
 *       "edit" = "Drupal\d8_card_11\Form\ContactEntityTypeForm",
 *       "delete" = "Drupal\d8_card_11\Form\ContactEntityTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\d8_card_11\ContactEntityTypeHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "contact_entity_type",
 *   admin_permission = "administer site configuration",
 *   bundle_of = "contact_entity",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/contact_entity_type/{contact_entity_type}",
 *     "add-form" = "/admin/structure/contact_entity_type/add",
 *     "edit-form" = "/admin/structure/contact_entity_type/{contact_entity_type}/edit",
 *     "delete-form" = "/admin/structure/contact_entity_type/{contact_entity_type}/delete",
 *     "collection" = "/admin/structure/contact_entity_type"
 *   }
 * )
 */
class ContactEntityType extends ConfigEntityBundleBase implements ContactEntityTypeInterface {

  /**
   * The Contact entity type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Contact entity type label.
   *
   * @var string
   */
  protected $label;

}
