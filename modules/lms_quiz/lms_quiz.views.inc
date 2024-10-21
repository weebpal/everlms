<?php

/**
 * @file
 */

/**
 * Implements hook_views_data().
 */
function lms_quiz_views_data() {
  $data = [];

  $lms_quiz = \Drupal::entityTypeManager()->getDefinition('lms_quiz');

  $data[$lms_quiz->getBaseTable()][$lms_quiz->id() . '_quiz_detail_link'] = [
    'title' => t('Quiz Detail Link'),
    'help' => t('Provide link to Quiz'),
    'field' => [
      'id' => 'lms_quiz_detail_link',
    ],
  ];

  $data[$lms_quiz->getBaseTable()][$lms_quiz->id() . '_take_quiz_link'] = [
    'title' => t('Take Quiz Link'),
    'help' => t('Provide link to take quiz link'),
    'field' => [
      'id' => 'lms_quiz_take_link',
    ],
  ];

  return $data;
}
