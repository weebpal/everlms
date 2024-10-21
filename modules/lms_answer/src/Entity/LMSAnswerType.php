<?php

namespace Drupal\lms_answer\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the lms_answer type entity class.
 *
 * @ConfigEntityType(
 *   id = "lms_answer_type",
 *   label = @Translation("Answer type", context = "Custom Entity Modules"),
 *   label_collection = @Translation("Answer types", context = "Custom Entity Modules"),
 *   label_singular = @Translation("answer type", context = "Custom Entity Modules"),
 *   label_plural = @Translation("answer types", context = "Custom Entity Modules"),
 *   label_count = @PluralTranslation(
 *     singular = "@count answer type",
 *     plural = "@count answer types",
 *     context = "Custom Entity Modules",
 *   ),
 *   handlers = {
 *     "access" = "Drupal\entity\BundleEntityAccessControlHandler",
 *     "list_builder" = "Drupal\lms_answer\LMSAnswerTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\lms_answer\Form\LMSAnswerTypeForm",
 *       "edit" = "Drupal\lms_answer\Form\LMSAnswerTypeForm",
 *       "duplicate" = "Drupal\lms_answer\Form\LMSAnswerTypeForm",
 *       "delete" = "Drupal\Core\Entity\EntityDeleteForm"
 *     },
 *     "local_task_provider" = {
 *       "default" = "Drupal\entity\Menu\DefaultEntityLocalTaskProvider",
 *     },
 *     "route_provider" = {
 *       "default" = "Drupal\entity\Routing\DefaultHtmlRouteProvider",
 *     },
 *   },
 *   admin_permission = "administer lms_answer_type",
 *   config_prefix = "lms_answer_type",
 *   bundle_of = "lms_answer",
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
 *     "add-form" = "/admin/content/lms-answer-types/add",
 *     "edit-form" = "/admin/content/lms-answer-types/{lms_answer_type}/edit",
 *     "duplicate-form" = "/admin/content/lms-answer-types/{lms_answer_type}/duplicate",
 *     "delete-form" = "/admin/content/lms-answer-types/{lms_answer_type}/delete",
 *     "collection" = "/admin/content/lms-answer-types",
 *   }
 * )
 */
class LMSAnswerType extends ConfigEntityBundleBase implements LMSAnswerTypeInterface {

  /**
   * A brief description of this lms_answer type.
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
