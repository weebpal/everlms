<?php

namespace Drupal\lms_user_course;

use Drupal\lms_user_course\Entity\LMSUserCourseInterface;

/**
 * Provides a user course service.
 */
interface LMSUserCourseManagerInterface {

  /**
   * Create user course
   */
  public function createUserCourse(string $course_id, string $uid);

  /**
   * Update user course
   */
  public function updateUserCourse(LMSUserCourseInterface $user_course);

  /**
   * Check user course, if user course exist, return user course
   */
  public function getUserCourseExist(string $course_id, string $uid);

  /**
   * Get all user course has quiz
   */
  public function getUserCourseByQuizId(string $quiz_id, string $uid);

  /**
   * Create user course from quiz
   */
  public function createUserCourseByQuizId(string $quiz_id, string $uid);

  /**
   * Calculate quiz result and create certificate for user course
   */
  public function calculateQuizResultAndCertificate(LMSUserCourseInterface $user_course);

  /**
   * Function to create certificate for User course
   */
  public function createCertificateForUserCourse(LMSUserCourseInterface $user_course);
}
