<?php

namespace Drupal\lms_membership;

use Drupal\commerce_order\Entity\Order;
use Drupal\profile\Entity\Profile;
use Drupal\taxonomy\Entity\Term;
use Drupal\user\Entity\User;

/**
 * Provides a membership service.
 */
interface MembershipManagerInterface {

  /**
   * @param \Drupal\commerce_order\Entity\Order $order
   *
   * @return mixed
   */
  public function createMembership(Order $order);

  /**
   * @param \Drupal\user\Entity\User $user
   *
   * @return mixed
   */
  public function getMembershipOfUser(User $user);

  /**
   * @param string $membership_type_key
   * @param $quantity
   * @param null $expired_time
   *
   * @return mixed
   */
  public function getExpiredDateOfMembership(string $membership_type_key, $quantity, $expired_time = NULL);

  /**
   * @param $start_time
   * @param $expired_time
   *
   * @return mixed
   */
  public function getStartDateOfMembership($start_time, $expired_time);

  /**
   * @param string $datetime
   * @param string|null $date_format
   *
   * @return mixed
   */
  public function getTimeWithTimezone(string $datetime = 'now', string $date_format = NULL);

  /**
   * @return mixed
   */
  public function subscriptionCron();

  /**
   * @param \Drupal\profile\Entity\Profile $profile
   *
   * @return mixed
   */
  public function checkExpired(Profile $profile);

}
