<?php

namespace Drupal\org_user_import\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class OrgUserEntitySettingsForm.
 *
 * @ingroup org_user_import
 */
class OrgUserEntitySettingsForm extends FormBase {

  /**
   * Returns a unique string identifying the form.
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId() {
    return 'orguserentity_settings';
  }

  /**
   * Form submission handler.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Empty implementation of the abstract submit class.
  }

  /**
   * Defines the settings form for Org user entity entities.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @return array
   *   Form definition array.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['orguserentity_settings']['#markup'] = 'Settings form for Org user entity entities. Manage field settings here.';
    return $form;
  }

}
