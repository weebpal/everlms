<?php

namespace Drupal\lms_user_course\Entity;

use Drupal\address\AddressInterface;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\user\EntityOwnerInterface;
use Drupal\Core\Entity\EntityChangedInterface;

/**
 * Defines the interface for user course items.
 */
interface LMSUserCourseInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {

  /**
   * Gets the lms_user_course name.
   *
   * @return string
   *   The lms_user_course name.
   */
  public function getName();

  /**
   * Sets the lms_user_course name.
   *
   * @param string $name
   *   The lms_user_course name.
   *
   * @return $this
   */
  public function setName($name);

  /**
   * Gets the lms_user_course creation timestamp.
   *
   * @return int
   *   The lms_user_course creation timestamp.
   */
  public function getCreatedTime();

  /**
   * Sets the lms_user_course creation timestamp.
   *
   * @param int $timestamp
   *   The lms_user_course creation timestamp.
   *
   * @return $this
   */
  public function setCreatedTime($timestamp);

}
