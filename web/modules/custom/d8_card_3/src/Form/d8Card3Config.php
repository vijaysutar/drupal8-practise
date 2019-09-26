<?php

namespace Drupal\d8_card_3\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * D8 Activity Card Config Class form
 */
class d8Card3Config extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['d8card3.configuration'];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'd8card3_configuration_form';
  }

  public function buildForm(array $form, \Drupal\Core\Form\FormStateInterface $form_state)
  {
    $config = $this->config('d8card3.configuration');

    $form['text_option'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Enter config name'),
      '#default_value' => $config->get('text_option'),
    ];

    $form['select_option'] = [
      '#type' => 'select',
      '#title' => $this->t('Select Element'),
      '#options' => [
        '1' => $this->t('One'),
        '2' => $this->t('Two')
      ],
      '#default_value' => $config->get('select_option'),
    ];

    $form['radio_option'] = array(
      '#type' => 'radios',
      '#title' => $this
        ->t('Poll status'),
      '#default_value' => $config->get('radio_option'),
      '#options' => array(
        0 => $this
          ->t('Closed'),
        1 => $this
          ->t('Active'),
      ),
    );

    return parent::buildForm($form, $form_state);
  }

  public function submitForm(array &$form, \Drupal\Core\Form\FormStateInterface $form_state)
  {
    $this->config('d8card3.configuration')
    ->set('text_option', $form_state->getValue('text_option'))
    ->set('select_option',$form_state->getValue('select_option'))
    ->set('radio_option', $form_state->getValue('radio_option'))
    ->save();

    parent::buildForm($form, $form_state);
  }

}

