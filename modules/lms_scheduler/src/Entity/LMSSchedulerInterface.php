<?php

namespace Drupal\lms_scheduler\Entity;

use Drupal\address\AddressInterface;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\user\EntityOwnerInterface;
use Drupal\Core\Entity\EntityChangedInterface;

/**
 * Defines the interface for lms scheduler items.
 */
interface LMSSchedulerInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {

  /**
   * Gets the lms_scheduler name.
   *
   * @return string
   *   The lms_scheduler name.
   */
  public function getName();

  /**
   * Sets the lms_scheduler name.
   *
   * @param string $name
   *   The lms_scheduler name.
   *
   * @return $this
   */
  public function setName($name);

  /**
   * Gets the lms_scheduler creation timestamp.
   *
   * @return int
   *   The lms_scheduler creation timestamp.
   */
  public function getCreatedTime();

  /**
   * Sets the lms_scheduler creation timestamp.
   *
   * @param int $timestamp
   *   The lms_scheduler creation timestamp.
   *
   * @return $this
   */
  public function setCreatedTime($timestamp);

}
