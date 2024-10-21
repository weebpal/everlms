<?php

namespace Drupal\lms_quiz_result;

use Drupal;
use Drupal\commerce_product\Entity\Product;
use Drupal\lms_user_course\Entity\LMSUserCourse;
use Drupal\Core\Entity\EntityTypeManagerInterface;

class LMSQuizResultManager implements LMSQuizResultManagerInterface {

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
   * @inheritDoc
   */
  public function getQuizResultByUid(string $uid, string $type = 'id') {
    $quiz_result_ids = Drupal::entityQuery('lms_quiz_result')
      ->condition('type', 'default')
      ->condition('field_taker', $uid)
      ->condition('status', $uid)
      ->accessCheck(FALSE)
      ->execute();
    if ($quiz_result_ids) {
      if ($type == 'id') {
        return $quiz_result_ids;
      } elseif ($type == 'target_id') {
        $values = [];
        foreach ($quiz_result_ids as $quiz_result_id) {
          $values[] = ['target_id' => $quiz_result_id];
        }
        return $values;
      }
      $quiz_results = $this->entityTypeManager()->getStorage('lms_quiz_result')->loadMultiple($quiz_result_ids);
      return $quiz_results;
    }
    return [];
  }
}
