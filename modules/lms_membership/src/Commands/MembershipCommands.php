<?php

namespace Drupal\lms_membership\Commands;

use Consolidation\SiteAlias\SiteAliasManagerAwareTrait;
use Drupal\lms_membership\MembershipManagerInterface;
use Drush\Commands\DrushCommands;
use Drush\SiteAlias\SiteAliasManagerAwareInterface;

/**
 * A drush command file.
 *
 * @package Drupal\lms_membership\Commands
 */
class MembershipCommands extends DrushCommands implements SiteAliasManagerAwareInterface {

  use SiteAliasManagerAwareTrait;

  /**
   * @var \Drupal\lms_membership\MembershipManagerInterface
   */
  protected MembershipManagerInterface $membershipManager;

  /**
   * @param \Drupal\lms_membership\MembershipManagerInterface $membership_manager
   */
  public function __construct(MembershipManagerInterface $membership_manager) {
    parent::__construct();
    $this->membershipManager = $membership_manager;
  }

  /**
   * Drush command that check expired subscription.
   *
   * @command lms_membership:membership_cron
   *
   * @usage lms_membership:membership_cron
   *
   * @aliases lms_membership_sr
   */
  public function subscriptionCron() {
    $this->membershipManager->subscriptionCron();
  }
}