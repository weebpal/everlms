<?php

namespace Drupal\lms_user_course;

use Drupal;
use Drupal\Core\Url;
use Drupal\commerce_product\Entity\Product;
use Drupal\lms_base\LMSBaseManagerInterface;
use Drupal\lms_user_course\Entity\LMSUserCourse;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\lms_quiz_result\LMSQuizResultManagerInterface;
use Drupal\lms_user_course\Entity\LMSUserCourseInterface;
use Drupal\lms_base\PermissionManagerInterface;

class LMSUserCourseManager implements LMSUserCourseManagerInterface {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected EntityTypeManagerInterface $entityTypeManager;

  /**
   * LMS Base Manager
   *
   * @var \Drupal\lms_base\LMSBaseManagerInterface
   */
  protected LMSBaseManagerInterface $lmsBaseManager;

  /**
   * Quiz Result Manager
   *
   * @var \Drupal\lms_quiz_result\LMSQuizResultManagerInterface
   */
  protected LMSQuizResultManagerInterface $quizResultManager;


  /**
   * LMS Permission Manager
   *
   * @var \Drupal\lms_base\PermissionManagerInterface
   */
  protected PermissionManagerInterface $permissionManager;

  /**
   * Constructs a new User course service.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, LMSBaseManagerInterface $lms_base_manager,  LMSQuizResultManagerInterface $quiz_result_manager, PermissionManagerInterface $permission_manager) {
    $this->entityTypeManager = $entity_type_manager;
    $this->lmsBaseManager = $lms_base_manager;
    $this->quizResultManager = $quiz_result_manager;
    $this->permissionManager = $permission_manager;
  }

  /**
   * @inheritdoc
   */
  public function createUserCourse(string $course_id, string $uid) {
    $quiz_results = $this->quizResultManager->getQuizResultByUid($uid, 'target_id');
    // Calculate quiz progress and course certificate
    $user_course_value = [
      'name' => t('User course of course @id', ['@id' => $course_id]),
      'type' => 'default',
      'field_course' => $course_id,
      'field_user' => $uid,
      'field_quiz_results' => $quiz_results,
      'status' => TRUE,
      'uid' => $uid
    ];
    $user_course = LMSUserCourse::create($user_course_value);
    $user_course->save();
    $this->calculateQuizResultAndCertificate($user_course);
    return $user_course;
  }

  /**
   * @inheritdoc
   */
  public function updateUserCourse(LMSUserCourseInterface $user_course) {
    $uid = $user_course->get('field_user')->target_id;
    $quiz_results = $this->quizResultManager->getQuizResultByUid($uid, 'target_id');
    $user_course->set('field_quiz_results', $quiz_results);
    $user_course->set('status', TRUE);
    $user_course->save();
    // Calculate quiz progress and course certificate
    $this->calculateQuizResultAndCertificate($user_course);
    return $user_course;
  }

  /**
   * @inheritdoc
   */
  public function getUserCourseExist(string $course_id, string $uid) {
    $user_course_ids = Drupal::entityQuery('lms_user_course')
      ->condition('type', 'default')
      ->condition('field_course', $course_id)
      ->condition('field_user', $uid)
      ->accessCheck()
      ->execute();
    if ($user_course_ids) {
      $user_course_id =  reset($user_course_ids);
      $user_course = LMSUserCourse::load($user_course_id);
      return $user_course;
    }
    return [];
  }

  /**
   * @inheritdoc
   */
  public function getUserCourseByQuizId(string $quiz_id, string $uid) {
    $course_ids = Drupal::entityQuery('commerce_product')
      ->condition('field_quizzes', $quiz_id)
      ->accessCheck()
      ->execute();
    if ($course_ids) {
      $user_course_ids = Drupal::entityQuery('lms_user_course')
        ->condition('type', 'default')
        ->condition('field_course', $course_ids, 'IN')
        ->condition('field_user', $uid)
        ->accessCheck()
        ->execute();
      $user_courses = $this->entityTypeManager->getStorage('lms_user_course')->loadMultiple($user_course_ids);
      return $user_courses;
    }
    return [];
  }

  /**
   * @inheritdoc
   */
  public function createUserCourseByQuizId(string $quiz_id, string $uid) {
    $course_ids = Drupal::entityQuery('commerce_product')
      ->condition('field_quizzes', $quiz_id)
      ->accessCheck()
      ->execute();
    if ($course_ids) {
      $user_courses = [];
      foreach ($course_ids as $course_id) {
        $permission = $this->permissionManager->checkCoursePermission($course_id, $uid);
        if ($permission) {
          $user_course = $this->createUserCourse($course_id, $uid);
          if ($user_course) {
            $user_courses[] = $user_course;
          }
        }
      }
      return $user_courses;
    }
    return [];
  }

  /**
   * @inheritdoc
   */
  public function calculateQuizResultAndCertificate(LMSUserCourseInterface $user_course) {
    $course = $user_course->get('field_course')->entity;
    if ($course) {
      $list_term = $this->lmsBaseManager->getListTaxonomyByVid('quiz_results');
      $total_passed = 0;
      $total_quizzes = $course->get('field_total_quizzes')->value;
      $threshold = $course->get('field_threshold')->value;
      $quiz_results = $user_course->get('field_quiz_results')->referencedEntities();
      foreach ($quiz_results as $quiz_result) {
        $result = $quiz_result->get('field_result')->entity;
        if ($result) {
          $result_key = $result->get('field_key')->value;
          if ($result_key == 'passed') {
            $total_passed++;
          }
        }
      }
      if ($total_quizzes) {
        $score_percent = ($total_passed / $total_quizzes) * 100;
      } else {
        $score_percent = 0;
      }

      $user_course->set('field_total_quiz_pass', $total_passed);
      $user_course->set('field_score', $score_percent);
      if ($score_percent >= $threshold) {
        // Set result for user course
        $user_course->set('field_result', $list_term['passed']);
        // Handle create course certificate
        $user_certificate = $this->createCertificateForUserCourse($user_course);
        if ($user_certificate) {
          $user_course->set('field_user_certificate', $user_certificate->id());
        }
      } else {
        $user_course->set('field_result', $list_term['failed']);
      }
      $user_course->save();
    }
  }


  /**
   * @inheritdoc
   */
  public function createCertificateForUserCourse(LMSUserCourseInterface $user_course) {
    $course = $user_course->get('field_course')->entity;
    if ($course) {
      $current_time = \Drupal::time()->getCurrentTime();
      $course_name = $course->label();
      $user = $user_course->get('field_user')->entity;
      $certificate = $course->get('field_certificate')->entity;
      $link_down = Url::fromRoute('lms_user_course.download_certificate', ['lms_user_course' => $user_course->id()])->toString();
      $user_display_name = $user->getDisplayName();
      $user_certificate = $this->entityTypeManager->getStorage('lms_user_certificate')
        ->create([
          'type' => 'default',
          'name' => "{$user_display_name} passed {$course_name}",
          'field_body' => "{$user_display_name} passed {$course_name} description",
          'field_certificate' => $certificate,
          'field_holder' => $user,
          'field_link' => $link_down,
          'field_get_time' => $current_time,
        ]);
      $user_certificate->save();
      return $user_certificate;
    }
    return NULL;
  }
}
