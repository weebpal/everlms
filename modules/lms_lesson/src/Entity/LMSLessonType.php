<?php

namespace Drupal\lms_lesson\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the lms_lesson type entity class.
 *
 * @ConfigEntityType(
 *   id = "lms_lesson_type",
 *   label = @Translation("Lesson type", context = "Custom Entity Modules"),
 *   label_collection = @Translation("Lesson types", context = "Custom Entity Modules"),
 *   label_singular = @Translation("lesson type", context = "Custom Entity Modules"),
 *   label_plural = @Translation("lesson types", context = "Custom Entity Modules"),
 *   label_count = @PluralTranslation(
 *     singular = "@count lesson type",
 *     plural = "@count lesson types",
 *     context = "Custom Entity Modules",
 *   ),
 *   handlers = {
 *     "access" = "Drupal\entity\BundleEntityAccessControlHandler",
 *     "list_builder" = "Drupal\lms_lesson\LMSLessonTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\lms_lesson\Form\LMSLessonTypeForm",
 *       "edit" = "Drupal\lms_lesson\Form\LMSLessonTypeForm",
 *       "duplicate" = "Drupal\lms_lesson\Form\LMSLessonTypeForm",
 *       "delete" = "Drupal\Core\Entity\EntityDeleteForm"
 *     },
 *     "local_task_provider" = {
 *       "default" = "Drupal\entity\Menu\DefaultEntityLocalTaskProvider",
 *     },
 *     "route_provider" = {
 *       "default" = "Drupal\entity\Routing\DefaultHtmlRouteProvider",
 *     },
 *   },
 *   admin_permission = "administer lms_lesson_type",
 *   config_prefix = "lms_lesson_type",
 *   bundle_of = "lms_lesson",
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
 *     "add-form" = "/admin/content/lms-lesson-types/add",
 *     "edit-form" = "/admin/content/lms-lesson-types/{lms_lesson_type}/edit",
 *     "duplicate-form" = "/admin/content/lms-lesson-types/{lms_lesson_type}/duplicate",
 *     "delete-form" = "/admin/content/lms-lesson-types/{lms_lesson_type}/delete",
 *     "collection" = "/admin/content/lms-lesson-types",
 *   }
 * )
 */
class LMSLessonType extends ConfigEntityBundleBase implements LMSLessonTypeInterface {

  /**
   * A brief description of this lms_lesson type.
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
