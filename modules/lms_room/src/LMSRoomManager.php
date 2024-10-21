<?php

namespace Drupal\lms_room;

use Drupal;
use Drupal\Core\Url;
use Drupal\commerce_product\Entity\Product;
use Drupal\lms_base\LMSBaseManagerInterface;
use Drupal\lms_room\Entity\LMSRoom;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\lms_quiz_result\LMSQuizResultManagerInterface;
use Drupal\lms_room\Entity\LMSRoomInterface;
use Drupal\lms_base\PermissionManagerInterface;

class LMSRoomManager implements LMSRoomManagerInterface {

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
}
