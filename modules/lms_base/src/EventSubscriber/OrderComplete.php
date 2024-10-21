<?php

namespace Drupal\lms_base\EventSubscriber;

use Drupal;
use Drupal\commerce_order\Entity\OrderInterface;
use Drupal\commerce_order\Event\OrderEvent;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\we_notification\Controller\WeNotificationController;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * add notification when an order is placed.
 */
class OrderComplete implements EventSubscriberInterface {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * OrderComplete constructor.
   *
   * @param EntityTypeManagerInterface $entity_type_manager
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events = [
      'commerce_checkout.completion' => 'addNotification',
    ];
    return $events;
  }

  /**
   *
   * @param Drupal\commerce_order\Event\OrderEvent $event
   *   The event we subscribed to.
   */
  public function addNotification(OrderEvent $event) {
    /** @var OrderInterface $order */
    $order = $event->getOrder();
    $notification_service = \Drupal::service('we_notification.notification_service');
    $notification_controller = new WeNotificationController($notification_service, $this->entityTypeManager);
    $notification_controller->createNotificationBuyCourse($order);
  }
}
