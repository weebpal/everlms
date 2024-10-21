<?php

namespace Drupal\lms_quiz_result;

/**
 * Provides a user course service.
 */
interface LMSQuizResultManagerInterface {

  /**
   * get list quiz result by uid
   */
  public function getQuizResultByUid(string $uid,  string $type = 'id');
}
