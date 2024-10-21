<?php

namespace Drupal\lms_quiz\Controller;

use Drupal\Core\Url;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides route responses for the Example module.
 */
class LMSQuizResultController extends ControllerBase {

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
   * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
   */
  public function resultPage() {
    $quiz_result = $this->routeMatch->getParameter('lms_quiz_result');
    if ($quiz_result) {
      $twig_template = NULL;
      $result_tables = [];
      $correct_questions = 0;

      $result = $quiz_result->get('field_result')->entity;
      $score = NULL;
      $result_name = NULL;
      if ($result) {
        $score = $quiz_result->get('field_score')->value;
        $result_name = $result->get('name')->getString();
      }

      $quiz = $quiz_result->get('field_quiz')->entity;

      $display_right_answer = FALSE;
      if ($quiz && $quiz->hasField('field_display_right_answer')) {
        $display_right_answer_value = $quiz->get('field_display_right_answer')->value;
        $display_right_answer = $display_right_answer_value ? TRUE : FALSE;
      }
      $quiz_name = NULL;
      if ($quiz) {
        $quiz_name = $quiz->get('name')->getString();
      }

      $table_header = [
        [
          'class' => 'quiz-result-question',
          'count' => TRUE,
          'title' => t('Question'),
        ],
        [
          'class' => 'quiz-result-choice',
          'title' => t('Choice'),
        ],
        [
          'class' => 'quiz-result-your-answer',
          'title' => t('Your Answer'),
        ],
        [
          'class' => 'quiz-result-correct',
          'title' => t('Correct?'),
        ],
        [
          'class' => 'quiz-result-right-answer',
          'title' => t('Right Answer'),
        ],
      ];

      $user_certificate = $quiz_result->get('field_user_certificate')->entity;
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
        $twig_template = str_replace('[course]', $quiz_name, $twig_template);
        $twig_template = str_replace('[score]', "{$score} %", $twig_template);
      }

      $question_responses = $quiz_result->get('field_questions_response')->referencedEntities();

      foreach ($question_responses as $question_responses_id => $question_response) {
        $right_answers = [];
        $rows = [];
        $question = $question_response->get('field_question')->entity;

        $user_answers = $question_response->get('field_answers')->referencedEntities();
        $user_answers_id = [];

        if ($question_response->field_correct->value) {
          $correct_questions += 1;
        }

        foreach ($user_answers as $id => $user_answer) {
          $user_answers_id += [$id => $user_answer->lms_answer_id->value];
        }
        $choices = $question->get('field_choices')->referencedEntities();
        foreach ($choices as $choice_id => $choice) {
          if ($choice->field_correct->value) {
            $right_answers += [$choice_id => $choice];
          }

          if ($choice_id) {
            $row = [
              $choice_id => [
                $choice->name->value,
                in_array($choice->lms_answer_id->value, $user_answers_id) ? 'selected' : '',
                in_array($choice->lms_answer_id->value, $user_answers_id) ? ($right_answers[$choice_id] ? 'Right' : 'Wrong') : '',
                $right_answers[$choice_id] ? TRUE : '',
              ],
            ];
          } else {
            $row = [
              $choice_id => [
                [
                  'data' => $question->name->value,
                  'rowspan' => count($choices),
                ],
                $choice->name->value,
                in_array($choice->lms_answer_id->value, $user_answers_id) ? 'selected' : '',
                in_array($choice->lms_answer_id->value, $user_answers_id) ? ($right_answers[$choice_id] ? 'Right' : 'Wrong') : '',
                $right_answers[$choice_id] ? TRUE : '',
              ],
            ];
          }
          $rows += $row;
        }

        $result_tables += [$question_responses_id => $rows];
      }
      return [
        '#theme' => 'lms_result_page',
        '#quiz_result' => [
          'quiz' => $quiz,
          'score' => $score,
          'display_right_answer' => $display_right_answer,
          'table_header' => $table_header,
          'tables' => $result_tables,
          'user_certificate' => $user_certificate,
          'twig_template' => $twig_template,
          'result' => $result_name,
          'correct' => $correct_questions,
          'total' => count($result_tables),
          'url' => Url::fromRoute('lms_quiz.download_certificate', ['lms_quiz_result' => $quiz_result->id()])->toString(),
        ],
      ];
    } else {
      return $this->redirect('system.404');
    }
  }
}
