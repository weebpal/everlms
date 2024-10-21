<?php

namespace Drupal\lms_lesson\Entity;

use Drupal\address\AddressInterface;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\user\EntityOwnerInterface;
use Drupal\Core\Entity\EntityChangedInterface;

/**
 * Defines the interface for lesson items.
 */
interface LMSLessonInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {

  /**
   * Gets the lms_lesson name.
   *
   * @return string
   *   The lms_lesson name.
   */
  public function getName();

  /**
   * Sets the lms_lesson name.
   *
   * @param string $name
   *   The lms_lesson name.
   *
   * @return $this
   */
  public function setName($name);

  /**
   * Gets the lms_lesson creation timestamp.
   *
   * @return int
   *   The lms_lesson creation timestamp.
   */
  public function getCreatedTime();

  /**
   * Sets the lms_lesson creation timestamp.
   *
   * @param int $timestamp
   *   The lms_lesson creation timestamp.
   *
   * @return $this
   */
  public function setCreatedTime($timestamp);

}
