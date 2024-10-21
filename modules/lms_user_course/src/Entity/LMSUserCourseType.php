<?php

namespace Drupal\lms_user_course\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the lms_user_course type entity class.
 *
 * @ConfigEntityType(
 *   id = "lms_user_course_type",
 *   label = @Translation("User Course type", context = "Custom Entity Modules"),
 *   label_collection = @Translation("User Course types", context = "Custom Entity Modules"),
 *   label_singular = @Translation("user course type", context = "Custom Entity Modules"),
 *   label_plural = @Translation("user course types", context = "Custom Entity Modules"),
 *   label_count = @PluralTranslation(
 *     singular = "@count user course type",
 *     plural = "@count user course types",
 *     context = "Custom Entity Modules",
 *   ),
 *   handlers = {
 *     "access" = "Drupal\entity\BundleEntityAccessControlHandler",
 *     "list_builder" = "Drupal\lms_user_course\LMSUserCourseTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\lms_user_course\Form\LMSUserCourseTypeForm",
 *       "edit" = "Drupal\lms_user_course\Form\LMSUserCourseTypeForm",
 *       "duplicate" = "Drupal\lms_user_course\Form\LMSUserCourseTypeForm",
 *       "delete" = "Drupal\Core\Entity\EntityDeleteForm"
 *     },
 *     "local_task_provider" = {
 *       "default" = "Drupal\entity\Menu\DefaultEntityLocalTaskProvider",
 *     },
 *     "route_provider" = {
 *       "default" = "Drupal\entity\Routing\DefaultHtmlRouteProvider",
 *     },
 *   },
 *   admin_permission = "administer lms_user_course_type",
 *   config_prefix = "lms_user_course_type",
 *   bundle_of = "lms_user_course",
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
 *     "add-form" = "/admin/content/lms-user-course-types/add",
 *     "edit-form" = "/admin/content/lms-user-course-types/{lms_user_course_type}/edit",
 *     "duplicate-form" = "/admin/content/lms-user-course-types/{lms_user_course_type}/duplicate",
 *     "delete-form" = "/admin/content/lms-user-course-types/{lms_user_course_type}/delete",
 *     "collection" = "/admin/content/lms-user-course-types",
 *   }
 * )
 */
class LMSUserCourseType extends ConfigEntityBundleBase implements LMSUserCourseTypeInterface {

  /**
   * A brief description of this lms_user_course type.
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
