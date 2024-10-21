<?php

namespace Drupal\lms_scheduler\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the lms_scheduler type entity class.
 *
 * @ConfigEntityType(
 *   id = "lms_scheduler_type",
 *   label = @Translation("LMS Scheduler type", context = "LMS"),
 *   label_collection = @Translation("LMS Scheduler types", context = "LMS"),
 *   label_singular = @Translation("lms scheduler type", context = "LMS"),
 *   label_plural = @Translation("lms scheduler types", context = "LMS"),
 *   label_count = @PluralTranslation(
 *     singular = "@count lms scheduler type",
 *     plural = "@count lms scheduler types",
 *     context = "LMS",
 *   ),
 *   handlers = {
 *     "access" = "Drupal\entity\BundleEntityAccessControlHandler",
 *     "list_builder" = "Drupal\lms_scheduler\LMSSchedulerTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\lms_scheduler\Form\LMSSchedulerTypeForm",
 *       "edit" = "Drupal\lms_scheduler\Form\LMSSchedulerTypeForm",
 *       "duplicate" = "Drupal\lms_scheduler\Form\LMSSchedulerTypeForm",
 *       "delete" = "Drupal\Core\Entity\EntityDeleteForm"
 *     },
 *     "local_task_provider" = {
 *       "default" = "Drupal\entity\Menu\DefaultEntityLocalTaskProvider",
 *     },
 *     "route_provider" = {
 *       "default" = "Drupal\entity\Routing\DefaultHtmlRouteProvider",
 *     },
 *   },
 *   admin_permission = "administer lms_scheduler_type",
 *   config_prefix = "lms_scheduler_type",
 *   bundle_of = "lms_scheduler",
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
 *     "add-form" = "/admin/content/lms-scheduler-types/add",
 *     "edit-form" = "/admin/content/lms-scheduler-types/{lms_scheduler_type}/edit",
 *     "duplicate-form" = "/admin/content/lms-scheduler-types/{lms_scheduler_type}/duplicate",
 *     "delete-form" = "/admin/content/lms-scheduler-types/{lms_scheduler_type}/delete",
 *     "collection" = "/admin/content/lms-scheduler-types",
 *   }
 * )
 */
class LMSSchedulerType extends ConfigEntityBundleBase implements LMSSchedulerTypeInterface {

  /**
   * A brief description of this lms_scheduler type.
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
