<?php

namespace Drupal\lms_question_response;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\lms_question_response\Entity\LMSQuestionResponseInterface;

/**
 * Defines the lms_question_response storage.
 */
class LMSQuestionResponseStorage extends SqlContentEntityStorage implements LMSQuestionResponseStorageInterface {
}
