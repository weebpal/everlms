<?php

namespace Drupal\lms_room;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\lms_room\Entity\LMSRoomInterface;

/**
 * Defines the lms_room storage.
 */
class LMSRoomStorage extends SqlContentEntityStorage implements LMSRoomStorageInterface {
}
