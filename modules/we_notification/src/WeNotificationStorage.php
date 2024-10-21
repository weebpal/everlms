<?php

namespace Drupal\we_notification;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\we_notification\Entity\WeNotificationInterface;

/**
 * Defines the we_notification storage.
 */
class WeNotificationStorage extends SqlContentEntityStorage implements WeNotificationStorageInterface {
}
