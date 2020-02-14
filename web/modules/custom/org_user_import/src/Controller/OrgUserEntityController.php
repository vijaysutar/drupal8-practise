<?php

namespace Drupal\org_user_import\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Datetime\DateFormatter;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Render\Renderer;
use Drupal\Core\Url;
use Drupal\org_user_import\Entity\OrgUserEntityInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class OrgUserEntityController.
 *
 *  Returns responses for Org user entity routes.
 */
class OrgUserEntityController extends ControllerBase implements ContainerInjectionInterface {


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
   * Constructs a new OrgUserEntityController.
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
   * Displays a Org user entity revision.
   *
   * @param int $org_user_entity_revision
   *   The Org user entity revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($org_user_entity_revision) {
    $org_user_entity = $this->entityTypeManager()->getStorage('org_user_entity')
      ->loadRevision($org_user_entity_revision);
    $view_builder = $this->entityTypeManager()->getViewBuilder('org_user_entity');

    return $view_builder->view($org_user_entity);
  }

  /**
   * Page title callback for a Org user entity revision.
   *
   * @param int $org_user_entity_revision
   *   The Org user entity revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($org_user_entity_revision) {
    $org_user_entity = $this->entityTypeManager()->getStorage('org_user_entity')
      ->loadRevision($org_user_entity_revision);
    return $this->t('Revision of %title from %date', [
      '%title' => $org_user_entity->label(),
      '%date' => $this->dateFormatter->format($org_user_entity->getRevisionCreationTime()),
    ]);
  }

  /**
   * Generates an overview table of older revisions of a Org user entity.
   *
   * @param \Drupal\org_user_import\Entity\OrgUserEntityInterface $org_user_entity
   *   A Org user entity object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(OrgUserEntityInterface $org_user_entity) {
    $account = $this->currentUser();
    $org_user_entity_storage = $this->entityTypeManager()->getStorage('org_user_entity');

    $langcode = $org_user_entity->language()->getId();
    $langname = $org_user_entity->language()->getName();
    $languages = $org_user_entity->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $org_user_entity->label()]) : $this->t('Revisions for %title', ['%title' => $org_user_entity->label()]);

    $header = [$this->t('Revision'), $this->t('Operations')];
    $revert_permission = (($account->hasPermission("revert all org user entity revisions") || $account->hasPermission('administer org user entity entities')));
    $delete_permission = (($account->hasPermission("delete all org user entity revisions") || $account->hasPermission('administer org user entity entities')));

    $rows = [];

    $vids = $org_user_entity_storage->revisionIds($org_user_entity);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\org_user_import\OrgUserEntityInterface $revision */
      $revision = $org_user_entity_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = $this->dateFormatter->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $org_user_entity->getRevisionId()) {
          $link = $this->l($date, new Url('entity.org_user_entity.revision', [
            'org_user_entity' => $org_user_entity->id(),
            'org_user_entity_revision' => $vid,
          ]));
        }
        else {
          $link = $org_user_entity->link($date);
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
              Url::fromRoute('entity.org_user_entity.translation_revert', [
                'org_user_entity' => $org_user_entity->id(),
                'org_user_entity_revision' => $vid,
                'langcode' => $langcode,
              ]) :
              Url::fromRoute('entity.org_user_entity.revision_revert', [
                'org_user_entity' => $org_user_entity->id(),
                'org_user_entity_revision' => $vid,
              ]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.org_user_entity.revision_delete', [
                'org_user_entity' => $org_user_entity->id(),
                'org_user_entity_revision' => $vid,
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

    $build['org_user_entity_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
