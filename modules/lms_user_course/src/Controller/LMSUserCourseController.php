<?php

namespace Drupal\lms_user_course\Controller;

use Drupal\Core\Url;
use Drupal\Core\Controller\ControllerBase;
use Drupal\commerce_product\Entity\Product;
use Drupal\Core\Routing\RouteMatchInterface;
use Symfony\Component\HttpFoundation\Request;
use Drupal\lms_user_course\Entity\LMSUserCourse;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides route responses for the Example module.
 */
class LMSUserCourseController extends ControllerBase {

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
   * Constructs a new LMSUserCourseController object.
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
   * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
   */
  public function resultPage(Request $request, LMSUserCourse $lms_user_course) {
    if ($lms_user_course) {
      $twig_template = NULL;

      $result = $lms_user_course->get('field_result')->entity;
      $score = NULL;
      $result_name = NULL;
      if ($result) {
        $score = $lms_user_course->get('field_score')->value;
        $result_name = $result->get('name')->getString();
      }
      /** @var Product $course */
      $course = $lms_user_course->get('field_course')->entity;

      $course_name = NULL;
      if ($course) {
        $course_name = $course->label();
        $total = $course->get('field_total_quizzes')->value;
      }

      $total_quiz_pass = $lms_user_course->get('field_total_quiz_pass')->value;

      $user_certificate = $lms_user_course->get('field_user_certificate')->entity;
      //load certificate data
      if ($user_certificate) {
        $certificate = $user_certificate->get('field_certificate')->entity;
        $holder = $user_certificate->get('field_holder')->entity;
        $holder_name = NULL;
        if ($holder) {
          $holder_name = $holder->get('name')->getString();
        }
        $get_time = NULL;
        if ($user_certificate && !$user_certificate->get('field_get_time')->isEmpty()) {
          $get_time_timestamp = $user_certificate->get('field_get_time')->value;
          $get_time = date('F j, Y', $get_time_timestamp);
        }

        $twig_template = $certificate->get('field_twig_template')->getString();

        $twig_template = str_replace('[date]', $get_time, $twig_template);
        $twig_template = str_replace('[name]', $holder_name, $twig_template);
        $twig_template = str_replace('[course]', $course_name, $twig_template);
        $twig_template = str_replace('[score]', "{$score} %", $twig_template);
      }

      return [
        '#theme' => 'lms_user_course_result',
        '#data' => [
          'score' => $score,
          'user_certificate' => $user_certificate,
          'twig_template' => $twig_template,
          'result' => $result_name,
          'correct' => $total_quiz_pass,
          'total' => $total,
          'url' => Url::fromRoute('lms_user_course.download_certificate', ['lms_user_course' => $lms_user_course->id()])->toString(),
        ],
      ];
    } else {
      return $this->redirect('system.404');
    }
  }
}
