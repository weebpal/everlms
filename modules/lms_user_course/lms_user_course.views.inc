<?php

/**
 * @file
 */

/**
 * Implements hook_views_data().
 */
function lms_user_course_views_data() {
  $data = [];

  $lms_user_course = \Drupal::entityTypeManager()->getDefinition('lms_user_course');

  $data[$lms_user_course->getBaseTable()][$lms_user_course->id() . '_result_link'] = [
    'title' => t('User Course Result Link'),
    'help' => t('Provide link to user course'),
    'field' => [
      'id' => 'lms_user_course_result_link',
    ],
  ];

  return $data;
}
