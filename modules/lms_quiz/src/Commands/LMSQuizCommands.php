<?php

namespace Drupal\lms_quiz\Commands;

use Drupal\lms_quiz\LMSQuizManagerInterface;
use Drush\Commands\DrushCommands;

/**
 * LMSQuizCommands
 */
class LMSQuizCommands extends DrushCommands {

  /**
   * @var \Drupal\lms_quiz\LMSQuizManagerInterface
   */
  protected $LMSQuizManager;

  /**
   * @param \Drupal\lms_quiz\LMSQuizManagerInterface $lms_quiz_manager
   */
  public function __construct(LMSQuizManagerInterface $lms_quiz_manager) {
    $this->LMSQuizManager = $lms_quiz_manager;
  }

  /**
   *
   * @command lms_quiz:submit_quiz_expired
   *
   * @validate-module-enabled lms_quiz
   *
   * @aliases lq-sqe
   *
   */
  public function submitQuizExpired() {
    $this->LMSQuizManager->submitQuizExpired();
  }

  /**
   *
   * @command lms_quiz:delete_quiz_state_anonymous
   *
   * @validate-module-enabled lms_quiz
   *
   * @aliases lq-dqsa
   */
  public function deleteQuizStateAnonymous() {
    $this->LMSQuizManager->deleteQuizStateAnonymous();
  }
}
