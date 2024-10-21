<?php

namespace Drupal\lms_user_course\Plugin\views\field;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\lms_user_course\Entity\LMSUserCourse;
use Drupal\node\NodeInterface;
use Drupal\views\Plugin\views\field\FieldPluginBase;
use Drupal\views\ResultRow;
use Psr\Container\ContainerInterface;

/**
 *
 * @ingroup views_field_handlers
 *
 * @ViewsField("lms_user_course_result_link")
 */
class UserCourseResultLink extends FieldPluginBase {

  /**
   * Constructs a NextInvoicePrice object.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static($configuration, $plugin_id, $plugin_definition);
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
    $options['link_text'] = ['default' => $this->definition['link_text default'] ?? t('View Result')];
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
      '#default_value' => $this->options['link_text'] ?? t('Take Quiz'),
    ];

    parent::buildOptionsForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function render(ResultRow $values) {
    $attributes = [];
    $entity = $values->_entity;
    $url = Url::fromRoute('<nolink>');
    if ($entity instanceof LMSUserCourse) {
      $url = Url::fromRoute('lms_user_course.result', ['lms_user_course' => $entity->id()]);
      $attributes['target'] = $this->options['target_attribute'];
    }
    return [
      '#type' => 'link',
      '#title' => $this->options['link_text'],
      '#url' => $url,
      '#attributes' => $attributes,
    ];
  }

}
