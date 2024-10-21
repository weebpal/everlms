<?php

namespace Drupal\lms_question_response\Entity;

use Drupal\address\AddressInterface;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\user\EntityOwnerInterface;
use Drupal\Core\Entity\EntityChangedInterface;

/**
 * Defines the interface for question response items.
 */
interface LMSQuestionResponseInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {

  /**
   * Gets the lms_question_response name.
   *
   * @return string
   *   The lms_question_response name.
   */
  public function getName();

  /**
   * Sets the lms_question_response name.
   *
   * @param string $name
   *   The lms_question_response name.
   *
   * @return $this
   */
  public function setName($name);

  /**
   * Gets the lms_question_response creation timestamp.
   *
   * @return int
   *   The lms_question_response creation timestamp.
   */
  public function getCreatedTime();

  /**
   * Sets the lms_question_response creation timestamp.
   *
   * @param int $timestamp
   *   The lms_question_response creation timestamp.
   *
   * @return $this
   */
  public function setCreatedTime($timestamp);

}
