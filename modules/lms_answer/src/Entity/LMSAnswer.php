<?php

namespace Drupal\lms_answer\Entity;

use Drupal\user\EntityOwnerTrait;
use Drupal\address\AddressInterface;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\EntityPublishedTrait;
use Drupal\Core\Entity\EntityStorageInterface;

/**
 * Defines the lms_answer entity class.
 *
 * @ContentEntityType(
 *   id = "lms_answer",
 *   label = @Translation("Answer", context = "Custom Entity Modules"),
 *   label_collection = @Translation("Answer items", context = "Custom Entity Modules"),
 *   label_singular = @Translation("answer item", context = "Custom Entity Modules"),
 *   label_plural = @Translation("answer items", context = "Custom Entity Modules"),
 *   label_count = @PluralTranslation(
 *     singular = "@count answer item",
 *     plural = "@count answer items",
 *     context = "Custom Entity Modules",
 *   ),
 *   bundle_label = @Translation("Answer type", context = "Custom Entity Modules"),
 *   handlers = {
 *     "event" = "Drupal\lms_answer\Event\LMSAnswerEvent",
 *     "storage" = "Drupal\lms_answer\LMSAnswerStorage",
 *     "access" = "Drupal\entity\EntityAccessControlHandler",
 *     "query_access" = "Drupal\entity\QueryAccess\QueryAccessHandler",
 *     "permission_provider" = "Drupal\entity\EntityPermissionProvider",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\lms_answer\LMSAnswerListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "form" = {
 *       "default" = "Drupal\lms_answer\Form\LMSAnswerForm",
 *       "add" = "Drupal\lms_answer\Form\LMSAnswerForm",
 *       "edit" = "Drupal\lms_answer\Form\LMSAnswerForm",
 *       "duplicate" = "Drupal\lms_answer\Form\LMSAnswerForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm"
 *     },
 *     "local_task_provider" = {
 *       "default" = "Drupal\entity\Menu\DefaultEntityLocalTaskProvider",
 *     },
 *     "route_provider" = {
 *       "default" = "Drupal\entity\Routing\AdminHtmlRouteProvider",
 *       "delete-multiple" = "Drupal\entity\Routing\DeleteMultipleRouteProvider",
 *     },
 *     "translation" = "Drupal\content_translation\ContentTranslationHandler"
 *   },
 *   base_table = "lms_answer",
 *   data_table = "lms_answer_field_data",
 *   admin_permission = "administer lms_answer",
 *   permission_granularity = "bundle",
 *   translatable = TRUE,
 *   entity_keys = {
 *     "id" = "lms_answer_id",
 *     "uuid" = "uuid",
 *     "bundle" = "type",
 *     "label" = "name",
 *     "langcode" = "langcode",
 *     "published" = "status",
 *     "owner" = "uid",
 *     "uid" = "uid",
 *   },
 *   links = {
 *     "canonical" = "/lms-answer/{lms_answer}",
 *     "add-page" = "/lms-answer/add",
 *     "add-form" = "/lms-answer/add/{lms_answer_type}",
 *     "edit-form" = "/lms-answer/{lms_answer}/edit",
 *     "duplicate-form" = "/lms-answer/{lms_answer}/duplicate",
 *     "delete-form" = "/lms-answer/{lms_answer}/delete",
 *     "delete-multiple-form" = "/admin/content/lms-answer-items/delete",
 *     "collection" = "/admin/content/lms-answer-items",
 *   },
 *   bundle_entity_type = "lms_answer_type",
 *   field_ui_base_route = "entity.lms_answer_type.edit_form",
 * )
 */
class LMSAnswer extends ContentEntityBase implements LMSAnswerInterface {

  use EntityOwnerTrait;
  use EntityChangedTrait;
  use EntityPublishedTrait;

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->get('name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setName($name) {
    $this->set('name', $name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);
    $fields += static::ownerBaseFieldDefinitions($entity_type);

    $fields['type'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Type'))
      ->setDescription(t('The lms_answer type.'))
      ->setSetting('target_type', 'lms_answer_type')
      ->setReadOnly(TRUE);

    $fields['uid']
      ->setLabel(t('Owner'))
      ->setDescription(t('The lms_answer owner.'))
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayConfigurable('form', TRUE);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The lms_answer name.'))
      ->setRequired(TRUE)
      ->setTranslatable(TRUE)
      ->setSettings([
        'default_value' => '',
        'max_length' => 255,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => 0,
      ])
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayConfigurable('form', TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time when the lms_answer was created.'))
      ->setTranslatable(TRUE)
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time when the lms_answer was last edited.'))
      ->setTranslatable(TRUE);

    $fields['status'] = BaseFieldDefinition::create('boolean')
    ->setLabel(t('Published'))
    ->setTranslatable(FALSE)
    ->setDefaultValue(TRUE)
    ->setDisplayOptions('form', [
      'type' => 'boolean_checkbox',
      'settings' => [
        'display_label' => TRUE,
      ],
      'weight' => 100,
    ])
    ->setDisplayConfigurable('view', TRUE)
    ->setDisplayConfigurable('form', TRUE);

    return $fields;
  }

  /**
   * Default value callback for the 'timezone' base field definition.
   *
   * @see ::baseFieldDefinitions()
   *
   * @return array
   *   An array of default values.
   */
  public static function getSiteTimezone() {
    $site_timezone = \Drupal::config('system.date')->get('timezone.default');
    if (empty($site_timezone)) {
      $site_timezone = @date_default_timezone_get();
    }

    return [$site_timezone];
  }

  /**
   * Gets the allowed values for the 'timezone' base field.
   *
   * @return array
   *   The allowed values.
   */
  public static function getTimezones() {
    return system_time_zones(NULL, TRUE);
  }

  /**
   * Gets the allowed values for the 'billing_countries' base field.
   *
   * @return array
   *   The allowed values.
   */
  public static function getAvailableCountries() {
    return \Drupal::service('address.country_repository')->getList();
  }

}
