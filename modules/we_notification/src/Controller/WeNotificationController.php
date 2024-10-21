<?php

namespace Drupal\we_notification\Controller;

use Drupal\commerce_order\Entity\OrderInterface;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Url;
use Drupal\lms_quiz_result\Entity\LMSQuizResultInterface;
use Drupal\lms_user_certificate\Entity\LMSUserCertificateInterface;
use Drupal\we_notification\NotificationManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Controller for handling notifications.
 */
class WeNotificationController extends ControllerBase {

  /**
   * The Notification Manager service.
   *
   * @var \Drupal\we_notification\NotificationManagerInterface
   */
  protected $notificationManager;

  /**
   * The Entity Type Manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a new NotificationController instance.
   *
   * @param \Drupal\we_notification\NotificationManagerInterface $notification_manager
   *   The Notification Manager service.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The Entity Type Manager service.
   */
  public function __construct(NotificationManagerInterface $notification_manager, EntityTypeManagerInterface $entity_type_manager) {
    $this->notificationManager = $notification_manager;
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('we_notification.notification_service'),
      $container->get('entity_type.manager')
    );
  }

  /**
   * Create a new notification.
   */
  public function createNotificationCertificate(LMSUserCertificateInterface $certificate) {
    $host_name = \Drupal::request()->getHost();
    $holder = $certificate->get('field_holder')->referencedEntities();
    $holder = reset($holder);
    $holder_name = $holder->label();
    $link_down = $certificate->get('field_link')->getValue();
    if (!empty($link_down)) {
      $link_down = reset($link_down);
    }
    
    $link = 'https://'.$host_name.$link_down['value'];
    $notificationData = [
      'shortDescription' => $holder_name.' has a Certificate',
      'detailedDescription' => 'detailed Description',
      'link' => $link,
    ];
    $notification = $this->notificationManager->createNotification($notificationData);
    $url = $notification->toUrl('canonical')->toString();

    return new RedirectResponse($url);
  }

  public function createNotificationBuyCourse(OrderInterface $course) {
    $customer_id = $course->getCustomerId();
    $customer_name = $course->getCustomer()->getAccountName();
    $order_id = $course->id();
    $link = 'https://lms.dev.weebpal.com/user/'.$customer_id.'//orders/'.$order_id;
    $notificationData = [
      'shortDescription' => $customer_name.' has purchased the order',
      'detailedDescription' => 'detailed Description',
      'link' => $link,
    ];
    $notification = $this->notificationManager->createNotification($notificationData);
    $url = $notification->toUrl('canonical')->toString();

    return new RedirectResponse($url);
  }

  public function createNotificationTakeQuiz(LMSQuizResultInterface $quiz_result) {
    $host_name = \Drupal::request()->getHost();
    $quiz_result_id = $quiz_result->id();
    $quiz = $quiz_result->get('field_quiz')->referencedEntities();
    $quiz = reset($quiz);
    $quiz_name = $quiz->label();
    $taker = $quiz_result->get('field_taker')->referencedEntities();
    $taker = reset($taker);
    $taker_name = $taker->getDisplayName();
    $score = $quiz_result->get('field_score')->getValue();
    $score = reset($score);
    
    $link = 'https://'.$host_name.'/take-quiz/result/'.$quiz_result_id;
    $notificationData = [
      'shortDescription' => $taker_name." Take Quiz: '".$quiz_name."'",
      'detailedDescription' => 'Score: '.$score['value'],
      'link' => $link,
    ];
    $notification = $this->notificationManager->createNotification($notificationData);
    $url = $notification->toUrl('canonical')->toString();

    return new RedirectResponse($url);
  }

  /**
   * Load a notification by its ID.
   */
  public function loadNotification($notificationId) {
    $this->notificationManager->loadNotification($notificationId);

  }

  /**
   * Mark a notification as read.
   */
  public function markReadNotification($notificationId) {
    $this->notificationManager->markReadNotification($notificationId);
    $url = Url::fromRoute('entity.we_notification.canonical', ['we_notification' => $notificationId]);

    return new RedirectResponse($url->toString());
  }
}
