<?php

namespace Drupal\lms_lesson;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\lms_lesson\Entity\LMSLessonInterface;

/**
 * Defines the lms_lesson storage.
 */
class LMSLessonStorage extends SqlContentEntityStorage implements LMSLessonStorageInterface {
}
