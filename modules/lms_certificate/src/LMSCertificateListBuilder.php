<?php

namespace Drupal\lms_certificate;

use Drupal\lms_certificate\Entity\LMSCertificateType;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;

/**
 * Defines the list builder for certificate items.
 */
class LMSCertificateListBuilder extends EntityListBuilder {

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
    /** @var \Drupal\lms_certificate\Entity\LMSCertificateInterface $entity */
    $lms_certificate_type = LMSCertificateType::load($entity->bundle());

    $row['name']['data'] = [
      '#type' => 'link',
      '#title' => $entity->label(),
    ] + $entity->toUrl()->toRenderArray();
    $row['type'] = $lms_certificate_type->label();

    return $row + parent::buildRow($entity);
  }

}
