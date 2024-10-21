<?php

namespace Drupal\lms_base;

use Drupal\user\Entity\User;
use Drupal\lms_lesson\Entity\LMSLesson;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Language\LanguageManagerInterface;

class LMSBaseManager implements LMSBaseManagerInterface {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The language manager.
   *
   * @var \Drupal\Core\Language\LanguageManagerInterface
   */
  protected $languageManager;

  /**
   * Constructs a MembershipTypeManager object.
   */
  public function __construct(EntityTypeManagerInterface $entityTypeManager, LanguageManagerInterface $languageManager) {
    $this->entityTypeManager = $entityTypeManager;
    $this->languageManager = $languageManager;
  }

  /**
   * @inheritdoc
   */
  public function getListTaxonomyByVid(string $vid, string $type = 'key') {
    $list = [];
    $termStorage = $this->entityTypeManager->getStorage('taxonomy_term');
    $termQuery = $termStorage->getQuery();
    $tids = $termQuery->condition('vid', $vid)
      ->condition('status', TRUE)
      ->accessCheck(FALSE)
      ->sort('weight', 'ASC')
      ->execute();
    if (!empty($tids)) {
      $terms = $termStorage->loadMultiple($tids);
      $langcode = $this->languageManager->getCurrentLanguage()->getId();
      foreach ($terms as $key => $term) {
        $term = $term->hasTranslation($langcode) ? $term->getTranslation($langcode) : $term;
        if ($type == 'key') {
          if ($term->hasField('field_key') && !$term->get('field_key')->isEmpty()) {
            $field_key_value = $term->get('field_key')->value;
            $list[$field_key_value] = $term;
          }
        } else {
          $list[$key] = $term;
        }
      }
    }
    return $list;
  }
}
