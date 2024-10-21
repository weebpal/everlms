<?php

namespace Drupal\lms_certificate\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the lms_certificate type entity class.
 *
 * @ConfigEntityType(
 *   id = "lms_certificate_type",
 *   label = @Translation("Certificate type", context = "Custom Entity Modules"),
 *   label_collection = @Translation("Certificate types", context = "Custom Entity Modules"),
 *   label_singular = @Translation("certificate type", context = "Custom Entity Modules"),
 *   label_plural = @Translation("certificate types", context = "Custom Entity Modules"),
 *   label_count = @PluralTranslation(
 *     singular = "@count certificate type",
 *     plural = "@count certificate types",
 *     context = "Custom Entity Modules",
 *   ),
 *   handlers = {
 *     "access" = "Drupal\entity\BundleEntityAccessControlHandler",
 *     "list_builder" = "Drupal\lms_certificate\LMSCertificateTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\lms_certificate\Form\LMSCertificateTypeForm",
 *       "edit" = "Drupal\lms_certificate\Form\LMSCertificateTypeForm",
 *       "duplicate" = "Drupal\lms_certificate\Form\LMSCertificateTypeForm",
 *       "delete" = "Drupal\Core\Entity\EntityDeleteForm"
 *     },
 *     "local_task_provider" = {
 *       "default" = "Drupal\entity\Menu\DefaultEntityLocalTaskProvider",
 *     },
 *     "route_provider" = {
 *       "default" = "Drupal\entity\Routing\DefaultHtmlRouteProvider",
 *     },
 *   },
 *   admin_permission = "administer lms_certificate_type",
 *   config_prefix = "lms_certificate_type",
 *   bundle_of = "lms_certificate",
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
 *     "add-form" = "/admin/content/lms-certificate-types/add",
 *     "edit-form" = "/admin/content/lms-certificate-types/{lms_certificate_type}/edit",
 *     "duplicate-form" = "/admin/content/lms-certificate-types/{lms_certificate_type}/duplicate",
 *     "delete-form" = "/admin/content/lms-certificate-types/{lms_certificate_type}/delete",
 *     "collection" = "/admin/content/lms-certificate-types",
 *   }
 * )
 */
class LMSCertificateType extends ConfigEntityBundleBase implements LMSCertificateTypeInterface {

  /**
   * A brief description of this lms_certificate type.
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
