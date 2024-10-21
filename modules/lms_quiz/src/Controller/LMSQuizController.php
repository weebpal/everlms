<?php

namespace Drupal\lms_quiz\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Url;
use Drupal\lms_quiz\LMSQuizManagerInterface;
use Drupal\views\Views;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * LMSQuizController.
 */
class LMSQuizController extends ControllerBase {

  /**
   * The route match.
   *
   * @var \Drupal\Core\Routing\RouteMatchInterface
   */
  protected $routeMatch;

  /**
   * @var \Drupal\lms_quiz\LMSQuizManagerInterface
   */
  protected $LMSQuizManager;

  /**
   * @param \Drupal\Core\Routing\RouteMatchInterface $route_match
   * @param \Drupal\lms_quiz\LMSQuizManagerInterface $lms_quiz_manager
   */
  public function __construct(RouteMatchInterface $route_match, LMSQuizManagerInterface $lms_quiz_manager) {
    $this->routeMatch = $route_match;
    $this->LMSQuizManager = $lms_quiz_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('current_route_match'),
      $container->get('lms_quiz.quiz_manager')
    );
  }

  /**
   * @param \Symfony\Component\HttpFoundation\Request $request
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   */
  public function redirectTakeQuizCallback(Request $request) {
    $content = $request->getContent();
    if ($content) {
      $data = json_decode($content, TRUE);
      if ($data['quiz_id'] && $data['uid']) {
        $quiz_result = $this->LMSQuizManager->getQuizResultExist($data['quiz_id'], $data['uid']);
        if ($quiz_result) {
          return new JsonResponse([
            'status' => 200,
            'redirect_url' => Url::fromRoute('lms_quiz.result_quiz', ['lms_quiz_result' => $quiz_result->id()])->toString(),
          ]);
        }
      }
    }
    return new JsonResponse(['status' => 400]);
  }

}
