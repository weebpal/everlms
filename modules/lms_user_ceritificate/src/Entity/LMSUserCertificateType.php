<?php

namespace Drupal\lms_user_ceritificate\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the lms_user_ceritificate type entity class.
 *
 * @ConfigEntityType(
 *   id = "lms_user_ceritificate_type",
 *   label = @Translation("User Ceritificate type", context = "Custom Entity Modules"),
 *   label_collection = @Translation("User Ceritificate types", context = "Custom Entity Modules"),
 *   label_singular = @Translation("user ceritificate type", context = "Custom Entity Modules"),
 *   label_plural = @Translation("user ceritificate types", context = "Custom Entity Modules"),
 *   label_count = @PluralTranslation(
 *     singular = "@count user ceritificate type",
 *     plural = "@count user ceritificate types",
 *     context = "Custom Entity Modules",
 *   ),
 *   handlers = {
 *     "access" = "Drupal\entity\BundleEntityAccessControlHandler",
 *     "list_builder" = "Drupal\lms_user_ceritificate\LMSUserCertificateTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\lms_user_ceritificate\Form\LMSUserCertificateTypeForm",
 *       "edit" = "Drupal\lms_user_ceritificate\Form\LMSUserCertificateTypeForm",
 *       "duplicate" = "Drupal\lms_user_ceritificate\Form\LMSUserCertificateTypeForm",
 *       "delete" = "Drupal\Core\Entity\EntityDeleteForm"
 *     },
 *     "local_task_provider" = {
 *       "default" = "Drupal\entity\Menu\DefaultEntityLocalTaskProvider",
 *     },
 *     "route_provider" = {
 *       "default" = "Drupal\entity\Routing\DefaultHtmlRouteProvider",
 *     },
 *   },
 *   admin_permission = "administer lms_user_ceritificate_type",
 *   config_prefix = "lms_user_ceritificate_type",
 *   bundle_of = "lms_user_ceritificate",
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
 *     "add-form" = "/admin/content/lms-user-ceritificate-types/add",
 *     "edit-form" = "/admin/content/lms-user-ceritificate-types/{lms_user_ceritificate_type}/edit",
 *     "duplicate-form" = "/admin/content/lms-user-ceritificate-types/{lms_user_ceritificate_type}/duplicate",
 *     "delete-form" = "/admin/content/lms-user-ceritificate-types/{lms_user_ceritificate_type}/delete",
 *     "collection" = "/admin/content/lms-user-ceritificate-types",
 *   }
 * )
 */
class LMSUserCertificateType extends ConfigEntityBundleBase implements LMSUserCertificateTypeInterface {

  /**
   * A brief description of this lms_user_ceritificate type.
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
