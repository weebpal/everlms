<?php

namespace Drupal\lms_room\Entity;

use Drupal\address\AddressInterface;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\user\EntityOwnerInterface;
use Drupal\Core\Entity\EntityChangedInterface;

/**
 * Defines the interface for room items.
 */
interface LMSRoomInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {

  /**
   * Gets the lms_room name.
   *
   * @return string
   *   The lms_room name.
   */
  public function getName();

  /**
   * Sets the lms_room name.
   *
   * @param string $name
   *   The lms_room name.
   *
   * @return $this
   */
  public function setName($name);

  /**
   * Gets the lms_room creation timestamp.
   *
   * @return int
   *   The lms_room creation timestamp.
   */
  public function getCreatedTime();

  /**
   * Sets the lms_room creation timestamp.
   *
   * @param int $timestamp
   *   The lms_room creation timestamp.
   *
   * @return $this
   */
  public function setCreatedTime($timestamp);

}
