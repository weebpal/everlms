<?php

namespace Drupal\lms_quiz_result\Form;

use Drupal\Core\Entity\BundleEntityFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\entity\Form\EntityDuplicateFormTrait;
use Drupal\language\Entity\ContentLanguageSettings;

class LMSQuizResultTypeForm extends BundleEntityFormBase {

  use EntityDuplicateFormTrait;

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);
    /** @var \Drupal\lms_quiz_result\Entity\LMSQuizResultTypeInterface $lms_quiz_result_type */
    $lms_quiz_result_type = $this->entity;

    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $lms_quiz_result_type->label(),
      '#required' => TRUE,
    ];
    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $lms_quiz_result_type->id(),
      '#machine_name' => [
        'exists' => '\Drupal\lms_quiz_result\Entity\LMSQuizResultType::load',
      ],
      '#maxlength' => EntityTypeInterface::BUNDLE_MAX_LENGTH,
      '#disabled' => !$lms_quiz_result_type->isNew(),

    ];
    $form['description'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Description'),
      '#description' => $this->t('This text will be displayed on the <em>Add quiz result item</em> page.'),
      '#default_value' => $lms_quiz_result_type->getDescription(),
    ];

    if ($this->moduleHandler->moduleExists('language')) {
      $form['language'] = [
        '#type' => 'details',
        '#title' => $this->t('Language settings'),
        '#group' => 'additional_settings',
      ];
      $form['language']['language_configuration'] = [
        '#type' => 'language_configuration',
        '#entity_information' => [
          'entity_type' => 'lms_quiz_result',
          'bundle' => $lms_quiz_result_type->id(),
        ],
        '#default_value' => ContentLanguageSettings::loadByEntityTypeBundle('lms_quiz_result', $lms_quiz_result_type->id()),
      ];
      $form['#submit'][] = 'language_configuration_element_submit';
    }

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $this->entity->save();

    $this->messenger()->addMessage($this->t('Saved the %label lms_quiz_result type.', [
      '%label' => $this->entity->label(),
    ]));
    $form_state->setRedirect('entity.lms_quiz_result_type.collection');
  }

}
