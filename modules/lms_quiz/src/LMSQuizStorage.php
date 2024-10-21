<?php

namespace Drupal\lms_quiz;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\lms_quiz\Entity\LMSQuizInterface;

/**
 * Defines the lms_quiz storage.
 */
class LMSQuizStorage extends SqlContentEntityStorage implements LMSQuizStorageInterface {
}
