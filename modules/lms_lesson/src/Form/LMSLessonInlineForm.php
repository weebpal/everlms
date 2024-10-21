<?php

namespace Drupal\lms_lesson\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\InvokeCommand;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Form\FormStateInterface;
use Drupal\inline_entity_form\Form\EntityInlineForm;

/**
 * Defines the inline form for order items.
 */
class LMSLessonInlineForm extends EntityInlineForm {

  /**
   * {@inheritdoc}
   */
  public function entityForm(array $entity_form, FormStateInterface $form_state) {
    $entity_form = parent::entityForm($entity_form, $form_state);
    $field_lesson_type_parents = $entity_form['field_lesson_type']['widget']['#parents'];
    $root_region = array_shift($field_lesson_type_parents);
    $name_field_lesson_type = $root_region . '[' . implode('][', $field_lesson_type_parents) . ']';

    $type_embed = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties([
      'vid' => 'lesson_type',
      'field_key' => 'embedded_video',
    ]);

    $type_google = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties([
      'vid' => 'lesson_type',
      'field_key' => 'google_meet',
    ]);

    $type_zoom = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties([
      'vid' => 'lesson_type',
      'field_key' => 'zoom_class',
    ]);

    if ($type_embed) {
      $type_embed = reset($type_embed);
      $entity_form['field_embedded_video']['widget'][0]['value']['#states'] = [
        'visible' => [
          ":input[name='$name_field_lesson_type']" => ['value' => $type_embed->id()],
        ],
      ];
    }

    if ($type_zoom) {
      $type_zoom = reset($type_zoom);
      $entity_form['field_zoom_class_link']['widget'][0]['uri']['#states'] = [
        'visible' => [
          ":input[name='$name_field_lesson_type']" => ['value' => $type_zoom->id()],
        ],
      ];
      $entity_form['field_zoom_class_data']['widget'][0]['value']['#states'] = [
        'visible' => [
          ":input[name='$name_field_lesson_type']" => ['value' => $type_zoom->id()],
        ],
      ];
      $entity_form['field_zoom_class']['widget']['#states'] = [
        'visible' => [
          ":input[name='$name_field_lesson_type']" => ['value' => $type_zoom->id()],
        ],
      ];

      $entity_form['btn_create_zoom_class'] = [
        '#type' => 'container',
        'btn_create' => [
          '#type' => 'markup',
          '#markup' => '<div class="button-create-zoom-class">'.t('Create zoom class room').'</div>',
        ],
        '#states' => [
          'visible' => [
            ":input[name='$name_field_lesson_type']" => ['value' => $type_zoom->id()],
          ],
        ],
        '#weight' => $entity_form['field_class_time']['#weight'],
      ];
    }

    if ($type_google) {
      $type_google = reset($type_google);
      $entity_form['field_google_meet_link']['widget'][0]['uri']['#states'] = [
        'visible' => [
          ":input[name='$name_field_lesson_type']" => ['value' => $type_google->id()],
        ],
      ];
      $entity_form['field_google_meet_data']['widget'][0]['value']['#states'] = [
        'visible' => [
          ":input[name='$name_field_lesson_type']" => ['value' => $type_google->id()],
        ],
      ];
      $entity_form['btn_create_google_meet'] = [
        '#type' => 'container',
        'btn_create' => [
          '#type' => 'markup',
          '#markup' => '<div class="button-create-google-meet-class">'.t('Create google meet room').'</div>',
        ],
        '#states' => [
          'visible' => [
            ":input[name='$name_field_lesson_type']" => ['value' => $type_google->id()],
          ],
        ],
        '#weight' => $entity_form['field_class_time']['#weight'],
      ];
    }

    if ($type_google && $type_zoom) {
      $entity_form['field_class_time']['widget'][0]['#states'] = [
        'visible' => [
          [":input[name='$name_field_lesson_type']" => ['value' => $type_google->id()]],
          'or',
          [":input[name='$name_field_lesson_type']" => ['value' => $type_zoom->id()]],
        ],
      ];

      $entity_form['field_zoom_class']['widget']['#attributes']['class'][] = 'zoom-class--wrapper';
      $entity_form['field_class_time']['widget'][0]['value']['#attributes']['class'][] = 'date-time-value--wrapper';
      $entity_form['field_class_time']['widget'][0]['end_value']['#attributes']['class'][] = 'date-time-end-value--wrapper';
    }

    return $entity_form;
  }

  /**
   * {@inheritdoc}
   */
  public function entityFormSubmit(array &$entity_form, FormStateInterface $form_state) {
    parent::entityFormSubmit($entity_form, $form_state);
  }
}
