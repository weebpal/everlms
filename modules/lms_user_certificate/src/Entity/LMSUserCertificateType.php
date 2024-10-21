<?php

namespace Drupal\lms_user_certificate\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the lms_user_certificate type entity class.
 *
 * @ConfigEntityType(
 *   id = "lms_user_certificate_type",
 *   label = @Translation("User Certificate type", context = "Custom Entity Modules"),
 *   label_collection = @Translation("User Certificate types", context = "Custom Entity Modules"),
 *   label_singular = @Translation("user certificate type", context = "Custom Entity Modules"),
 *   label_plural = @Translation("user certificate types", context = "Custom Entity Modules"),
 *   label_count = @PluralTranslation(
 *     singular = "@count user certificate type",
 *     plural = "@count user certificate types",
 *     context = "Custom Entity Modules",
 *   ),
 *   handlers = {
 *     "access" = "Drupal\entity\BundleEntityAccessControlHandler",
 *     "list_builder" = "Drupal\lms_user_certificate\LMSUserCertificateTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\lms_user_certificate\Form\LMSUserCertificateTypeForm",
 *       "edit" = "Drupal\lms_user_certificate\Form\LMSUserCertificateTypeForm",
 *       "duplicate" = "Drupal\lms_user_certificate\Form\LMSUserCertificateTypeForm",
 *       "delete" = "Drupal\Core\Entity\EntityDeleteForm"
 *     },
 *     "local_task_provider" = {
 *       "default" = "Drupal\entity\Menu\DefaultEntityLocalTaskProvider",
 *     },
 *     "route_provider" = {
 *       "default" = "Drupal\entity\Routing\DefaultHtmlRouteProvider",
 *     },
 *   },
 *   admin_permission = "administer lms_user_certificate_type",
 *   config_prefix = "lms_user_certificate_type",
 *   bundle_of = "lms_user_certificate",
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
 *     "add-form" = "/admin/content/lms-user-certificate-types/add",
 *     "edit-form" = "/admin/content/lms-user-certificate-types/{lms_user_certificate_type}/edit",
 *     "duplicate-form" = "/admin/content/lms-user-certificate-types/{lms_user_certificate_type}/duplicate",
 *     "delete-form" = "/admin/content/lms-user-certificate-types/{lms_user_certificate_type}/delete",
 *     "collection" = "/admin/content/lms-user-certificate-types",
 *   }
 * )
 */
class LMSUserCertificateType extends ConfigEntityBundleBase implements LMSUserCertificateTypeInterface {

  /**
   * A brief description of this lms_user_certificate type.
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
