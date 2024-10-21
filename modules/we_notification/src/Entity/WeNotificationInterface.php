<?php

namespace Drupal\we_notification\Entity;

use Drupal\address\AddressInterface;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\user\EntityOwnerInterface;
use Drupal\Core\Entity\EntityChangedInterface;

/**
 * Defines the interface for notification items.
 */
interface WeNotificationInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {

  /**
   * Gets the we_notification name.
   *
   * @return string
   *   The we_notification name.
   */
  public function getName();

  /**
   * Sets the we_notification name.
   *
   * @param string $name
   *   The we_notification name.
   *
   * @return $this
   */
  public function setName($name);

  /**
   * Gets the we_notification creation timestamp.
   *
   * @return int
   *   The we_notification creation timestamp.
   */
  public function getCreatedTime();

  /**
   * Sets the we_notification creation timestamp.
   *
   * @param int $timestamp
   *   The we_notification creation timestamp.
   *
   * @return $this
   */
  public function setCreatedTime($timestamp);

}
