<?php

namespace Drupal\lms_question_response\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the lms_question_response type entity class.
 *
 * @ConfigEntityType(
 *   id = "lms_question_response_type",
 *   label = @Translation("Question Response type", context = "Custom Entity Modules"),
 *   label_collection = @Translation("Question Response types", context = "Custom Entity Modules"),
 *   label_singular = @Translation("question response type", context = "Custom Entity Modules"),
 *   label_plural = @Translation("question response types", context = "Custom Entity Modules"),
 *   label_count = @PluralTranslation(
 *     singular = "@count question response type",
 *     plural = "@count question response types",
 *     context = "Custom Entity Modules",
 *   ),
 *   handlers = {
 *     "access" = "Drupal\entity\BundleEntityAccessControlHandler",
 *     "list_builder" = "Drupal\lms_question_response\LMSQuestionResponseTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\lms_question_response\Form\LMSQuestionResponseTypeForm",
 *       "edit" = "Drupal\lms_question_response\Form\LMSQuestionResponseTypeForm",
 *       "duplicate" = "Drupal\lms_question_response\Form\LMSQuestionResponseTypeForm",
 *       "delete" = "Drupal\Core\Entity\EntityDeleteForm"
 *     },
 *     "local_task_provider" = {
 *       "default" = "Drupal\entity\Menu\DefaultEntityLocalTaskProvider",
 *     },
 *     "route_provider" = {
 *       "default" = "Drupal\entity\Routing\DefaultHtmlRouteProvider",
 *     },
 *   },
 *   admin_permission = "administer lms_question_response_type",
 *   config_prefix = "lms_question_response_type",
 *   bundle_of = "lms_question_response",
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
 *     "add-form" = "/admin/content/lms-question-response-types/add",
 *     "edit-form" = "/admin/content/lms-question-response-types/{lms_question_response_type}/edit",
 *     "duplicate-form" = "/admin/content/lms-question-response-types/{lms_question_response_type}/duplicate",
 *     "delete-form" = "/admin/content/lms-question-response-types/{lms_question_response_type}/delete",
 *     "collection" = "/admin/content/lms-question-response-types",
 *   }
 * )
 */
class LMSQuestionResponseType extends ConfigEntityBundleBase implements LMSQuestionResponseTypeInterface {

  /**
   * A brief description of this lms_question_response type.
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
