<?php

namespace Drupal\lms_membership\EventSubscriber;

use Drupal\commerce_order\Entity\OrderInterface;
use Drupal\commerce_order\Event\OrderEvent;
use Drupal\lms_membership\MembershipManagerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
/**
 * Add subscription when an order is completed.
 */
class OrderComplete implements EventSubscriberInterface {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;

  /**
   * @var \Drupal\lms_membership\MembershipManagerInterface
   */
  protected $membershipManager;

  /**
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   * @param \Drupal\Core\Session\AccountProxyInterface $current_user
   * @param \Drupal\lms_membership\MembershipManagerInterface $lms_membership_manager
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, AccountProxyInterface $current_user, MembershipManagerInterface $lms_membership_manager) {
    $this->entityTypeManager = $entity_type_manager;
    $this->currentUser = $current_user;
    $this->membershipManager = $lms_membership_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events = [
      'commerce_checkout.completion' => 'addMembership',
    ];
    return $events;
  }

  /**
   * @param \Drupal\commerce_order\Event\OrderEvent $event
   */
  public function addMembership(OrderEvent $event) {
    /** @var OrderInterface $order */
    $order = $event->getOrder();
    $this->membershipManager->createMembership($order);
  }
}
