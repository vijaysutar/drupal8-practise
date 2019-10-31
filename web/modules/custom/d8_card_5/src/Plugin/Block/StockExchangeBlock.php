<?php

namespace Drupal\d8_card_5\Plugin\Block;

use \Drupal\Core\Block\BlockBase;

/**
 * Provides a block with a simple text.
 *
 * @Block(
 *   id = "stock_exchange_rate_block",
 *   admin_label = @Translation("Stock Exchange Rates"),
 * )
 */
class StockExchangeBlock extends BlockBase {

  /**
   * {@inheritDoc}
   */
  public function build() {
    return [
      '#markup' => $this->t('This is Stock Exchange Block')
    ];
  }

}
