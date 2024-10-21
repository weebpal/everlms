<?php

namespace Drupal\lms_base;

use Drupal\user\Entity\User;
use Drupal\lms_lesson\Entity\LMSLesson;

/**
 * Provides a course service.
 */
interface PermissionManagerInterface {

  /**
   * @param $user_id
   * @param $lesson_id
   *
   * @return mixed
   */
  public function checkLessonPermission($user_id, $lesson_id);

  /**
   * @param $user_id
   * @param $lesson_id
   * @param $course_id
   *
   * @return mixed
   */
  public function checkLessonPermissionWithCourse($user_id, $lesson_id, $course_id);

  /**
   * @param $user_id
   * @param $quiz_id
   *
   * @return mixed
   */
  public function checkQuizPermission($user_id, $quiz_id);

  /**
   * @param $user_id
   * @param $quiz_id
   * @param $course_id
   *
   * @return mixed
   */
  public function checkQuizPermissionWithCourse($user_id, $quiz_id, $course_id);

  /**
   * @param $user_id
   * @param $course_id
   *
   * @return mixed
   */
  public function checkCoursePermission($user_id, $course_id);

  /**
   * @param $user_id
   *
   * @return mixed
   */
  public function checkPermissionByUid($user_id);

  /**
   * @param User $user
   * @param LMSLesson $lesson
   *
   * @return mixed
   */
  public function checkRole(User $user, LMSLesson $lesson);

}
