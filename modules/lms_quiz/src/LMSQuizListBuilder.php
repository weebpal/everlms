<?php

namespace Drupal\lms_quiz;

use Drupal\lms_quiz\Entity\LMSQuizType;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;

/**
 * Defines the list builder for quiz items.
 */
class LMSQuizListBuilder extends EntityListBuilder {

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
    /** @var \Drupal\lms_quiz\Entity\LMSQuizInterface $entity */
    $lms_quiz_type = LMSQuizType::load($entity->bundle());

    $row['name']['data'] = [
      '#type' => 'link',
      '#title' => $entity->label(),
    ] + $entity->toUrl()->toRenderArray();
    $row['type'] = $lms_quiz_type->label();

    return $row + parent::buildRow($entity);
  }

}
