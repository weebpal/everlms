<?php

namespace Drupal\zoom_integration\Controller;

use DateTimeImmutable;
use Drupal\Core\Controller\ControllerBase;
use GuzzleHttp\Exception\RequestException;
use Drupal\Component\Serialization\Json;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\key\KeyRepositoryInterface;
use Drupal\user\Entity\User;
use Drupal\zoom_integration\Event\ZoomAPIEvents;
use Drupal\zoom_integration\Event\ZoomAPIWebhookEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Drupal\Core\Cache\Cache;
use Drupal\lms_lesson\Entity\LMSLesson;

/**
 * Class ZoomAPIController.
 */
class ZoomAPIController extends ControllerBase {

  /**
   * The Immutable Config Object.
   *
   * @var \Drupal\Core\Config\ImmutableConfig
   */
  protected $config;

  /**
   * The KeyRepositoryInterface.
   *
   * @var \Drupal\key\KeyRepositoryInterface
   */
  protected $keyRepository;

  /**
   * Psr\Log\LoggerInterface definition.
   *
   * @var \Psr\Log\LoggerInterface
   */
  protected $logger;

  /**
   * Symfony\Component\HttpFoundation\RequestStack definition.
   *
   * @var \Symfony\Component\HttpFoundation\RequestStack
   */
  protected $requestStack;

  /**
   * Symfony\Component\EventDispatcher\EventDispatcherInterface definition.
   *
   * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
   */
  protected $eventDispatcher;

  /**
   * Enable or disable debugging.
   *
   * @var bool
   */
  protected $debug = FALSE;

  /**
   * Zoom webhook verification token.
   *
   * @var string
   */
  protected $webhookVerificationToken;

  /**
   * Constructs a new WebhookController object.
   *
   * @param \Psr\Log\LoggerInterface $logger
   *   Logger interface.
   * @param \Drupal\key\KeyRepositoryInterface $key_repository
   *   Key repository interface.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   Config factory interface.
   * @param \Symfony\Component\HttpFoundation\RequestStack $request_stack
   *   Request stack.
   * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $event_dispatcher
   *   Event dispatcher interface.
   */
  public function __construct(
    ConfigFactoryInterface $config_factory,
  ) {
    $this->config = $config_factory->get('zoom_integration.settings');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
    );
  }

  public function test() {
    // $lesson = LMSLesson::load(45);
    // $currentDateTime = time(); // current timestamp
    // dump(date('Y-m-d\TH:i:s\Z', $currentDateTime));
    // $compareDateTime = strtotime($lesson->get('field_class_time')->getValue()[0]['value']);
    // dump($lesson->get('field_class_time')->getValue()[0]);
    // $time_end = strtotime($lesson->get('field_class_time')->getValue()[0]['end_value']);
    // if(!empty($time_end) && $time_end > $currentDateTime){
    //   $time_remain = $time_end - $currentDateTime;
    //   if($time_remain < 1800) {
    //     $time_remain = 1800;
    //   } elseif($time_remain > 172800) {
    //     $time_remain = 172800;
    //   }
    //   dump($time_remain);
    //   $iExp = $time_remain + $currentDateTime;
    // }
    // dump($iExp);

    // dump((new DateTimeImmutable())->getTimestamp());
    // dump(date('Y-m-d\TH:i:s\Z', (new DateTimeImmutable())->getTimestamp()));
    // dump(date('Y-m-d\TH:i:s\Z', (new DateTimeImmutable())->getTimestamp()));
    // $currentDateTime = time();
    // $startDateTime = strtotime($lesson->get('field_class_time')->getValue()[0]['value']);
    // $endDateTime = strtotime($lesson->get('field_class_time')->getValue()[0]['end_value']);
    // if ($currentDateTime > $startDateTime && $endDateTime > $currentDateTime) {
    //   dump( "Current time is greater than 2024-01-31T02:45:00");
    // } else {
    //     dump( "Current time is not greater than 2024-01-31T02:45:00");
    // }
    return [];
  }
}
