<?php

/**
 * @file
 */

/**
 * Implements hook_views_data().
 */
function lms_lesson_views_data() {
  $data = [];

  $lms_lesson = \Drupal::entityTypeManager()->getDefinition('lms_lesson');

  $data[$lms_lesson->getBaseTable()][$lms_lesson->id() . '_lesson_detail_link'] = [
    'title' => t('Lesson Detail Link'),
    'help' => t('Provide link to lesson'),
    'field' => [
      'id' => 'lms_lesson_detail_link',
    ],
  ];

  return $data;
}
