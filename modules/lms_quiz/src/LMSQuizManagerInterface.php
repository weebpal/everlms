<?php

namespace Drupal\lms_quiz;

/**
 * LMSQuizManagerInterface.
 */
interface LMSQuizManagerInterface {

  /**
   * @param string $quiz_id
   * @param string $uid
   *
   * @return mixed
   */
  public function getQuizResultExist(string $quiz_id, string $uid);

  /**
   * @return mixed
   */
  public function submitQuizExpired();

  /**
   *  @return mixed
   */
  public function deleteQuizStateAnonymous();
}
