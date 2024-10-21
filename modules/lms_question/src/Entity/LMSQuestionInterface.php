<?php

namespace Drupal\lms_question\Entity;

use Drupal\address\AddressInterface;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\user\EntityOwnerInterface;
use Drupal\Core\Entity\EntityChangedInterface;

/**
 * Defines the interface for question items.
 */
interface LMSQuestionInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {

  /**
   * Gets the lms_question name.
   *
   * @return string
   *   The lms_question name.
   */
  public function getName();

  /**
   * Sets the lms_question name.
   *
   * @param string $name
   *   The lms_question name.
   *
   * @return $this
   */
  public function setName($name);

  /**
   * Gets the lms_question creation timestamp.
   *
   * @return int
   *   The lms_question creation timestamp.
   */
  public function getCreatedTime();

  /**
   * Sets the lms_question creation timestamp.
   *
   * @param int $timestamp
   *   The lms_question creation timestamp.
   *
   * @return $this
   */
  public function setCreatedTime($timestamp);

}
