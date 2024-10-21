<?php

namespace Drupal\lms_quiz_result;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\lms_quiz_result\Entity\LMSQuizResultInterface;

/**
 * Defines the lms_quiz_result storage.
 */
class LMSQuizResultStorage extends SqlContentEntityStorage implements LMSQuizResultStorageInterface {
}
