<?php

namespace Drupal\custom_shorturl\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Drupal\file\Entity\File;
use Drupal\Core\File\FileSystem;

/**
 * Class ShortUrlController
 *
 * @package Drupal\custom_shorturl\Controller
 */
class ShortUrlController extends ControllerBase {

  protected $fileSystem;

  /**
   * {@inheritdoc}
   */
  public function __construct(FileSystem $file_system) {
    $this->fileSystem = $file_system;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('file_system')
    );
  }
  /**
   * {@inheritdoc}
   */
  public function loadImage() {
    $fid = 2;
    $file = File::load($fid);
    $uri = $file->getFileUri();

    $server_path = $this->fileSystem->realpath($uri);

    return new BinaryFileResponse($server_path);
  }

}
