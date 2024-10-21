<?php

namespace Drupal\lms_class\Entity;

use Drupal\address\AddressInterface;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\user\EntityOwnerInterface;
use Drupal\Core\Entity\EntityChangedInterface;

/**
 * Defines the interface for class items.
 */
interface LMSClassInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {

  /**
   * Gets the lms_class name.
   *
   * @return string
   *   The lms_class name.
   */
  public function getName();

  /**
   * Sets the lms_class name.
   *
   * @param string $name
   *   The lms_class name.
   *
   * @return $this
   */
  public function setName($name);

  /**
   * Gets the lms_class creation timestamp.
   *
   * @return int
   *   The lms_class creation timestamp.
   */
  public function getCreatedTime();

  /**
   * Sets the lms_class creation timestamp.
   *
   * @param int $timestamp
   *   The lms_class creation timestamp.
   *
   * @return $this
   */
  public function setCreatedTime($timestamp);

}
