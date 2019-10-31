<?php

namespace Drupal\d8_card_11\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Datetime\DateFormatter;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Render\Renderer;
use Drupal\Core\Url;
use Drupal\d8_card_11\Entity\ContactEntityInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class ContactEntityController.
 *
 *  Returns responses for Contact entity routes.
 */
class ContactEntityController extends ControllerBase implements ContainerInjectionInterface {


  /**
   * The date formatter.
   *
   * @var \Drupal\Core\Datetime\DateFormatter
   */
  protected $dateFormatter;

  /**
   * The renderer.
   *
   * @var \Drupal\Core\Render\Renderer
   */
  protected $renderer;

  /**
   * Constructs a new ContactEntityController.
   *
   * @param \Drupal\Core\Datetime\DateFormatter $date_formatter
   *   The date formatter.
   * @param \Drupal\Core\Render\Renderer $renderer
   *   The renderer.
   */
  public function __construct(DateFormatter $date_formatter, Renderer $renderer) {
    $this->dateFormatter = $date_formatter;
    $this->renderer = $renderer;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('date.formatter'),
      $container->get('renderer')
    );
  }

  /**
   * Displays a Contact entity revision.
   *
   * @param int $contact_entity_revision
   *   The Contact entity revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($contact_entity_revision) {
    $contact_entity = $this->entityTypeManager()->getStorage('contact_entity')
      ->loadRevision($contact_entity_revision);
    $view_builder = $this->entityTypeManager()->getViewBuilder('contact_entity');

    return $view_builder->view($contact_entity);
  }

  /**
   * Page title callback for a Contact entity revision.
   *
   * @param int $contact_entity_revision
   *   The Contact entity revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($contact_entity_revision) {
    $contact_entity = $this->entityTypeManager()->getStorage('contact_entity')
      ->loadRevision($contact_entity_revision);
    return $this->t('Revision of %title from %date', [
      '%title' => $contact_entity->label(),
      '%date' => $this->dateFormatter->format($contact_entity->getRevisionCreationTime()),
    ]);
  }

  /**
   * Generates an overview table of older revisions of a Contact entity.
   *
   * @param \Drupal\d8_card_11\Entity\ContactEntityInterface $contact_entity
   *   A Contact entity object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(ContactEntityInterface $contact_entity) {
    $account = $this->currentUser();
    $contact_entity_storage = $this->entityTypeManager()->getStorage('contact_entity');

    $langcode = $contact_entity->language()->getId();
    $langname = $contact_entity->language()->getName();
    $languages = $contact_entity->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $contact_entity->label()]) : $this->t('Revisions for %title', ['%title' => $contact_entity->label()]);

    $header = [$this->t('Revision'), $this->t('Operations')];
    $revert_permission = (($account->hasPermission("revert all contact entity revisions") || $account->hasPermission('administer contact entity entities')));
    $delete_permission = (($account->hasPermission("delete all contact entity revisions") || $account->hasPermission('administer contact entity entities')));

    $rows = [];

    $vids = $contact_entity_storage->revisionIds($contact_entity);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\d8_card_11\ContactEntityInterface $revision */
      $revision = $contact_entity_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = $this->dateFormatter->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $contact_entity->getRevisionId()) {
          $link = $this->l($date, new Url('entity.contact_entity.revision', [
            'contact_entity' => $contact_entity->id(),
            'contact_entity_revision' => $vid,
          ]));
        }
        else {
          $link = $contact_entity->link($date);
        }

        $row = [];
        $column = [
          'data' => [
            '#type' => 'inline_template',
            '#template' => '{% trans %}{{ date }} by {{ username }}{% endtrans %}{% if message %}<p class="revision-log">{{ message }}</p>{% endif %}',
            '#context' => [
              'date' => $link,
              'username' => $this->renderer->renderPlain($username),
              'message' => [
                '#markup' => $revision->getRevisionLogMessage(),
                '#allowed_tags' => Xss::getHtmlTagList(),
              ],
            ],
          ],
        ];
        $row[] = $column;

        if ($latest_revision) {
          $row[] = [
            'data' => [
              '#prefix' => '<em>',
              '#markup' => $this->t('Current revision'),
              '#suffix' => '</em>',
            ],
          ];
          foreach ($row as &$current) {
            $current['class'] = ['revision-current'];
          }
          $latest_revision = FALSE;
        }
        else {
          $links = [];
          if ($revert_permission) {
            $links['revert'] = [
              'title' => $this->t('Revert'),
              'url' => $has_translations ?
              Url::fromRoute('entity.contact_entity.translation_revert', [
                'contact_entity' => $contact_entity->id(),
                'contact_entity_revision' => $vid,
                'langcode' => $langcode,
              ]) :
              Url::fromRoute('entity.contact_entity.revision_revert', [
                'contact_entity' => $contact_entity->id(),
                'contact_entity_revision' => $vid,
              ]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.contact_entity.revision_delete', [
                'contact_entity' => $contact_entity->id(),
                'contact_entity_revision' => $vid,
              ]),
            ];
          }

          $row[] = [
            'data' => [
              '#type' => 'operations',
              '#links' => $links,
            ],
          ];
        }

        $rows[] = $row;
      }
    }

    $build['contact_entity_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
