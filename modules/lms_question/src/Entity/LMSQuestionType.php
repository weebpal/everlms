<?php

namespace Drupal\lms_question\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the lms_question type entity class.
 *
 * @ConfigEntityType(
 *   id = "lms_question_type",
 *   label = @Translation("Question type", context = "Custom Entity Modules"),
 *   label_collection = @Translation("Question types", context = "Custom Entity Modules"),
 *   label_singular = @Translation("question type", context = "Custom Entity Modules"),
 *   label_plural = @Translation("question types", context = "Custom Entity Modules"),
 *   label_count = @PluralTranslation(
 *     singular = "@count question type",
 *     plural = "@count question types",
 *     context = "Custom Entity Modules",
 *   ),
 *   handlers = {
 *     "access" = "Drupal\entity\BundleEntityAccessControlHandler",
 *     "list_builder" = "Drupal\lms_question\LMSQuestionTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\lms_question\Form\LMSQuestionTypeForm",
 *       "edit" = "Drupal\lms_question\Form\LMSQuestionTypeForm",
 *       "duplicate" = "Drupal\lms_question\Form\LMSQuestionTypeForm",
 *       "delete" = "Drupal\Core\Entity\EntityDeleteForm"
 *     },
 *     "local_task_provider" = {
 *       "default" = "Drupal\entity\Menu\DefaultEntityLocalTaskProvider",
 *     },
 *     "route_provider" = {
 *       "default" = "Drupal\entity\Routing\DefaultHtmlRouteProvider",
 *     },
 *   },
 *   admin_permission = "administer lms_question_type",
 *   config_prefix = "lms_question_type",
 *   bundle_of = "lms_question",
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
 *     "add-form" = "/admin/content/lms-question-types/add",
 *     "edit-form" = "/admin/content/lms-question-types/{lms_question_type}/edit",
 *     "duplicate-form" = "/admin/content/lms-question-types/{lms_question_type}/duplicate",
 *     "delete-form" = "/admin/content/lms-question-types/{lms_question_type}/delete",
 *     "collection" = "/admin/content/lms-question-types",
 *   }
 * )
 */
class LMSQuestionType extends ConfigEntityBundleBase implements LMSQuestionTypeInterface {

  /**
   * A brief description of this lms_question type.
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
