<?php

namespace Drupal\lms_room\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the lms_room type entity class.
 *
 * @ConfigEntityType(
 *   id = "lms_room_type",
 *   label = @Translation("Room type", context = "Custom Entity Modules"),
 *   label_collection = @Translation("Room types", context = "Custom Entity Modules"),
 *   label_singular = @Translation("room type", context = "Custom Entity Modules"),
 *   label_plural = @Translation("room types", context = "Custom Entity Modules"),
 *   label_count = @PluralTranslation(
 *     singular = "@count room type",
 *     plural = "@count room types",
 *     context = "Custom Entity Modules",
 *   ),
 *   handlers = {
 *     "access" = "Drupal\entity\BundleEntityAccessControlHandler",
 *     "list_builder" = "Drupal\lms_room\LMSRoomTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\lms_room\Form\LMSRoomTypeForm",
 *       "edit" = "Drupal\lms_room\Form\LMSRoomTypeForm",
 *       "duplicate" = "Drupal\lms_room\Form\LMSRoomTypeForm",
 *       "delete" = "Drupal\Core\Entity\EntityDeleteForm"
 *     },
 *     "local_task_provider" = {
 *       "default" = "Drupal\entity\Menu\DefaultEntityLocalTaskProvider",
 *     },
 *     "route_provider" = {
 *       "default" = "Drupal\entity\Routing\DefaultHtmlRouteProvider",
 *     },
 *   },
 *   admin_permission = "administer lms_room_type",
 *   config_prefix = "lms_room_type",
 *   bundle_of = "lms_room",
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
 *     "add-form" = "/admin/content/lms-room-types/add",
 *     "edit-form" = "/admin/content/lms-room-types/{lms_room_type}/edit",
 *     "duplicate-form" = "/admin/content/lms-room-types/{lms_room_type}/duplicate",
 *     "delete-form" = "/admin/content/lms-room-types/{lms_room_type}/delete",
 *     "collection" = "/admin/content/lms-room-types",
 *   }
 * )
 */
class LMSRoomType extends ConfigEntityBundleBase implements LMSRoomTypeInterface {

  /**
   * A brief description of this lms_room type.
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
