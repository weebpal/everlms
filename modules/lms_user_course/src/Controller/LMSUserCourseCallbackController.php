<?php

namespace Drupal\lms_user_course\Controller;

use Drupal;
use Drupal\Core\Url;
use Drupal\user\UserInterface;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;
use Drupal\lms_user_course\Entity\LMSUserCourse;
use Drupal\commerce_product\Entity\ProductInterface;
use Drupal\lms_user_course\Entity\LMSUserCourseType;
use Symfony\Component\HttpFoundation\RedirectResponse;

class LMSUserCourseCallbackController extends ControllerBase {

  /**
   * Start user course callback
   */
  public function startUserCourseCallback(ProductInterface $commerce_product, UserInterface $user, Request $request) {
    $product_id = $commerce_product->id();
    $user_id = $user->id();
    $userCourseManager = \Drupal::service('lms_user_course.manager');
    $user_course =  $userCourseManager->getUserCourseExist($product_id, $user_id);
    if ($user_course) {
        $userCourseManager->updateUserCourse($user_course);
    } else {
      $userCourseManager->createUserCourse($product_id, $user_id);
    }

    $destination = $request->query->get('destination', '<front>');
    if ($destination !== '<front>') {
      $url = Url::fromUserInput($destination)->toString();
      return new RedirectResponse($url);
    }
    return $this->redirect('<front>');
  }
}
