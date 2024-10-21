<?php

namespace Drupal\lms_user_ceritificate\Entity;

use Drupal\user\EntityOwnerTrait;
use Drupal\address\AddressInterface;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\EntityPublishedTrait;
use Drupal\Core\Entity\EntityStorageInterface;

/**
 * Defines the lms_user_ceritificate entity class.
 *
 * @ContentEntityType(
 *   id = "lms_user_ceritificate",
 *   label = @Translation("User Ceritificate", context = "Custom Entity Modules"),
 *   label_collection = @Translation("User Ceritificate items", context = "Custom Entity Modules"),
 *   label_singular = @Translation("user ceritificate item", context = "Custom Entity Modules"),
 *   label_plural = @Translation("user ceritificate items", context = "Custom Entity Modules"),
 *   label_count = @PluralTranslation(
 *     singular = "@count user ceritificate item",
 *     plural = "@count user ceritificate items",
 *     context = "Custom Entity Modules",
 *   ),
 *   bundle_label = @Translation("User Ceritificate type", context = "Custom Entity Modules"),
 *   handlers = {
 *     "event" = "Drupal\lms_user_ceritificate\Event\LMSUserCertificateEvent",
 *     "storage" = "Drupal\lms_user_ceritificate\LMSUserCertificateStorage",
 *     "access" = "Drupal\entity\EntityAccessControlHandler",
 *     "query_access" = "Drupal\entity\QueryAccess\QueryAccessHandler",
 *     "permission_provider" = "Drupal\entity\EntityPermissionProvider",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\lms_user_ceritificate\LMSUserCertificateListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "form" = {
 *       "default" = "Drupal\lms_user_ceritificate\Form\LMSUserCertificateForm",
 *       "add" = "Drupal\lms_user_ceritificate\Form\LMSUserCertificateForm",
 *       "edit" = "Drupal\lms_user_ceritificate\Form\LMSUserCertificateForm",
 *       "duplicate" = "Drupal\lms_user_ceritificate\Form\LMSUserCertificateForm",
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
 *   base_table = "lms_user_ceritificate",
 *   data_table = "lms_user_ceritificate_field_data",
 *   admin_permission = "administer lms_user_ceritificate",
 *   permission_granularity = "bundle",
 *   translatable = TRUE,
 *   entity_keys = {
 *     "id" = "lms_user_ceritificate_id",
 *     "uuid" = "uuid",
 *     "bundle" = "type",
 *     "label" = "name",
 *     "langcode" = "langcode",
 *     "published" = "status",
 *     "owner" = "uid",
 *     "uid" = "uid",
 *   },
 *   links = {
 *     "canonical" = "/lms-user-ceritificate/{lms_user_ceritificate}",
 *     "add-page" = "/lms-user-ceritificate/add",
 *     "add-form" = "/lms-user-ceritificate/add/{lms_user_ceritificate_type}",
 *     "edit-form" = "/lms-user-ceritificate/{lms_user_ceritificate}/edit",
 *     "duplicate-form" = "/lms-user-ceritificate/{lms_user_ceritificate}/duplicate",
 *     "delete-form" = "/lms-user-ceritificate/{lms_user_ceritificate}/delete",
 *     "delete-multiple-form" = "/admin/content/lms-user-ceritificate-items/delete",
 *     "collection" = "/admin/content/lms-user-ceritificate-items",
 *   },
 *   bundle_entity_type = "lms_user_ceritificate_type",
 *   field_ui_base_route = "entity.lms_user_ceritificate_type.edit_form",
 * )
 */
class LMSUserCertificate extends ContentEntityBase implements LMSUserCertificateInterface {

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
      ->setDescription(t('The lms_user_ceritificate type.'))
      ->setSetting('target_type', 'lms_user_ceritificate_type')
      ->setReadOnly(TRUE);

    $fields['uid']
      ->setLabel(t('Owner'))
      ->setDescription(t('The lms_user_ceritificate owner.'))
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayConfigurable('form', TRUE);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The lms_user_ceritificate name.'))
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
      ->setDescription(t('The time when the lms_user_ceritificate was created.'))
      ->setTranslatable(TRUE)
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time when the lms_user_ceritificate was last edited.'))
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
