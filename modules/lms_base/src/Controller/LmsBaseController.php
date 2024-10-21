<?php

namespace Drupal\lms_base\Controller;

use Drupal\commerce_order\Entity\Order;
use Drupal\Core\Controller\ControllerBase;
use Drupal\lms_certificate\Entity\LMSCertificate;
use Drupal\lms_quiz_result\Entity\LMSQuizResult;
use Drupal\user\Entity\User;

class LmsBaseController extends ControllerBase {

  public function test() {
    $var =  user_role_permissions(['anonymous','authenticated','membership','student','teacher','content_editor','administrator','host_zoom_class']);
    dump($var);

    dump('lms we_notification');
    $storage = \Drupal::entityTypeManager()->getStorage('we_notification');
    $entities = $storage->loadMultiple($storage->getQuery()->accessCheck('FALSE')->execute());
    dump(count($entities));

    dump('lms lms_question_response');
    $storage = \Drupal::entityTypeManager()->getStorage('lms_question_response');
    $entities = $storage->loadMultiple($storage->getQuery()->accessCheck('FALSE')->execute());
    dump(count($entities));

    dump('lms quiz_result');
    $storage = \Drupal::entityTypeManager()->getStorage('lms_quiz_result');
    $entities = $storage->loadMultiple($storage->getQuery()->accessCheck('FALSE')->execute());
    dump(count($entities));

    dump('lms quiz_result');
    $storage = \Drupal::entityTypeManager()->getStorage('lms_quiz_result');
    $entities = $storage->loadMultiple($storage->getQuery()->accessCheck('FALSE')->execute());
    dump(count($entities));

    dump('lms user_ceritificate');
    $storage = \Drupal::entityTypeManager()->getStorage('lms_user_certificate');
    $entities = $storage->loadMultiple($storage->getQuery()->accessCheck('FALSE')->execute());
    dump(count($entities));


    dump('lms user_course');
    $storage = \Drupal::entityTypeManager()->getStorage('lms_user_course');
    $entities = $storage->loadMultiple($storage->getQuery()->accessCheck('FALSE')->execute());
    dump(count($entities));


    dump('lms scheduler');
    $storage = \Drupal::entityTypeManager()->getStorage('lms_scheduler');
    $entities = $storage->loadMultiple($storage->getQuery()->accessCheck('FALSE')->execute());
    dump(count($entities));

    dump('lms Room');
    $storage = \Drupal::entityTypeManager()->getStorage('lms_room');
    $entities = $storage->loadMultiple($storage->getQuery()->accessCheck('FALSE')->execute());
    dump(count($entities));

    dump('lms Room');
    $storage = \Drupal::entityTypeManager()->getStorage('lms_room');
    $entities = $storage->loadMultiple($storage->getQuery()->accessCheck('FALSE')->execute());
    dump(count($entities));

    dump('lms answer');
    $storage = \Drupal::entityTypeManager()->getStorage('lms_answer');
    $entities = $storage->loadMultiple($storage->getQuery()->accessCheck('FALSE')->execute());
    dump(count($entities));

//    dump('lms class');
//    $storage = \Drupal::entityTypeManager()->getStorage('lms_class');
//    $entities = $storage->loadMultiple($storage->getQuery()->accessCheck('FALSE')->execute());
//    dump(count($entities));

    dump('lms lesson');
    $storage = \Drupal::entityTypeManager()->getStorage('lms_lesson');
    $entities = $storage->loadMultiple($storage->getQuery()->accessCheck('FALSE')->execute());
    dump(count($entities));

    dump('lms questions');
    $storage = \Drupal::entityTypeManager()->getStorage('lms_question');
    $entities = $storage->loadMultiple($storage->getQuery()->accessCheck('FALSE')->execute());
    dump(count($entities));

    dump('Entity Quiz');
    $storage = \Drupal::entityTypeManager()->getStorage('lms_quiz');
    $entities = $storage->loadMultiple($storage->getQuery()->accessCheck('FALSE')->execute());
    dump(count($entities));
    //    $services = \Drupal::service('lms_quiz.quiz_manager');
    //    $services->submitQuizExpired();
    //        $services_membership = \Drupal::service('lms_membership.membership_manager');
    //    $services_membership = 'q';
    //        dump($services_membership);
    //    $order = Order::load(33);
    //        $profile = $services_membership->createMembership( $order);
    // $type = [
    //   'lms_quiz_result',
    //   'lms_question_response',
    //   'lms_user_certificate',
    //   'lms_user_course'
    // ];
    // foreach ($type as $value){
    //   $user_ceritificate = \Drupal::entityTypeManager()->getStorage($value)->loadMultiple();
    //   foreach ($user_ceritificate as $item){
    //     $item->delete();
    //   }
    // }

    exit();
  }
}
