<?php

namespace Drupal\lms_question_response;

use Drupal\lms_question_response\Entity\LMSQuestionResponseType;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;

/**
 * Defines the list builder for question response items.
 */
class LMSQuestionResponseListBuilder extends EntityListBuilder {

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
    /** @var \Drupal\lms_question_response\Entity\LMSQuestionResponseInterface $entity */
    $lms_question_response_type = LMSQuestionResponseType::load($entity->bundle());

    $row['name']['data'] = [
      '#type' => 'link',
      '#title' => $entity->label(),
    ] + $entity->toUrl()->toRenderArray();
    $row['type'] = $lms_question_response_type->label();

    return $row + parent::buildRow($entity);
  }

}
