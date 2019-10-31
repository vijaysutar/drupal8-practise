<?php

namespace Drupal\d8_card_8\Plugin\Filter;

use Drupal\Core\Form\FormStateInterface;
use Drupal\filter\FilterProcessResult;
use Drupal\filter\Plugin\FilterBase;

/**
 * Class FilterCapitalize
 *
 * @Filter(
 *   id = "filter_capitalize",
 *   title = @Translation("Capitalize Filter"),
 *   description = @Translation("Capitalize the configured words"),
 *   type = Drupal\filter\Plugin\FilterInterface::TYPE_MARKUP_LANGUAGE,
 * )
 */
class FilterCapitalize extends FilterBase {


  /**
   * {@inheritDoc}
   */
  public function process($text, $langcode) {

    $new_text = $text;
    $words = $this->settings['capitalize_words'];
    $words_array = explode(',', $words);

    foreach ($words_array as $item){
      $new_text = str_replace($item, ucfirst($item), $new_text);
    }

    return new FilterProcessResult($new_text);
  }

  /**
   * {@inheritDoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $form['capitalize_words'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Words to Capitalize'),
      '#default_value' => $this->settings['capitalize_words'] ?? '',
      '#description' => $this->t('Enter a list of words in small case which should be capitalize')
    ];

    return $form;
  }

}
