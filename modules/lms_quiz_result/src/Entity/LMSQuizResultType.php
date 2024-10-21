<?php

namespace Drupal\lms_quiz_result\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the lms_quiz_result type entity class.
 *
 * @ConfigEntityType(
 *   id = "lms_quiz_result_type",
 *   label = @Translation("Quiz Result type", context = "Custom Entity Modules"),
 *   label_collection = @Translation("Quiz Result types", context = "Custom Entity Modules"),
 *   label_singular = @Translation("quiz result type", context = "Custom Entity Modules"),
 *   label_plural = @Translation("quiz result types", context = "Custom Entity Modules"),
 *   label_count = @PluralTranslation(
 *     singular = "@count quiz result type",
 *     plural = "@count quiz result types",
 *     context = "Custom Entity Modules",
 *   ),
 *   handlers = {
 *     "access" = "Drupal\entity\BundleEntityAccessControlHandler",
 *     "list_builder" = "Drupal\lms_quiz_result\LMSQuizResultTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\lms_quiz_result\Form\LMSQuizResultTypeForm",
 *       "edit" = "Drupal\lms_quiz_result\Form\LMSQuizResultTypeForm",
 *       "duplicate" = "Drupal\lms_quiz_result\Form\LMSQuizResultTypeForm",
 *       "delete" = "Drupal\Core\Entity\EntityDeleteForm"
 *     },
 *     "local_task_provider" = {
 *       "default" = "Drupal\entity\Menu\DefaultEntityLocalTaskProvider",
 *     },
 *     "route_provider" = {
 *       "default" = "Drupal\entity\Routing\DefaultHtmlRouteProvider",
 *     },
 *   },
 *   admin_permission = "administer lms_quiz_result_type",
 *   config_prefix = "lms_quiz_result_type",
 *   bundle_of = "lms_quiz_result",
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
 *     "add-form" = "/admin/content/lms-quiz-result-types/add",
 *     "edit-form" = "/admin/content/lms-quiz-result-types/{lms_quiz_result_type}/edit",
 *     "duplicate-form" = "/admin/content/lms-quiz-result-types/{lms_quiz_result_type}/duplicate",
 *     "delete-form" = "/admin/content/lms-quiz-result-types/{lms_quiz_result_type}/delete",
 *     "collection" = "/admin/content/lms-quiz-result-types",
 *   }
 * )
 */
class LMSQuizResultType extends ConfigEntityBundleBase implements LMSQuizResultTypeInterface {

  /**
   * A brief description of this lms_quiz_result type.
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
