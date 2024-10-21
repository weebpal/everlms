<?php

namespace Drupal\lms_quiz\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the lms_quiz type entity class.
 *
 * @ConfigEntityType(
 *   id = "lms_quiz_type",
 *   label = @Translation("Quiz type", context = "Custom Entity Modules"),
 *   label_collection = @Translation("Quiz types", context = "Custom Entity Modules"),
 *   label_singular = @Translation("quiz type", context = "Custom Entity Modules"),
 *   label_plural = @Translation("quiz types", context = "Custom Entity Modules"),
 *   label_count = @PluralTranslation(
 *     singular = "@count quiz type",
 *     plural = "@count quiz types",
 *     context = "Custom Entity Modules",
 *   ),
 *   handlers = {
 *     "access" = "Drupal\entity\BundleEntityAccessControlHandler",
 *     "list_builder" = "Drupal\lms_quiz\LMSQuizTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\lms_quiz\Form\LMSQuizTypeForm",
 *       "edit" = "Drupal\lms_quiz\Form\LMSQuizTypeForm",
 *       "duplicate" = "Drupal\lms_quiz\Form\LMSQuizTypeForm",
 *       "delete" = "Drupal\Core\Entity\EntityDeleteForm"
 *     },
 *     "local_task_provider" = {
 *       "default" = "Drupal\entity\Menu\DefaultEntityLocalTaskProvider",
 *     },
 *     "route_provider" = {
 *       "default" = "Drupal\entity\Routing\DefaultHtmlRouteProvider",
 *     },
 *   },
 *   admin_permission = "administer lms_quiz_type",
 *   config_prefix = "lms_quiz_type",
 *   bundle_of = "lms_quiz",
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
 *     "add-form" = "/admin/content/lms-quiz-types/add",
 *     "edit-form" = "/admin/content/lms-quiz-types/{lms_quiz_type}/edit",
 *     "duplicate-form" = "/admin/content/lms-quiz-types/{lms_quiz_type}/duplicate",
 *     "delete-form" = "/admin/content/lms-quiz-types/{lms_quiz_type}/delete",
 *     "collection" = "/admin/content/lms-quiz-types",
 *   }
 * )
 */
class LMSQuizType extends ConfigEntityBundleBase implements LMSQuizTypeInterface {

  /**
   * A brief description of this lms_quiz type.
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
