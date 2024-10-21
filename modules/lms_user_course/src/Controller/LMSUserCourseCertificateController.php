<?php

namespace Drupal\lms_user_course\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides route responses for the User course certificate.
 */
class LMSUserCourseCertificateController extends ControllerBase {

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

  protected $mpdf_file;

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
    $this->mpdf_file = new \Mpdf\Mpdf(['tempDir' => './sites/default/files/pdf']);
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
   * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response|void
   * @throws \Mpdf\MpdfException
   */
  public function createCertificatePDF() {
    $user_course = $this->routeMatch->getParameter('lms_user_course');
    if ($user_course) {
      $course_name = NULL;
      $course = $user_course->get('field_course')->entity;
      if ($course) {
        $course_name = $course->label();
      }
      $user_certificate = $user_course->get('field_user_certificate')->entity;
      $get_time = NULL;
      if ($user_certificate && !$user_certificate->get('field_get_time')->isEmpty()) {
        $get_time_timestamp = $user_certificate->get('field_get_time')->value;
        $get_time = date('F j, Y', $get_time_timestamp);
      }
      $score = $user_course->get('field_score')->value;
      $certificate = $user_certificate->get('field_certificate')->entity;
      if ($certificate) {
        $holder_name = NULL;
        $holder = $user_certificate->get('field_holder')->entity;
        if ($holder) {
          $holder_name = $holder->get('name')->getString();
        }

        $twig_template = $certificate->get('field_twig_template')->getString();

        $twig_template = str_replace('[date]', $get_time, $twig_template);
        $twig_template = str_replace('[name]', $holder_name, $twig_template);
        $twig_template = str_replace('[course]', $course_name, $twig_template);
        $twig_template = str_replace('[score]', "{$score} %", $twig_template);

        $stylesheet = file_get_contents('./modules/custom/lms_quiz/css/pdf.css');
        $this->mpdf_file->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
        $this->mpdf_file->WriteHTML($twig_template, \Mpdf\HTMLParserMode::HTML_BODY);

        $filename = 'CourseCertificate.pdf';
        $response = new Response();

        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');
        $response->setContent($this->mpdf_file->Output($filename, 'D'));
        return $response;
      }
    } else {
      return $this->redirect('system.404');
    }
  }
}
