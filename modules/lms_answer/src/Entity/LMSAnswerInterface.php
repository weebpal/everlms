<?php

namespace Drupal\lms_answer\Entity;

use Drupal\address\AddressInterface;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\user\EntityOwnerInterface;
use Drupal\Core\Entity\EntityChangedInterface;

/**
 * Defines the interface for answer items.
 */
interface LMSAnswerInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {

  /**
   * Gets the lms_answer name.
   *
   * @return string
   *   The lms_answer name.
   */
  public function getName();

  /**
   * Sets the lms_answer name.
   *
   * @param string $name
   *   The lms_answer name.
   *
   * @return $this
   */
  public function setName($name);

  /**
   * Gets the lms_answer creation timestamp.
   *
   * @return int
   *   The lms_answer creation timestamp.
   */
  public function getCreatedTime();

  /**
   * Sets the lms_answer creation timestamp.
   *
   * @param int $timestamp
   *   The lms_answer creation timestamp.
   *
   * @return $this
   */
  public function setCreatedTime($timestamp);

}
