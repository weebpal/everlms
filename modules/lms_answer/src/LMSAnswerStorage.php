<?php

namespace Drupal\lms_answer;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\lms_answer\Entity\LMSAnswerInterface;

/**
 * Defines the lms_answer storage.
 */
class LMSAnswerStorage extends SqlContentEntityStorage implements LMSAnswerStorageInterface {
}
