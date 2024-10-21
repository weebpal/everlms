<?php

namespace Drupal\lms_class\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the lms_class type entity class.
 *
 * @ConfigEntityType(
 *   id = "lms_class_type",
 *   label = @Translation("Class type", context = "LMS"),
 *   label_collection = @Translation("Class types", context = "LMS"),
 *   label_singular = @Translation("class type", context = "LMS"),
 *   label_plural = @Translation("class types", context = "LMS"),
 *   label_count = @PluralTranslation(
 *     singular = "@count class type",
 *     plural = "@count class types",
 *     context = "LMS",
 *   ),
 *   handlers = {
 *     "access" = "Drupal\entity\BundleEntityAccessControlHandler",
 *     "list_builder" = "Drupal\lms_class\LMSClassTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\lms_class\Form\LMSClassTypeForm",
 *       "edit" = "Drupal\lms_class\Form\LMSClassTypeForm",
 *       "duplicate" = "Drupal\lms_class\Form\LMSClassTypeForm",
 *       "delete" = "Drupal\Core\Entity\EntityDeleteForm"
 *     },
 *     "local_task_provider" = {
 *       "default" = "Drupal\entity\Menu\DefaultEntityLocalTaskProvider",
 *     },
 *     "route_provider" = {
 *       "default" = "Drupal\entity\Routing\DefaultHtmlRouteProvider",
 *     },
 *   },
 *   admin_permission = "administer lms_class_type",
 *   config_prefix = "lms_class_type",
 *   bundle_of = "lms_class",
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
 *     "add-form" = "/admin/content/lms-class-types/add",
 *     "edit-form" = "/admin/content/lms-class-types/{lms_class_type}/edit",
 *     "duplicate-form" = "/admin/content/lms-class-types/{lms_class_type}/duplicate",
 *     "delete-form" = "/admin/content/lms-class-types/{lms_class_type}/delete",
 *     "collection" = "/admin/content/lms-class-types",
 *   }
 * )
 */
class LMSClassType extends ConfigEntityBundleBase implements LMSClassTypeInterface {

  /**
   * A brief description of this lms_class type.
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
