<?php

namespace Drupal\lms_scheduler;

use Drupal\lms_scheduler\Entity\LMSSchedulerType;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;

/**
 * Defines the list builder for lms scheduler items.
 */
class LMSSchedulerListBuilder extends EntityListBuilder {

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
    /** @var \Drupal\lms_scheduler\Entity\LMSSchedulerInterface $entity */
    $lms_scheduler_type = LMSSchedulerType::load($entity->bundle());

    $row['name']['data'] = [
      '#type' => 'link',
      '#title' => $entity->label(),
    ] + $entity->toUrl()->toRenderArray();
    $row['type'] = $lms_scheduler_type->label();

    return $row + parent::buildRow($entity);
  }

}
