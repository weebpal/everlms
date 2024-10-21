<?php

namespace Drupal\lms_quiz\Entity;

use Drupal\address\AddressInterface;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\user\EntityOwnerInterface;
use Drupal\Core\Entity\EntityChangedInterface;

/**
 * Defines the interface for quiz items.
 */
interface LMSQuizInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {

  /**
   * Gets the lms_quiz name.
   *
   * @return string
   *   The lms_quiz name.
   */
  public function getName();

  /**
   * Sets the lms_quiz name.
   *
   * @param string $name
   *   The lms_quiz name.
   *
   * @return $this
   */
  public function setName($name);

  /**
   * Gets the lms_quiz creation timestamp.
   *
   * @return int
   *   The lms_quiz creation timestamp.
   */
  public function getCreatedTime();

  /**
   * Sets the lms_quiz creation timestamp.
   *
   * @param int $timestamp
   *   The lms_quiz creation timestamp.
   *
   * @return $this
   */
  public function setCreatedTime($timestamp);

}
