<?php
/**
 * @file
 * Contain Queue Worker Plugin for User insert.
 */

namespace Drupal\d8_card_7\Plugin\QueueWorker;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Mail\MailManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Queue\QueueWorkerBase;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * User mailer to send a welcome email to new user.
 *
 * @QueueWorker(
 *   id = "cron_user_mailer",
 *   title = @Translation("Cron User Mailer"),
 *   cron = {"time" = 10}
 * )
 *
 */
class UserMailer extends QueueWorkerBase implements ContainerFactoryPluginInterface {

  /**
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $userStorage;

  /**
   * @var \Drupal\Core\Mail\MailManagerInterface
   */
  protected $mailManager;

  /**
   * UserMailer constructor.
   *
   * @param \Drupal\Core\Entity\EntityStorageInterface $user_storage
   *  The user storage
   * @param \Drupal\Core\Mail\MailManagerInterface $mail_manager
   *  The mail manager
   */
  public function __construct(EntityStorageInterface $user_storage, MailManagerInterface $mail_manager) {
    $this->userStorage = $user_storage;
    $this->mailManager = $mail_manager;
  }

  /**
   * {@inheritDoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $container->get('entity.manager')->getStorage('user'),
      $container->get('plugin.manager.mail'),
    );
  }

  /**
   * {@inheritDoc}
   */
  public function processItem($data) {
    // Load user.
    $user = $this->userStorage->load($data->uid);

    // TODO: Send Mail Logic.

    // Register messenger.

    \Drupal::messenger()->addMessage("Mail sent to user " . $user->getEmail());

    // Log info
    $this->logger->info("Mail sent to user " . $user->getEmail());
  }

}
