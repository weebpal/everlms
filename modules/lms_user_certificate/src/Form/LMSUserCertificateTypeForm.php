<?php

namespace Drupal\lms_user_certificate\Form;

use Drupal\Core\Entity\BundleEntityFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\entity\Form\EntityDuplicateFormTrait;
use Drupal\language\Entity\ContentLanguageSettings;

class LMSUserCertificateTypeForm extends BundleEntityFormBase {

  use EntityDuplicateFormTrait;

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);
    /** @var \Drupal\lms_user_certificate\Entity\LMSUserCertificateTypeInterface $lms_user_certificate_type */
    $lms_user_certificate_type = $this->entity;

    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $lms_user_certificate_type->label(),
      '#required' => TRUE,
    ];
    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $lms_user_certificate_type->id(),
      '#machine_name' => [
        'exists' => '\Drupal\lms_user_certificate\Entity\LMSUserCertificateType::load',
      ],
      '#maxlength' => EntityTypeInterface::BUNDLE_MAX_LENGTH,
      '#disabled' => !$lms_user_certificate_type->isNew(),

    ];
    $form['description'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Description'),
      '#description' => $this->t('This text will be displayed on the <em>Add user certificate item</em> page.'),
      '#default_value' => $lms_user_certificate_type->getDescription(),
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
          'entity_type' => 'lms_user_certificate',
          'bundle' => $lms_user_certificate_type->id(),
        ],
        '#default_value' => ContentLanguageSettings::loadByEntityTypeBundle('lms_user_certificate', $lms_user_certificate_type->id()),
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

    $this->messenger()->addMessage($this->t('Saved the %label lms_user_certificate type.', [
      '%label' => $this->entity->label(),
    ]));
    $form_state->setRedirect('entity.lms_user_certificate_type.collection');
  }

}