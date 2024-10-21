<?php

namespace Drupal\lms_quiz_result\Entity;

use Drupal\address\AddressInterface;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\user\EntityOwnerInterface;
use Drupal\Core\Entity\EntityChangedInterface;

/**
 * Defines the interface for quiz result items.
 */
interface LMSQuizResultInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {

  /**
   * Gets the lms_quiz_result name.
   *
   * @return string
   *   The lms_quiz_result name.
   */
  public function getName();

  /**
   * Sets the lms_quiz_result name.
   *
   * @param string $name
   *   The lms_quiz_result name.
   *
   * @return $this
   */
  public function setName($name);

  /**
   * Gets the lms_quiz_result creation timestamp.
   *
   * @return int
   *   The lms_quiz_result creation timestamp.
   */
  public function getCreatedTime();

  /**
   * Sets the lms_quiz_result creation timestamp.
   *
   * @param int $timestamp
   *   The lms_quiz_result creation timestamp.
   *
   * @return $this
   */
  public function setCreatedTime($timestamp);

}
