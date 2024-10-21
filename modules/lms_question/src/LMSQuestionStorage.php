<?php

namespace Drupal\lms_question;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\lms_question\Entity\LMSQuestionInterface;

/**
 * Defines the lms_question storage.
 */
class LMSQuestionStorage extends SqlContentEntityStorage implements LMSQuestionStorageInterface {
}
