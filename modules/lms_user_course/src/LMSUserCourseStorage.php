<?php

namespace Drupal\lms_user_course;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\lms_user_course\Entity\LMSUserCourseInterface;

/**
 * Defines the lms_user_course storage.
 */
class LMSUserCourseStorage extends SqlContentEntityStorage implements LMSUserCourseStorageInterface {
}
