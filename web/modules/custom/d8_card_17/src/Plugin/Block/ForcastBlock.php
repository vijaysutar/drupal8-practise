<?php

namespace Drupal\d8_card_17\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Forecast\Forecast;

/**
 * Provides a 'ForcastBlock' block.
 *
 * @Block(
 *  id = "forcast_block",
 *  admin_label = @Translation("Forcast block"),
 * )
 */
class ForcastBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
    ] + parent::defaultConfiguration();
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form['latitude'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Enter Latitude'),
      '#description' => $this->t('Enter Latitude'),
      '#default_value' => $this->configuration['latitude'],
      '#maxlength' => 255,
      '#size' => 100,
      '#weight' => '0',
    ];

    $form['longitude'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Enter Longitude'),
      '#description' => $this->t('Enter Longitude'),
      '#default_value' => $this->configuration['longitude'],
      '#maxlength' => 255,
      '#size' => 100,
      '#weight' => '0',
    ];


    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['latitude'] = $form_state->getValue('latitude');
    $this->configuration['longitude'] = $form_state->getValue('longitude');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {

    $forecast = new Forecast('7411b0e6d5e0c99fbd7405fd6de00cd5');
    // Get the current forecast for a given latitude and longitude
    $update = $forecast->get($this->configuration['latitude'],$this->configuration['longitude']);
    $summary = $update->currently->summary;
    $temparature = $update->currently->temperature;
    $body = "Forecast is $summary with temparature of $temparature dec C.";
    return [
      'weather' => [
        '#markup' => $body,
      ],
    ];


    $build = [];
    $build['#theme'] = 'forcast_block';
    $build['#conten'][] = $this->configuration['latitude'];
    $build['#conten'][] = $this->configuration['longitude'];

    return $build;
  }

}
