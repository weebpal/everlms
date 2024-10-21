<?php

namespace Drupal\lms_user_ceritificate\Entity;

use Drupal\address\AddressInterface;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\user\EntityOwnerInterface;
use Drupal\Core\Entity\EntityChangedInterface;

/**
 * Defines the interface for user ceritificate items.
 */
interface LMSUserCertificateInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {

  /**
   * Gets the lms_user_ceritificate name.
   *
   * @return string
   *   The lms_user_ceritificate name.
   */
  public function getName();

  /**
   * Sets the lms_user_ceritificate name.
   *
   * @param string $name
   *   The lms_user_ceritificate name.
   *
   * @return $this
   */
  public function setName($name);

  /**
   * Gets the lms_user_ceritificate creation timestamp.
   *
   * @return int
   *   The lms_user_ceritificate creation timestamp.
   */
  public function getCreatedTime();

  /**
   * Sets the lms_user_ceritificate creation timestamp.
   *
   * @param int $timestamp
   *   The lms_user_ceritificate creation timestamp.
   *
   * @return $this
   */
  public function setCreatedTime($timestamp);

}
