<?php

namespace Drupal\lms_lesson\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\lms_room\Entity\LMSRoom;
use Drupal\user\Entity\User;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Provides route responses for the Example module.
 */
class LMSLessonController extends ControllerBase {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The route match.
   *
   * @var \Drupal\Core\Routing\RouteMatchInterface
   */
  protected $routeMatch;

  /**
   * Constructs a new LMSQuizResultController object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\Core\Routing\RouteMatchInterface $route_match
   *   The router match
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, RouteMatchInterface $route_match) {
    $this->routeMatch = $route_match;
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('current_route_match'),
    );
  }

  /**
   * Zoom api callback
   * @param \Symfony\Component\HttpFoundation\Request $request
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   * @throws \Exception
   */
  public function zoomAPICallBack(Request $request) {
    $content = $request->getContent();
    if ($content) {
      $data = json_decode($content, TRUE);
      if ($data['start_time'] && $data['room_id']) {
        $room = LMSRoom::load($data['room_id']);
        $user = User::load($room->get('field_host')->getString());
        if (!empty($user)) {
          $client = \Drupal::service('zoom_integration.client');
          $user_zoom = $client->getUserByEmail($user->getEmail());
          if (!empty($user)) {
            $start_time = date('Y-m-d\TH:i:s\Z', strtotime($data['start_time']));
            $id = $user_zoom['id'];
            if ($start_time) {
              $response = $client->request(
                'POST',
                "/users/$id/meetings",
                [],
                ['start_time' => $start_time]
              );
              return new JsonResponse([
                'status' => 200,
                'zoom_data' => $response,
              ]);
            }
          }
        }
      }
    }
    return new JsonResponse(['status' => 400]);
  }
}
