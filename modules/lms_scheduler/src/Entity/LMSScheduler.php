<?php

namespace Drupal\lms_scheduler\Entity;

use Drupal\user\EntityOwnerTrait;
use Drupal\address\AddressInterface;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\EntityPublishedTrait;
use Drupal\Core\Entity\EntityStorageInterface;

/**
 * Defines the lms_scheduler entity class.
 *
 * @ContentEntityType(
 *   id = "lms_scheduler",
 *   label = @Translation("LMS Scheduler", context = "LMS"),
 *   label_collection = @Translation("LMS Scheduler items", context = "LMS"),
 *   label_singular = @Translation("lms scheduler item", context = "LMS"),
 *   label_plural = @Translation("lms scheduler items", context = "LMS"),
 *   label_count = @PluralTranslation(
 *     singular = "@count lms scheduler item",
 *     plural = "@count lms scheduler items",
 *     context = "LMS",
 *   ),
 *   bundle_label = @Translation("LMS Scheduler type", context = "LMS"),
 *   handlers = {
 *     "event" = "Drupal\lms_scheduler\Event\LMSSchedulerEvent",
 *     "storage" = "Drupal\lms_scheduler\LMSSchedulerStorage",
 *     "access" = "Drupal\entity\EntityAccessControlHandler",
 *     "query_access" = "Drupal\entity\QueryAccess\QueryAccessHandler",
 *     "permission_provider" = "Drupal\entity\EntityPermissionProvider",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\lms_scheduler\LMSSchedulerListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "form" = {
 *       "default" = "Drupal\lms_scheduler\Form\LMSSchedulerForm",
 *       "add" = "Drupal\lms_scheduler\Form\LMSSchedulerForm",
 *       "edit" = "Drupal\lms_scheduler\Form\LMSSchedulerForm",
 *       "duplicate" = "Drupal\lms_scheduler\Form\LMSSchedulerForm",
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
 *   base_table = "lms_scheduler",
 *   data_table = "lms_scheduler_field_data",
 *   admin_permission = "administer lms_scheduler",
 *   permission_granularity = "bundle",
 *   translatable = TRUE,
 *   entity_keys = {
 *     "id" = "lms_scheduler_id",
 *     "uuid" = "uuid",
 *     "bundle" = "type",
 *     "label" = "name",
 *     "langcode" = "langcode",
 *     "published" = "status",
 *     "owner" = "uid",
 *     "uid" = "uid",
 *   },
 *   links = {
 *     "canonical" = "/lms-scheduler/{lms_scheduler}",
 *     "add-page" = "/lms-scheduler/add",
 *     "add-form" = "/lms-scheduler/add/{lms_scheduler_type}",
 *     "edit-form" = "/lms-scheduler/{lms_scheduler}/edit",
 *     "duplicate-form" = "/lms-scheduler/{lms_scheduler}/duplicate",
 *     "delete-form" = "/lms-scheduler/{lms_scheduler}/delete",
 *     "delete-multiple-form" = "/admin/content/lms-scheduler-items/delete",
 *     "collection" = "/admin/content/lms-scheduler-items",
 *   },
 *   bundle_entity_type = "lms_scheduler_type",
 *   field_ui_base_route = "entity.lms_scheduler_type.edit_form",
 * )
 */
class LMSScheduler extends ContentEntityBase implements LMSSchedulerInterface {

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
      ->setLabel('Type')
      ->setDescription('The lms_scheduler type.')
      ->setSetting('target_type', 'lms_scheduler_type')
      ->setReadOnly(TRUE);

    $fields['uid']
      ->setLabel('Owner')
      ->setDescription('The lms_scheduler owner.')
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayConfigurable('form', TRUE);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel('Name')
      ->setDescription('The lms_scheduler name.')
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
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'string',
      ])
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayConfigurable('form', TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel('Created')
      ->setDescription('The time when the lms_scheduler was created.')
      ->setTranslatable(TRUE)
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel('Changed')
      ->setDescription('The time when the lms_scheduler was last edited.')
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
