<?php

namespace Drupal\we_notification\Entity;

use Drupal\user\UserInterface;
use Drupal\user\EntityOwnerTrait;
use Drupal\address\AddressInterface;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\EntityPublishedTrait;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\FieldStorageDefinitionInterface;

/**
 * Defines the we_notification entity class.
 *
 * @ContentEntityType(
 *   id = "we_notification",
 *   label = @Translation("Notification", context = "Custom Entity Modules"),
 *   label_collection = @Translation("Notification items", context = "Custom Entity Modules"),
 *   label_singular = @Translation("notification item", context = "Custom Entity Modules"),
 *   label_plural = @Translation("notification items", context = "Custom Entity Modules"),
 *   label_count = @PluralTranslation(
 *     singular = "@count notification item",
 *     plural = "@count notification items",
 *     context = "Custom Entity Modules",
 *   ),
 *   bundle_label = @Translation("Notification type", context = "Custom Entity Modules"),
 *   handlers = {
 *     "event" = "Drupal\we_notification\Event\WeNotificationEvent",
 *     "storage" = "Drupal\we_notification\WeNotificationStorage",
 *     "access" = "Drupal\entity\EntityAccessControlHandler",
 *     "query_access" = "Drupal\entity\QueryAccess\QueryAccessHandler",
 *     "permission_provider" = "Drupal\entity\EntityPermissionProvider",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\we_notification\WeNotificationListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "form" = {
 *       "default" = "Drupal\we_notification\Form\WeNotificationForm",
 *       "add" = "Drupal\we_notification\Form\WeNotificationForm",
 *       "edit" = "Drupal\we_notification\Form\WeNotificationForm",
 *       "duplicate" = "Drupal\we_notification\Form\WeNotificationForm",
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
 *   base_table = "we_notification",
 *   data_table = "we_notification_field_data",
 *   admin_permission = "administer we_notification",
 *   permission_granularity = "bundle",
 *   translatable = TRUE,
 *   entity_keys = {
 *     "id" = "we_notification_id",
 *     "uuid" = "uuid",
 *     "bundle" = "type",
 *     "label" = "name",
 *     "langcode" = "langcode",
 *     "published" = "status",
 *     "owner" = "uid",
 *     "uid" = "uid",
 *   },
 *   links = {
 *     "canonical" = "/we-notification/{we_notification}",
 *     "add-page" = "/we-notification/add",
 *     "add-form" = "/we-notification/add/{we_notification_type}",
 *     "edit-form" = "/we-notification/{we_notification}/edit",
 *     "duplicate-form" = "/we-notification/{we_notification}/duplicate",
 *     "delete-form" = "/we-notification/{we_notification}/delete",
 *     "delete-multiple-form" = "/admin/content/we-notification-items/delete",
 *     "collection" = "/admin/content/we-notification-items",
 *   },
 *   bundle_entity_type = "we_notification_type",
 *   field_ui_base_route = "entity.we_notification_type.edit_form",
 * )
 */
class WeNotification extends ContentEntityBase implements WeNotificationInterface {

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

    $fields['nid'] = BaseFieldDefinition::create('integer')
    ->setLabel(t('Notification ID'))
    ->setDescription(t('The ID of the notification.'))
    ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'text_default',
        'weight' => 10,
      ])
      ->setDisplayOptions('form', [
        'type' => 'text_textarea',
        'weight' => 10,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['body'] = BaseFieldDefinition::create('text_long')
    ->setLabel(t('Body'))
    ->setDescription(t('Body'))
    ->setRequired(TRUE)
    ->setDisplayOptions('view', [
      'label' => 'hidden',
      'type' => 'text_default',
      'weight' => 10,
    ])
    ->setDisplayOptions('form', [
      'type' => 'text_textarea',
      'weight' => 10,
    ])
    ->setDisplayConfigurable('form', TRUE)
    ->setDisplayConfigurable('view', TRUE);

    $fields['source'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Source'))
      ->setCardinality(FieldStorageDefinitionInterface::CARDINALITY_UNLIMITED)
      ->setDescription(t('The source of the notification in the format key: value.'))
      ->setSettings([
        'default_value' => NULL,
        'max_length' => 255,
      ])
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -5,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

  $fields['link'] = BaseFieldDefinition::create('uri')
    ->setLabel(t('Link'))
    ->setDescription(t('A link associated with the notification.'))
    ->setDisplayOptions('view', [
      'label' => 'inline',
      'type' => 'entity_reference_label',
      'weight' => 30,
    ])
    ->setDisplayOptions('form', [
      'type' => 'entity_reference_autocomplete',
      'weight' => 30,
    ])
    ->setDisplayConfigurable('view', TRUE)
    ->setDisplayConfigurable('form', TRUE);

    $fields['type'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Type'))
      ->setDescription(t('The we_notification type.'))
      ->setSetting('target_type', 'we_notification_type')
      ->setReadOnly(TRUE);

    $fields['received_uid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Received uid'))
      ->setDescription(t('The receiver of the notification.'))
      ->setSetting('target_type', 'user')
      ->setSetting('multiple', TRUE)
      ->setCardinality(FieldStorageDefinitionInterface::CARDINALITY_UNLIMITED)
      ->setDisplayOptions('form', [
        'type' => 'options_buttons',
        'weight' => -2,
      ])
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayConfigurable('form', TRUE);

    $fields['uid']
      ->setLabel(t('Owner'))
      ->setDescription(t('The owner.'))
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayConfigurable('form', TRUE);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('Name'))
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

      $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Read Status'))
      ->setDescription(t('Status of post action entity'))
      ->setDefaultValue(TRUE)
      ->setSettings(['on_label' => 'Yes', 'off_label' => 'No'])
      ->setDisplayOptions('view', [
        'label' => 'visible',
        'type' => 'boolean',
        'weight' => 2,
      ])
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'weight' => 2,
      ])
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayConfigurable('form', TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time when the we_notification was created.'))
      ->setTranslatable(TRUE)
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time when the we_notification was last edited.'))
      ->setTranslatable(TRUE)
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

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
