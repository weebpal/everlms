<?php

namespace Drupal\lms_user_ceritificate;

use Drupal\lms_user_ceritificate\Entity\LMSUserCertificateType;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;

/**
 * Defines the list builder for user ceritificate items.
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
    /** @var \Drupal\lms_user_ceritificate\Entity\LMSUserCertificateInterface $entity */
    $lms_user_ceritificate_type = LMSUserCertificateType::load($entity->bundle());

    $row['name']['data'] = [
      '#type' => 'link',
      '#title' => $entity->label(),
    ] + $entity->toUrl()->toRenderArray();
    $row['type'] = $lms_user_ceritificate_type->label();

    return $row + parent::buildRow($entity);
  }

}
