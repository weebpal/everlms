<?php

namespace Drupal\lms_user_course\Controller;

use Drupal;
use Drupal\user\UserInterface;
use Drupal\Core\Access\AccessResult;
use Drupal\commerce_product\Entity\ProductInterface;

class LMSUserCourseAccessController {

  /**
   * Check access of start user course callback
   * @return \Drupal\Core\Access\AccessResultInterface
   * The access result.
   */
  public function access(ProductInterface $commerce_product, UserInterface $user) {
    $product_id = $commerce_product->id();
    $user_id = $user->id();
    $permissionManager = \Drupal::service('lms_base.permission_manager');
    $userCourseManager = \Drupal::service('lms_user_course.manager');
    $has_permission = $permissionManager->checkCoursePermission($user_id, $product_id);
    $user_course = $userCourseManager->getUserCourseExist($product_id, $user_id);
    if ($has_permission && !$user_course) {
      return AccessResult::allowed();
    }
    return AccessResult::forbidden();
  }
}
