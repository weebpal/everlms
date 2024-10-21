<?php

namespace Drupal\lms_quiz\Service;

use Drupal;
use Drupal\Core\Url;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\lms_quiz_result\Entity\LMSQuizResultInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class Create
 * @package Drupal\lms_quiz\Service
 */
class Create {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a new Create service.
   */
  public function __construct() {
    $this->entityTypeManager = \Drupal::entityTypeManager();
  }

  /**
   * define function for create Question Response entity
   */
  public function createQuestionResponse($quiz, $questions, $form_values, $current_user, $quiz_name) {
    $question_responses = [];

    foreach ($questions as $question_order => $question) {
      //$current_question_id = $question->lms_question_id->value;
      $field_choices = $question->get('field_choices')->referencedEntities();
      $user_choices = $form_values['answers_select_' . $question_order];
      $user_answers = [];
      $correct_answers = [];
      $current_time = date("H:i:s");

      foreach ($field_choices as $field_choice_id => $field_choice) {
        $correct_answers +=
          $field_choice->field_correct->value == '1'
          ? [$field_choice_id => "$field_choice_id"]
          : [$field_choice_id => 0];
      }

      foreach ($user_choices as $choice_id => $choice) {
        if ($choice !== 0) {
          array_push($user_answers, $field_choices[$choice_id]);
        }
      }
      $user_display_name = $current_user->getDisplayName();
      $question_number = $question_order + 1;
      $quiz_take_time = 1;

      $field_correct = $correct_answers == $user_choices ? true : false;
      $entity_question_response = $this->entityTypeManager->getStorage('lms_question_response')
        ->create([
          'type' => 'default',
          'name' => "Question  {$question_number} response by {$user_display_name} from {$current_time} for {$quiz_name}",
          'field_body' => "Question  {$question_number} response by {$user_display_name} from {$current_time} for {$quiz_name} description",
          'field_question' => $question,
          'field_quiz' => $quiz,
          'field_answers' => $user_answers,
          'field_score' => $field_correct ? $question->field_score->value : 0,
          'field_correct' => $field_correct,
          'field_take_quiz_time' => $quiz_take_time,
        ]);
      $entity_question_response->save();
      $question_responses = array_merge($question_responses, [$entity_question_response]);
    }

    return $question_responses;
  }

  /**
   * define function for create Quiz Result entity
   */
  public function createQuizResult($quiz, $questions, $form_values, $current_user, $start_time, $end_time) {
    $quiz_name = $quiz->label();
    $question_responses = $this->createQuestionResponse($quiz, $questions, $form_values, $current_user, $quiz_name);
    $field_score = 0.00;

    foreach ($question_responses as $value) {
      $field_score += intval($value->field_correct->value);
    }

    $field_score /= (count($question_responses) / 100);
    $pass_score = floatval($quiz->field_threshold->value);
    $user = $this->entityTypeManager->getStorage('user')->load($current_user->id());

    $lmsBaseManager = Drupal::service('lms_base.manager');
    $list_term = $lmsBaseManager->getListTaxonomyByVid('quiz_results');

    $taxonomy_result = $field_score >= $pass_score ? $list_term['passed'] : $list_term['failed'];

    $user_display_name = $current_user->getDisplayName();

    $quiz_result = $this->entityTypeManager->getStorage('lms_quiz_result')
      ->create([
        'type' => 'default',
        'name' => "{$quiz_name} result from {$user_display_name}",
        'field_body' => "{$quiz_name} result from {$user_display_name} description",
        'field_quiz' => $quiz,
        'field_score' => round($field_score, 2),
        'field_result' => $taxonomy_result,
        'field_taker' => $user,
        'field_questions_response' => $question_responses,
        'field_start_time' => date('Y-m-d\TH:i:s', $start_time),
        'field_end_time' => date('Y-m-d\TH:i:s', $end_time),
      ]);

    $quiz_result->save();
    // Reference user certificate.
    if ($quiz_result && ($field_score >= $pass_score)) {
      $user_certificate = $this->createUserCertificate($quiz_result);
      if ($user_certificate) {
        $quiz_result->set('field_user_certificate', $user_certificate->id());
        $quiz_result->save();
      }
    }

    // Handle user course
    $userCourseManager = Drupal::service('lms_user_course.manager');
    $user_courses = $userCourseManager->getUserCourseByQuizId($quiz->id(), $user->id());
    // Add quiz result to user course
    foreach ($user_courses as $user_course) {
      $field_values = $user_course->get('field_quiz_results')->referencedEntities();
      if (!empty($field_values)) {
        if (!in_array($quiz_result->id(), array_column($field_values, 'target_id'))) {
          $field_values[] = ['target_id' => $quiz_result->id()];
          $user_course->set('field_quiz_results', $field_values);
        }
      } else {
        // Just new value.
        $user_course->set('field_quiz_results', ['target_id' => $quiz_result->id()]);
      }
      $user_course->save();
      // Calculate quiz progress and course certificate
      $userCourseManager->calculateQuizResultAndCertificate($user_course);
    }
    return $quiz_result;
  }

  /**
   * define function to update Quiz Result entity
   */
  public function updateQuizResult($quiz_result, $quiz, $questions, $form_values, $current_user, $start_time, $end_time) {
    $quiz_name = $quiz->label();
    $question_responses = $this->createQuestionResponse($quiz, $questions, $form_values, $current_user, $quiz_name);
    $field_score = 0.00;

    foreach ($question_responses as $value) {
      $field_score += intval($value->field_correct->value);
    }

    $field_score /= (count($question_responses) / 100);
    $pass_score = floatval($quiz->field_threshold->value);
    $user = $this->entityTypeManager->getStorage('user')->load($current_user->id());

    $lmsBaseManager = Drupal::service('lms_base.manager');
    $list_term = $lmsBaseManager->getListTaxonomyByVid('quiz_results');

    $taxonomy_result = $field_score >= $pass_score ? $list_term['passed'] : $list_term['failed'];

    $quiz_take_time = $quiz_result->get('field_take_quiz_time')->value;
    if ($quiz_take_time) {
      $quiz_take_time = $quiz_take_time + 1;
    } else {
      $quiz_take_time = 1;
    }

    $quiz_result->set('field_take_quiz_time', $quiz_take_time);
    $quiz_result->set('field_score', round($field_score, 2));
    $quiz_result->set('field_result', $taxonomy_result);
    $quiz_result->set('field_questions_response', $question_responses);
    $quiz_result->set('field_start_time', date('Y-m-d\TH:i:s', $start_time));
    $quiz_result->set('field_end_time', date('Y-m-d\TH:i:s', $end_time));

    $quiz_result->save();
    // Reference user certificate.
    if ($quiz_result && ($field_score >= $pass_score)) {
      $user_certificate = $this->createUserCertificate($quiz_result);
      if ($user_certificate) {
        $quiz_result->set('field_user_certificate', $user_certificate->id());
        $quiz_result->save();
      }
    }

    // Handle user course
    $userCourseManager = Drupal::service('lms_user_course.manager');
    $user_courses = $userCourseManager->getUserCourseByQuizId($quiz->id(), $user->id());
    // Add quiz result to user course
    foreach ($user_courses as $user_course) {
      $field_values = $user_course->get('field_quiz_results')->referencedEntities();
      if (!empty($field_values)) {
        if (!in_array($quiz_result->id(), array_column($field_values, 'target_id'))) {
          $field_values[] = ['target_id' => $quiz_result->id()];
          $user_course->set('field_quiz_results', $field_values);
        }
      } else {
        // Just new value.
        $user_course->set('field_quiz_results', ['target_id' => $quiz_result->id()]);
      }
      $user_course->save();
      // Calculate quiz progress and course certificate
      $userCourseManager->calculateQuizResultAndCertificate($user_course);
    }
    return $quiz_result;
  }

  /**
   * define function for create User Certificate entity
   */
  public function createUserCertificate(LMSQuizResultInterface $quiz_result) {
    $current_time = \Drupal::time()->getCurrentTime();
    $quiz = $quiz_result->get('field_quiz')->entity;
    $quiz_name = $quiz->label();
    $taker = $quiz_result->get('field_taker')->entity;
    $certificate = $quiz->get('field_certificate')->entity;
    $link_down = Url::fromRoute('lms_quiz.download_certificate', ['lms_quiz_result' => $quiz_result->id()])->toString();
    $user_display_name = $taker->getDisplayName();
    $user_certificate = $this->entityTypeManager->getStorage('lms_user_certificate')
      ->create([
        'type' => 'default',
        'name' => "{$user_display_name} pass {$quiz_name}",
        'field_body' => "{$user_display_name} pass {$quiz_name} description",
        'field_certificate' => $certificate,
        'field_holder' => $taker,
        'field_link' => $link_down,
        'field_get_time' => $current_time,
      ]);
    $user_certificate->save();

    return $user_certificate;
  }
}
