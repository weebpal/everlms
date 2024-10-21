<?php

namespace Drupal\lms_user_course;

use Drupal\lms_user_course\Entity\LMSUserCourseType;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;

/**
 * Defines the list builder for user course items.
 */
class LMSUserCourseListBuilder extends EntityListBuilder {

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
    /** @var \Drupal\lms_user_course\Entity\LMSUserCourseInterface $entity */
    $lms_user_course_type = LMSUserCourseType::load($entity->bundle());

    $row['name']['data'] = [
      '#type' => 'link',
      '#title' => $entity->label(),
    ] + $entity->toUrl()->toRenderArray();
    $row['type'] = $lms_user_course_type->label();

    return $row + parent::buildRow($entity);
  }

}
