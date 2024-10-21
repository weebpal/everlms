<?php

namespace Drupal\lms_lesson;

use Drupal\lms_lesson\Entity\LMSLessonType;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;

/**
 * Defines the list builder for lesson items.
 */
class LMSLessonListBuilder extends EntityListBuilder {

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
    /** @var \Drupal\lms_lesson\Entity\LMSLessonInterface $entity */
    $lms_lesson_type = LMSLessonType::load($entity->bundle());

    $row['name']['data'] = [
      '#type' => 'link',
      '#title' => $entity->label(),
    ] + $entity->toUrl()->toRenderArray();
    $row['type'] = $lms_lesson_type->label();

    return $row + parent::buildRow($entity);
  }

}
