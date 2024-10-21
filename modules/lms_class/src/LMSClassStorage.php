<?php

namespace Drupal\lms_class;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\lms_class\Entity\LMSClassInterface;

/**
 * Defines the lms_class storage.
 */
class LMSClassStorage extends SqlContentEntityStorage implements LMSClassStorageInterface {
}
