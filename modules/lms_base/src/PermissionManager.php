<?php

namespace Drupal\lms_base;

use Drupal\user\Entity\User;
use Drupal\lms_lesson\Entity\LMSLesson;
use Drupal\commerce_product\Entity\Product;
use Drupal\Core\Entity\EntityTypeManagerInterface;

class PermissionManager implements PermissionManagerInterface {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected EntityTypeManagerInterface $entityTypeManager;

  /**
   * Constructs a new User Permission service.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public function checkLessonPermission($user_id, $lesson_id) {
    if (!empty($lesson_id)) {
      if ($this->checkPermissionByUid($user_id)) {
        return TRUE;
      }
      $lesson = LMSLesson::load($lesson_id);
      if ($lesson) {
        if ($lesson->hasField('field_zoom_class') && !$lesson->get('field_zoom_class')->isEmpty()) {
          $zoom_class = $lesson->get('field_zoom_class')->entity;
          if ($zoom_class && $zoom_class->hasField('field_host') && !$zoom_class->get('field_host')->isEmpty()) {
            $host_id = $zoom_class->get('field_host')->target_id;
            if ($host_id == $user_id) {
              return TRUE;
            }
          }
        }
        if ($lesson->hasField('field_free')) {
          $free = $lesson->get('field_free')->value;
          if ($free) {
            return TRUE;
          }
        }
        $courses = $this->entityTypeManager->getStorage('commerce_product')->loadByProperties(['field_lessons' => $lesson_id]);
        if (!empty($courses)) {
          foreach ($courses as $course) {
            $has_permission = $this->checkCoursePermission($user_id, $course->id());
            if ($has_permission) {
              $account = User::load($user_id);
              if ($account) {
                return $account->hasPermission('access lesson detail');
              }
            }
          }
        }
      }
    }
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function checkLessonPermissionWithCourse($user_id, $lesson_id, $course_id) {
    if (!empty($lesson_id)) {
      if ($this->checkPermissionByUid($user_id)) {
        return TRUE;
      }
      $lesson = LMSLesson::load($lesson_id);
      if ($lesson) {
        if ($lesson->hasField('field_zoom_class') && !$lesson->get('field_zoom_class')->isEmpty()) {
          $zoom_class = $lesson->get('field_zoom_class')->entity;
          if ($zoom_class && $zoom_class->hasField('field_host') && !$zoom_class->get('field_host')->isEmpty()) {
            $host_id = $zoom_class->get('field_host')->target_id;
            if ($host_id == $user_id) {
              return TRUE;
            }
          }
        }
        if ($lesson->hasField('field_free')) {
          $free = $lesson->get('field_free')->value;
          if ($free) {
            return TRUE;
          }
        }
        $has_permission = $this->checkCoursePermission($user_id, $course_id);
        if ($has_permission) {
          $account = User::load($user_id);
          if ($account) {
            return $account->hasPermission('access lesson detail');
          }
        }
      }
    }
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function checkQuizPermission($user_id, $quiz_id) {
    if (!empty($quiz_id)) {
      if ($this->checkPermissionByUid($user_id)) {
        return TRUE;
      }
      $courses = $this->entityTypeManager->getStorage('commerce_product')->loadByProperties(['field_quiz' => $quiz_id]);
      if (!empty($courses)) {
        foreach ($courses as $course) {
          $has_permission = $this->checkCoursePermission($user_id, $course->id());
          if ($has_permission) {
            return TRUE;
          }
        }
      }
    }
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function checkQuizPermissionWithCourse($user_id, $quiz_id, $course_id) {
    if (!empty($quiz_id)) {
      if ($this->checkPermissionByUid($user_id)) {
        return TRUE;
      }
      $has_permission = $this->checkCoursePermission($user_id, $course_id);
      if ($has_permission) {
        return TRUE;
      }
    }
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function checkCoursePermission($user_id, $course_id) {
    if (!empty($course_id)) {
      if ($this->checkPermissionByUid($user_id)) {
        return TRUE;
      }
      $course = $this->entityTypeManager->getStorage('commerce_product')->load($course_id);
      /** @var \Drupal\commerce_product\Entity\Product $course */
      if (!empty($course)) {
        if ($course->hasField('field_free')) {
          $free = $course->get('field_free')->value;
          if ($free) {
            return TRUE;
          }
        }
        if ($course && $course->hasField('field_teacher') && !$course->get('field_teacher')->isEmpty()) {
          $teacher_id = $course->get('field_teacher')->target_id;
          if ($teacher_id == $user_id) {
            return TRUE;
          }
        }
        $variations = $course->getVariationIds();
        if (!empty($variations)) {
          $orders = $this->entityTypeManager->getStorage('commerce_order')
            ->loadByProperties([
              'uid' => $user_id,
              'state' => 'completed',
            ]);
          foreach ($orders as $order) {
            $commerce_order_items = $order->get('order_items')->referencedEntities();
            foreach ($commerce_order_items as $order_item) {
              $product_id = $order_item->getPurchasedEntityId();
              if (in_array($product_id, $variations)) {
                return TRUE;
              }
            }
          }
        }
      }
    }
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function checkPermissionByUid($user_id) {
    $user = User::load($user_id);
    if ($user && $user->isAuthenticated() && ($user->hasRole('administrator') || $user->hasRole('membership'))) {
      return TRUE;
    }
    return FALSE;
  }


  /**
   * {@inheritdoc}
   */
  public function checkRole(User $user, LMSLesson $lesson) {
    if ($user->isAuthenticated() && ($user->hasRole('teacher') || $user->hasRole('student'))) {
      if ($user->hasRole('teacher')) {
        $query = \Drupal::entityQuery('commerce_product')
          ->accessCheck(FALSE)
          ->condition('type', 'course')
          ->condition('field_lessons.target_id', $lesson->id());
        $product_ids = $query->execute();
        $products = Product::loadMultiple($product_ids);

        if (!empty($products)) {
          $product = reset($products);
          $teacher_id = $product->get('field_teacher')->getString();

          if ($teacher_id == $user->id()) {
            $role = 1;
          }
        }
      } elseif ($user->hasRole('student')) {
        $role = 0;
      }
      return $role;
    }
  }
}
