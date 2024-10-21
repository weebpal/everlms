<?php

namespace Drupal\lms_lesson\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\InvokeCommand;
use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\entity\Form\EntityDuplicateFormTrait;
use Drupal\lms_room\Entity\LMSRoom;
use Drupal\user\Entity\User;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LMSLessonForm extends ContentEntityForm {

  use EntityDuplicateFormTrait;

  /**
   * The date formatter.
   *
   * @var \Drupal\Core\Datetime\DateFormatterInterface
   */
  protected $dateFormatter;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->dateFormatter = $container->get('date.formatter');
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);
    /** @var \Drupal\lms_lesson\Entity\LMSLessonInterface $lms_lesson */
    $lms_lesson = $this->entity;
    $form['#theme'] = ['lms_lesson_form'];
    $form['#attached']['library'][] = 'lms_lesson/form';
    $changed = $lms_lesson->getChangedTime();
    $form['changed'] = [
      '#type' => 'hidden',
      '#default_value' => $changed,
    ];
    if ($changed) {
      $last_saved = $this->dateFormatter->format($changed, 'short');
    } else {
      $last_saved = $lms_lesson->isNew() ? $this->t('Not saved yet') : $this->t('N/A');
    }

    $form['advanced'] = [
      '#type' => 'container',
      '#attributes' => ['class' => ['entity-meta']],
      '#weight' => 99,
    ];
    $form['meta'] = [
      '#attributes' => ['class' => ['entity-meta__header']],
      '#type' => 'container',
      '#group' => 'advanced',
      '#title' => $this->t('Authoring information'),
      '#weight' => 90,
      'published' => [
        '#type' => 'html_tag',
        '#tag' => 'h3',
        '#value' => '',
        // '#access' => $lms_lesson->isDefault(),
        '#attributes' => [
          'class' => ['entity-meta__title'],
        ],
      ],
      'changed' => [
        '#type' => 'item',
        '#wrapper_attributes' => [
          'class' => ['entity-meta__last-saved', 'container-inline'],
        ],
        '#markup' => '<h4 class="label inline">' . $this->t('Last saved') . '</h4> ' . $last_saved,
      ],
      'owner' => [
        '#type' => 'item',
        '#wrapper_attributes' => [
          'class' => ['author', 'container-inline'],
        ],
        '#markup' => '<h4 class="label inline">' . $this->t('Owner') . '</h4> ' . $lms_lesson->getOwner()->getDisplayName(),
      ],
    ];
    if (isset($form['uid'])) {
      $form['uid']['#group'] = 'author';
    }
    if (isset($form['created'])) {
      $form['created']['#group'] = 'author';
    }

    $form['path_settings'] = [
      '#type' => 'details',
      '#title' => $this->t('URL path settings'),
      '#open' => !empty($form['path']['widget'][0]['alias']['#default_value']),
      '#group' => 'advanced',
      '#access' => !empty($form['path']['#access']) && $lms_lesson->get('path')->access('edit'),
      '#attributes' => [
        'class' => ['path-form'],
      ],
      '#attached' => [
        'library' => ['path/drupal.path'],
      ],
      '#weight' => 91,
    ];
    $form['path']['#group'] = 'path_settings';


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
      $form['field_embedded_video']['#states'] = [
        'visible' => [
          ':input[name="field_lesson_type"]' => ['value' => $type_embed->id()],
        ],
      ];
    }

    if ($type_zoom) {
      $type_zoom = reset($type_zoom);
      $form['field_zoom_class_link']['#states'] = [
        'visible' => [
          ':input[name="field_lesson_type"]' => ['value' => $type_zoom->id()],
        ],
      ];
      $form['field_zoom_class_data']['#states'] = [
        'visible' => [
          ':input[name="field_lesson_type"]' => ['value' => $type_zoom->id()],
        ],
      ];
      $form['field_zoom_class']['#states'] = [
        'visible' => [
          ':input[name="field_lesson_type"]' => ['value' => $type_zoom->id()],
        ],
      ];
      $form['btn_create_zoom_class'] = [
        '#type' => 'button',
        '#value' => "Create zoom class room",
        '#ajax' => [
          'callback' => [$this, 'createZoomMeeting'],
          'event' => 'click',
          'wrapper' => 'container-wrapper',
        ],
        '#states' => [
          'visible' => [
            ':input[name="field_lesson_type"]' => ['value' => $type_zoom->id()],
          ],
        ],
        '#weight' => $form['field_class_time']['#weight'],
      ];
    }

    if ($type_google) {
      $type_google = reset($type_google);
      $form['field_google_meet_link']['#states'] = [
        'visible' => [
          ':input[name="field_lesson_type"]' => ['value' => $type_google->id()],
        ],
      ];
      $form['field_google_meet_data']['#states'] = [
        'visible' => [
          ':input[name="field_lesson_type"]' => ['value' => $type_google->id()],
        ],
      ];
      $form['btn_create_google_meet'] = [
        '#type' => 'button',
        '#value' => "Create google meet room",
        '#ajax' => [
          'callback' => [$this, 'createGoogleMeeting'],
          'event' => 'click',
          'wrapper' => 'container-wrapper',
        ],
        '#states' => [
          'visible' => [
            ':input[name="field_lesson_type"]' => ['value' => $type_google->id()],
          ],
        ],
        '#weight' => $form['field_class_time']['#weight'],
      ];
    }

    if ($type_google && $type_zoom) {
      $form['field_class_time']['#states'] = [
        'visible' => [
          [':input[name="field_lesson_type"]' => ['value' => $type_google->id()]],
          'or',
          [':input[name="field_lesson_type"]' => ['value' => $type_zoom->id()]],
        ],
      ];
    }

    $form['container'] = [
      '#type' => 'container',
      '#attributes' => [
        'id' => 'container-wrapper',
      ],
      '#weight' => 4,
    ];

    $form['#attached']['library'][] = 'lms_lesson/lms_lesson.add_form';
    // dump($form);
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $this->entity->save();
    $this->postSave($this->entity, $this->operation);
    $this->messenger()->addMessage($this->t('Saved the %label lms_lesson.', [
      '%label' => $this->entity->label(),
    ]));
    $form_state->setRedirect('entity.lms_lesson.collection');
  }

  /**
   * @inheritDoc
   */
  public function selectLessonType(array &$form, FormStateInterface $form_state) {
    return $form['container'];
  }

  /**
   * @inheritDoc
   */
  public function createGoogleMeeting(array &$form, FormStateInterface $form_state) {
    return $form['container'];
  }

  /**
   * @inheritDoc
   */
  public function createZoomMeeting(array &$form, FormStateInterface $form_state) {
    $response = new AjaxResponse();
    $values = $form_state->getValues();
    $room_class_id = $values['field_zoom_class']['0']['target_id'];
    if (!empty($room_class_id)) {
      $room = LMSRoom::load($room_class_id);
      $user = User::load($room->get('field_host')->getString());
      if (!empty($user)) {
        $client = \Drupal::service('zoom_integration.client');
        $user_zoom = $client->getUserByEmail($user->getEmail());
        if (!empty($user_zoom)) {
          $start_time = $values['field_class_time'][0]['value'];
          $id = $user_zoom['id'];
          if ($start_time) {
            $compareDateTime = strtotime($start_time['date'] . 'T'.$start_time['time']);
            $data = $client->request(
              "POST",
              "/users/$id/meetings",
              [],
              ["start_time" => date('Y-m-d\TH:i:s\Z', $compareDateTime)]
            );
            $response->addCommand(new InvokeCommand(NULL, 'setValue', [$data]));
          }
        }
      }
    }

    return $response;
  }
}
