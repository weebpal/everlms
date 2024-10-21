<?php

namespace Drupal\lms_user_certificate;

use Drupal\lms_user_certificate\Entity\LMSUserCertificateType;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;

/**
 * Defines the list builder for user certificate items.
 */
class LMSUserCertificateListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['name'] = $this->t('Name');
    $header['type'] = $this->t('Type');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /** @var \Drupal\lms_user_certificate\Entity\LMSUserCertificateInterface $entity */
    $lms_user_certificate_type = LMSUserCertificateType::load($entity->bundle());

    $row['name']['data'] = [
      '#type' => 'link',
      '#title' => $entity->label(),
    ] + $entity->toUrl()->toRenderArray();
    $row['type'] = $lms_user_certificate_type->label();

    return $row + parent::buildRow($entity);
  }

}
