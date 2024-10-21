<?php

namespace Drupal\zoom_integration\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * The config form.
 */
class ZoomIntegrationConfigForm extends ConfigFormBase {

  const CONFIG_NAME = 'zoom_integration.settings';

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'zoom_integration_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->getConfig();

    $form['access_token_uri'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Zoom API Get Access Token URL'),
      '#default_value' => $config->get('access_token_uri'),
      '#description' => $this->t('Do not include trailing slash. Ex. https://zoom.us/oauth/token'),
      '#required' => TRUE,
    ];

    $form['base_uri'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Zoom API Base URL'),
      '#default_value' => $config->get('base_uri'),
      '#description' => $this->t('Do not include trailing slash. Ex. https://api.zoom.us/v2'),
      '#required' => TRUE,
    ];

    $form['account_id'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Account ID'),
      '#default_value' => $config->get('account_id'),
      '#description' => $this->t('The Zoom API Account ID.'),
      '#required' => TRUE,
    ];

    $form['client_id'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Client ID'),
      '#default_value' => $config->get('client_id'),
      '#description' => $this->t('The Zoom API Client ID.'),
      '#required' => TRUE,
    ];

    $form['client_secret'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Client Secret'),
      '#default_value' => $config->get('client_secret'),
      '#description' => $this->t('The Zoom API Client secret.'),
      '#required' => TRUE,
    ];

    $form['app_client_id'] = [
      '#type' => 'textfield',
      '#title' => $this->t('App Client ID'),
      '#default_value' => $config->get('app_client_id'),
      '#description' => $this->t('App Credentials Client ID.'),
      '#required' => TRUE,
    ];

    $form['app_client_secret'] = [
      '#type' => 'textfield',
      '#title' => $this->t('App Client Secret'),
      '#default_value' => $config->get('app_client_secret'),
      '#description' => $this->t('App Credentials Client secret.'),
      '#required' => TRUE,
    ];

    $form['webhook_verification_token'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Webhook Verification Token'),
      '#default_value' => $config->get('webhook_verification_token'),
      '#description' => $this->t('Use this verification token to validate an event notification request from zoom.us for this app. Provided by Zoom.'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function getEditableConfigNames() {
    return [self::CONFIG_NAME];
  }

  /**
   * Returns this modules configuration object.
   */
  protected function getConfig() {
    return $this->config(self::CONFIG_NAME);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->getConfig();
    $values = $form_state->getValues();
    $config->set('access_token_uri', $values['access_token_uri']);
    $config->set('base_uri', $values['base_uri']);
    $config->set('account_id', $values['account_id']);
    $config->set('client_id', $values['client_id']);
    $config->set('client_secret', $values['client_secret']);
    $config->set('app_client_id', $values['app_client_id']);
    $config->set('app_client_secret', $values['app_client_secret']);
    $config->set('webhook_verification_token', $values['webhook_verification_token']);
    $config->save();
    parent::submitForm($form, $form_state);
  }
}
