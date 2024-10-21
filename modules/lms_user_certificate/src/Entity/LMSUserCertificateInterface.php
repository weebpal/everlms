<?php

namespace Drupal\lms_user_certificate\Entity;

use Drupal\address\AddressInterface;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\user\EntityOwnerInterface;
use Drupal\Core\Entity\EntityChangedInterface;

/**
 * Defines the interface for user certificate items.
 */
interface LMSUserCertificateInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {

  /**
   * Gets the lms_user_certificate name.
   *
   * @return string
   *   The lms_user_certificate name.
   */
  public function getName();

  /**
   * Sets the lms_user_certificate name.
   *
   * @param string $name
   *   The lms_user_certificate name.
   *
   * @return $this
   */
  public function setName($name);

  /**
   * Gets the lms_user_certificate creation timestamp.
   *
   * @return int
   *   The lms_user_certificate creation timestamp.
   */
  public function getCreatedTime();

  /**
   * Sets the lms_user_certificate creation timestamp.
   *
   * @param int $timestamp
   *   The lms_user_certificate creation timestamp.
   *
   * @return $this
   */
  public function setCreatedTime($timestamp);

}
