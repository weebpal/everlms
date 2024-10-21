<?php

namespace Drupal\we_notification\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the we_notification type entity class.
 *
 * @ConfigEntityType(
 *   id = "we_notification_type",
 *   label = @Translation("Notification type", context = "Custom Entity Modules"),
 *   label_collection = @Translation("Notification types", context = "Custom Entity Modules"),
 *   label_singular = @Translation("notification type", context = "Custom Entity Modules"),
 *   label_plural = @Translation("notification types", context = "Custom Entity Modules"),
 *   label_count = @PluralTranslation(
 *     singular = "@count notification type",
 *     plural = "@count notification types",
 *     context = "Custom Entity Modules",
 *   ),
 *   handlers = {
 *     "access" = "Drupal\entity\BundleEntityAccessControlHandler",
 *     "list_builder" = "Drupal\we_notification\WeNotificationTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\we_notification\Form\WeNotificationTypeForm",
 *       "edit" = "Drupal\we_notification\Form\WeNotificationTypeForm",
 *       "duplicate" = "Drupal\we_notification\Form\WeNotificationTypeForm",
 *       "delete" = "Drupal\Core\Entity\EntityDeleteForm"
 *     },
 *     "local_task_provider" = {
 *       "default" = "Drupal\entity\Menu\DefaultEntityLocalTaskProvider",
 *     },
 *     "route_provider" = {
 *       "default" = "Drupal\entity\Routing\DefaultHtmlRouteProvider",
 *     },
 *   },
 *   admin_permission = "administer we_notification_type",
 *   config_prefix = "we_notification_type",
 *   bundle_of = "we_notification",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid",
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "uuid",
 *     "description",
 *     "traits",
 *     "locked",
 *   },
 *   links = {
 *     "add-form" = "/admin/content/we-notification-types/add",
 *     "edit-form" = "/admin/content/we-notification-types/{we_notification_type}/edit",
 *     "duplicate-form" = "/admin/content/we-notification-types/{we_notification_type}/duplicate",
 *     "delete-form" = "/admin/content/we-notification-types/{we_notification_type}/delete",
 *     "collection" = "/admin/content/we-notification-types",
 *   }
 * )
 */
class WeNotificationType extends ConfigEntityBundleBase implements WeNotificationTypeInterface {

  /**
   * A brief description of this we_notification type.
   *
   * @var string
   */
  protected $description;

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    return $this->description;
  }

  /**
   * {@inheritdoc}
   */
  public function setDescription($description) {
    $this->description = $description;
    return $this;
  }

}
