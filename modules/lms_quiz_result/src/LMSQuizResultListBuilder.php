<?php

namespace Drupal\lms_quiz_result;

use Drupal\lms_quiz_result\Entity\LMSQuizResultType;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;

/**
 * Defines the list builder for quiz result items.
 */
class LMSQuizResultListBuilder extends EntityListBuilder {

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
    /** @var \Drupal\lms_quiz_result\Entity\LMSQuizResultInterface $entity */
    $lms_quiz_result_type = LMSQuizResultType::load($entity->bundle());

    $row['name']['data'] = [
      '#type' => 'link',
      '#title' => $entity->label(),
    ] + $entity->toUrl()->toRenderArray();
    $row['type'] = $lms_quiz_result_type->label();

    return $row + parent::buildRow($entity);
  }

}
