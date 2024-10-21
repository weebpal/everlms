<?php

namespace Drupal\lms_scheduler;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\lms_scheduler\Entity\LMSSchedulerInterface;

/**
 * Defines the lms_scheduler storage.
 */
class LMSSchedulerStorage extends SqlContentEntityStorage implements LMSSchedulerStorageInterface {
}
