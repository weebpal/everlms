<?php

function lms_theme_form_system_theme_settings_alter(&$form, &$form_state) {
  $form['skin'] = [
    '#type' => 'details',
    '#title' => t('Skin'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
    '#group' => 'tabs',
    'skin_type' => [
      '#type' => 'select',
      '#title' => t('Select Skin Type'),
      '#description' => t('Change Theme Color.'),
      '#default_value' => theme_get_setting('skin_type', 'lms_theme'),
      '#options' => [
        'everlms-theme-green' => t('Green'),
        'everlms-theme-beauty' => t('Beauty'),
        'everlms-theme-purple' => t('Purple'),
        'everlms-theme-blue' => t('Blue'),
        'everlms-theme-bartender-green' => t('bartender Green'),
        'everlms-theme-bartender-orange' => t('Bartender Orange'),
        'everlms-theme-badminton-cyan' => t('Badminton Cyan'),
        'everlms-theme-badminton-green' => t('Badminton Green'),
        'everlms-theme-language-green' => t('Language Green'),
      ],
    ]
  ];
}
