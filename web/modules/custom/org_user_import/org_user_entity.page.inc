<?php

/**
 * @file
 * Contains org_user_entity.page.inc.
 *
 * Page callback for Org user entity entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Org user entity templates.
 *
 * Default template: org_user_entity.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_org_user_entity(array &$variables) {
  // Fetch OrgUserEntity Entity Object.
  $org_user_entity = $variables['elements']['#org_user_entity'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
