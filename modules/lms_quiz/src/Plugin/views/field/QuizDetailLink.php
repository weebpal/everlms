<?php

namespace Drupal\lms_quiz\Plugin\views\field;

use Drupal\Core\Url;
use Drupal\views\ResultRow;
use Drupal\node\NodeInterface;
use Drupal\lms_quiz\Entity\LMSQuiz;
use Psr\Container\ContainerInterface;
use Drupal\lms_base\PermissionManager;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\commerce_product\Entity\Product;
use Drupal\views\Plugin\views\field\FieldPluginBase;

/**
 *
 * @ingroup views_field_handlers
 *
 * @ViewsField("lms_quiz_detail_link")
 */
class QuizDetailLink extends FieldPluginBase {

  /**
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected AccountInterface $account;

  /**
   * @var \Drupal\lms_base\PermissionManager
   */
  protected PermissionManager $permissionManager;

  /**
   * @param array $configuration
   * @param $plugin_id
   * @param $plugin_definition
   * @param \Drupal\Core\Session\AccountInterface $account
   * @param \Drupal\lms_base\PermissionManager $permission_manager
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, AccountInterface $account, PermissionManager $permission_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->account = $account;
    $this->permissionManager = $permission_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static($configuration, $plugin_id, $plugin_definition, $container->get('current_user'), $container->get('lms_base.permission_manager'));
  }

  /**
   * {@inheritdoc}
   */
  public function query() {
    // Do nothing -- to override the parent query.
  }

  /**
   * {@inheritdoc}
   */
  protected function defineOptions() {
    $options = parent::defineOptions();
    $options['target_attribute'] = ['default' => $this->definition['target_attribute default'] ?? '_blank'];
    $options['link_text'] = ['default' => $this->definition['link_text default'] ?? t('View detail')];
    $options['link_plain'] = ['default' => $this->definition['link_plain default'] ?? TRUE];
    return $options;
  }

  /**
   * Provide link to node option
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    $form['target_attribute'] = [
      '#title' => t('Target Attribute'),
      '#type' => 'select',
      '#options' => [
        '_blank' => t('Blank'),
        '_self' => t('Self'),
        '_parent' => t('Parent'),
        '_top' => t('Top'),
      ],
      '#description' => t('The target attribute specifies where to open the linked document'),
      '#default_value' => $this->options['target_attribute'] ?? '_blank',
    ];
    $form['link_text'] = [
      '#title' => t('Link text'),
      '#type' => 'textfield',
      '#description' => t('The text display in link'),
      '#default_value' => $this->options['link_text'] ?? t('View detail'),
    ];
    $form['link_plain'] = [
      '#title' => t('Link plain'),
      '#type' => 'checkbox',
      '#description' => t('Get link without tag'),
      '#default_value' => $this->options['link_plain'] ?? TRUE,
    ];

    parent::buildOptionsForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function render(ResultRow $values) {
    $attributes = [];
    $entity = $values->_entity;
    if ($entity instanceof LMSQuiz) {
      $has_permission = FALSE;
      $relationship_entities = $values->_relationship_entities;
      if ($relationship_entities) {
        $relationship_entity = reset($relationship_entities);
        if ($relationship_entity instanceof Product) {
          $has_permission = $this->permissionManager->checkQuizPermissionWithCourse($this->account->id(), $entity->id(), $relationship_entity->id());
        }
      } else {
        $has_permission = $this->permissionManager->checkQuizPermission($this->account->id(), $entity->id());
      }
      if ($has_permission) {
        $url = Url::fromRoute('entity.lms_quiz.canonical', ['lms_quiz' => $entity->id()]);
        $attributes['target'] = $this->options['target_attribute'];
        if ($this->options['link_plain']) {
          return [
            '#markup' => $url->toString(),
          ];
        } else {
          return [
            '#type' => 'link',
            '#title' => $this->options['link_text'],
            '#url' => $url,
            '#attributes' => $attributes,
          ];
        }
      }
    }

    return NULL;
  }
}
