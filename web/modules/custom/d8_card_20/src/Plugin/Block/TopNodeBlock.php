<?php

namespace Drupal\d8_card_20\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Render\RendererInterface;
use Drupal\Core\Session\AccountProxy;
use Drupal\node\NodeStorageInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'TopNodeBlock' block.
 *
 * @Block(
 *  id = "top_node_block",
 *  admin_label = @Translation("Top node block"),
 * )
 */
class TopNodeBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * @var \Drupal\node\NodeStorageInterface
   */
  protected $nodeStorage;

  /**
   * @var \Drupal\Core\Render\RendererInterface
   */
  protected $renderer;

  /**
   * @var \Drupal\Core\Session\AccountProxy
   */
  protected $accountProxy;

  /**
   * TopNodeBlock constructor.
   *
   * @param \Drupal\node\NodeStorageInterface $nodeStorage
   * @param \Drupal\Core\Render\RendererInterface $renderer
   * @param \Drupal\Core\Session\AccountProxy $accountProxy
   */
  public function __construct(NodeStorageInterface $nodeStorage, RendererInterface $renderer, AccountProxy $accountProxy) {
    $this->nodeStorage = $nodeStorage;
    $this->renderer = $renderer;
    $this->accountProxy = $accountProxy;
  }

  /**
   * Creates an instance of the plugin.
   *
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *   The container to pull out services used in the plugin.
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin ID for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   *
   * @return static
   *   Returns an instance of this plugin.
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $container->get('entity.manager')->getStorage('node'),
      $container->get('renderer'),
      $container->get('current_user')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];

    $ids = $this->nodeStorage->getQuery()
      ->condition('status',1)
      ->sort('changed','DESC')
      ->range(0,5)
      ->execute();
    $nodes = $this->nodeStorage->loadMultiple($ids);

    $build['#markup'] = '';
    $build['#cache']['#tags'][] = 'node_list';

    foreach ($nodes as $node) {
      $build['#markup'] .= '-' . $node->label();
      $this->renderer->addCacheableDependency($build, $node);
    }

    return $build;
  }

}
