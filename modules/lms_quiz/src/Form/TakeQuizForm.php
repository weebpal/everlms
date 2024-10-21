<?php

namespace Drupal\lms_quiz\Form;

use Drupal;
use Drupal\Component\Serialization\Json;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\InvokeCommand;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Url;
use Drupal\lms_base\PermissionManager;
use Drupal\lms_base\PermissionManagerInterface;
use Drupal\lms_quiz\LMSQuizManager;
use Drupal\lms_quiz\LMSQuizManagerInterface;
use Drupal\lms_quiz\Service\Create;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class TakeQuizForm extends FormBase {

  /**
   * The route match.
   *
   * @var \Drupal\Core\Routing\RouteMatchInterface
   */
  protected $routeMatch;

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface;
   */
  protected $entityTypeManager;

  /**
   * The current user
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

  /**
   * @var \Drupal\lms_quiz\Service\Create
   */
  protected Create $createQuizService;

  /**
   * @var \Drupal\lms_base\PermissionManagerInterface
   */
  protected PermissionManagerInterface $permissionManager;

  protected $quiz;

  protected $questions;

  protected $start_time;

  protected $end_time;

  protected $current_question;

  protected $form_quiz_style;

  protected bool $count_down;

  /**
   * @var \Drupal\lms_quiz\LMSQuizManagerInterface
   */
  protected $LMSQuizManager;

  /**
   * Constructs a new TakeQuiz object.
   *
   * @param \Drupal\Core\Session\AccountInterface $current_user
   *   The current user.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\Core\Routing\RouteMatchInterface $route_match
   *   The router match
   */
  public function __construct(AccountInterface $current_user, EntityTypeManagerInterface $entity_type_manager, RouteMatchInterface $route_match, PermissionManagerInterface $permission_manager, Create $create_quiz_service, LMSQuizManagerInterface $lms_quiz_manager) {
    $this->currentUser = $current_user;
    $this->entityTypeManager = $entity_type_manager;
    $this->routeMatch = $route_match;
    $this->permissionManager = $permission_manager;
    $this->createQuizService = $create_quiz_service;
    $this->LMSQuizManager = $lms_quiz_manager;
    $this->count_down = FALSE;

    $this->quiz = $this->routeMatch->getParameter('lms_quiz');

    if ($this->quiz) {
      $this->questions = $this->quiz->get('field_questions')->referencedEntities();
      $this->setFormStyle();
    } else {
      $this->questions = [];
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('current_user'),
      $container->get('entity_type.manager'),
      $container->get('current_route_match'),
      $container->get('lms_base.permission_manager'),
      $container->get('lms_quiz.create'),
      $container->get('lms_quiz.quiz_manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'quiz_form';
  }

  /**
   * Checks access for a specific request.
   *
   * @return \Drupal\Core\Access\AccessResultInterface
   *   The access result.
   */
  public function titleCallback() {
    return $this->quiz->label();
  }

  /**
   * Checks access for a specific request.
   *
   * @return \Drupal\Core\Access\AccessResultInterface
   *   The access results.
   */
  public function access() {
    if ($this->currentUser()->isAnonymous()) {
      return AccessResult::forbidden();
    }
    $has_permission = $this->permissionManager->checkQuizPermission($this->currentUser->id(), $this->quiz->id());

    return AccessResult::allowedIf($has_permission);
  }

  /**
   * @param string $uid
   * @param string $quiz_id
   * @param array $data
   */
  public function setQuizState(string $uid, string $quiz_id, array $data) {
    $key = 'lms_quiz_state_' . $uid . '_' . $quiz_id;
    \Drupal::state()->set($key, Json::encode($data));
  }

  /**
   * @param string $uid
   * @param string $quiz_id
   *
   * @return array|mixed
   */
  public function getQuizState(string $uid, string $quiz_id) {
    $key = 'lms_quiz_state_' . $uid . '_' . $quiz_id;
    $data = \Drupal::state()->get($key);
    return $data ? Json::decode($data) : [];
  }

  /**
   * @param string $uid
   * @param string $quiz_id
   */
  public function deleteQuizState(string $uid, string $quiz_id) {
    $key = 'lms_quiz_state_' . $uid . '_' . $quiz_id;
    \Drupal::state()->delete($key);
  }

  /**
   * @return $this
   */
  public function setFormStyle() {
    $this->form_quiz_style = 'one_page';
    /** @var \Drupal\taxonomy\Entity\Term $quiz_style */
    $quiz_style = $this->quiz->get('field_form_style')->entity;
    if ($quiz_style && $quiz_style->hasField('field_key')) {
      $this->form_quiz_style = $quiz_style->get('field_key')->getString();
    }
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $current_time = Drupal::time()->getCurrentTime();
    $quiz_id = $this->quiz->id();
    $uid = $this->currentUser->id();
    $userCourseManager = Drupal::service('lms_user_course.manager');
    $user_courses = $userCourseManager->getUserCourseByQuizId($quiz_id, $uid);
    if (!$user_courses) {
      $userCourseManager->createUserCourseByQuizId($quiz_id, $uid);
    }

    // Redirect to result if result exist
    $quiz_result = $this->LMSQuizManager->getQuizResultExist($quiz_id, $uid);
    if ($quiz_result) {
      $take_quiz_number = $this->quiz->get('field_take_quiz_number')->value;
      $quiz_take_time = $quiz_result->get('field_take_quiz_time')->value;

      if ($take_quiz_number <= $quiz_take_time) {
        $url = Url::fromRoute('lms_quiz.result_quiz', ['lms_quiz_result' => $quiz_result->id()])->toString();
        return new RedirectResponse($url);
      }
      $result = $quiz_result->get('field_result')->entity;
      if ($result) {
        $key = $result->get('field_key')->value;
        if ($key == 'passed') {
          $url = Url::fromRoute('lms_quiz.result_quiz', ['lms_quiz_result' => $quiz_result->id()])->toString();
          return new RedirectResponse($url);
        }
      }
    }

    $quiz_state = $this->getQuizState($uid, $quiz_id);

    $time_limit = $this->quiz->get('field_time_limit')->value;
    if ($quiz_state) {
      $this->start_time = $quiz_state['start_time'] ?? time();
      $this->end_time = $quiz_state['end_time'] ?? ($this->start_time + $time_limit * 60);
      $this->current_question = $quiz_state['step'] ?? 1;
      $this->count_down = $quiz_state['count_down'] ?? 1;
      // Submit when expired
      if ($this->end_time < time()) {
        $entity_quiz_result = $this->createQuizService->createQuizResult($this->quiz, $this->questions, $quiz_state['form_values'], $this->currentUser, $this->start_time, $this->end_time);
        $this->deleteQuizState($this->currentUser->id(), $this->quiz->id());
        $url = Url::fromRoute('lms_quiz.result_quiz', ['lms_quiz_result' => $entity_quiz_result->id()])->toString();
        return new RedirectResponse($url);
      }
    } else {
      $this->start_time = time();
      $this->end_time = $this->start_time + $time_limit * 60;
      $this->setQuizState($uid, $quiz_id, [
        'start_time' => $this->start_time,
        'end_time' => $this->end_time,
        'step' => 1,
        'uid' => $uid,
        'quiz_id' => $quiz_id,
      ]);
      $this->current_question = 1;
    }

    if (!$this->count_down) {
      $count_down = $this->countDownInit($this->start_time, $this->end_time);
      $this->count_down = TRUE;
    } else {
      $count_down = $this->countDownInit($current_time, $this->end_time);
    }
    $form['count_down_timer'] = [
      '#type' => 'markup',
      '#markup' => '<div class="count-down-timer--wrapper"><div class="count-down-label--wrapper"><div class="label">' . t('Time Limit') . ': </div><div class="timer">' . $count_down . '</div></div></div>',
    ];

    $form['quiz-form'] = [
      '#type' => 'container',
      '#attributes' => [
        'class' => ['take-quiz-form'],
      ],
      '#prefix' => '<div id="take-quiz">',
      '#suffix' => '</div>',
    ];
    $form['quiz-form']['actions'] = [
      '#type' => 'actions',
    ];
    $form['quiz-form']['#attached']['library'][] = 'lms_quiz/take-quiz';

    //one page
    if ($this->form_quiz_style == 'one_page') {
      foreach ($this->questions as $question_order => $field_question) {
        $answers = [];
        $field_choices = $field_question->get('field_choices')
          ->referencedEntities();

        foreach ($field_choices as $key => $field_choice) {
          $answers += [$key => $field_choice->name->value];
        }

        $form['quiz-form']['question_' . ($question_order)] = [
          '#type' => 'markup',
          '#size' => '255',
          '#markup' => '<label><h3>Question ' . ($question_order + 1) . ':</h3><p>' . $field_question->name->value . '</p></label>',
          '#prefix' => '<div id="question_' . $question_order . '">',
          '#suffix' => '</div>',
        ];
        $answer_key = 'answers_select_' . $question_order;
        $default_value = $quiz_state['form_values'] ? $quiz_state['form_values'][$answer_key] : NULL;
        $form['quiz-form']['answers_select_' . ($question_order)] = [
          '#type' => 'checkboxes',
          '#options' => $answers,
          '#default_value' => $default_value,
          '#prefix' => '<div id="answer_checkboxes_' . ($question_order) . '">',
          '#suffix' => '</div>',
        ];
        $form['quiz-form']['actions']['submit'] = [
          '#type' => 'button',
          '#button_type' => 'primary',
          '#value' => $this->t('Submit'),
          '#prefix' => '<div id="confirm_submit">',
          '#suffix' => '</div>',
        ];
        $form['quiz-form']['actions']['submit_confirmed'] = [
          '#type' => 'submit',
          '#attributes' => [
            'class' => ['submit-btn', 'hidden'],
          ],
        ];
      }
    }
    if ($this->form_quiz_style == 'question_step' && $this->current_question) {
      $answers = [];
      $question_order = $this->current_question - 1;
      $question = $this->questions[$question_order];

      $choices = $question->get('field_choices')->referencedEntities();
      foreach ($choices as $key => $choice) {
        $answers += [$key => $choice->name->value];
      }

      $form['quiz-form']['question_' . $question_order] = [
        '#type' => 'markup',
        '#size' => '255',
        '#markup' => '<label><h3>Question ' . ($question_order + 1) . ':</h3><p>' . $question->name->value . '</p></label>',
        '#disabled' => TRUE,
        '#prefix' => '<div id="question_' . $question_order . '">',
        '#suffix' => '</div>',
      ];

      $answer_key = 'answers_select_' . $question_order;
      $default_value = $quiz_state['form_values'] ? $quiz_state['form_values'][$answer_key] : NULL;
      foreach ($default_value as $answer_id => $answer_value) {
        $default_value[$answer_id] = $answer_value !== 0 ? $answer_value : NULL;
      }
      $form['quiz-form']['answers_select_' . $question_order] = [
        '#type' => 'checkboxes',
        '#default_value' => $default_value,
        '#options' => $answers,
        '#prefix' => '<div id="answer_checkboxes_' . $question_order . '">',
        '#suffix' => '</div>',
      ];

      //action
      if ($question_order > 0) {
        $form['quiz-form']['actions']['back'] = [
          '#type' => 'submit',
          '#button_type' => 'primary',
          '#value' => $this->t('Back'),
          '#submit' => [[$this, 'backSubmit']],
          '#prefix' => '<div id="back_button">',
          '#suffix' => '</div>',
          '#ajax' => [
            'callback' => [$this, 'ajaxCallback'],
            'wrapper' => 'take-quiz',
          ],
        ];
      }

      if ($question_order < count($this->questions) - 1) {
        $form['quiz-form']['actions']['next'] = [
          '#type' => 'submit',
          '#button_type' => 'primary',
          '#value' => $this->t('Next'),
          '#submit' => [[$this, 'nextSubmit']],
          '#prefix' => '<div id="next_button">',
          '#suffix' => '</div>',
          '#ajax' => [
            'callback' => [$this, 'ajaxCallback'],
            'wrapper' => 'take-quiz',
          ],
        ];
      }

      if ($question_order == count($this->questions) - 1) {
        $form['quiz-form']['actions']['submit'] = [
          '#type' => 'button',
          '#button_type' => 'primary',
          '#value' => $this->t('Submit'),
          '#prefix' => '<div id="confirm_submit">',
          '#suffix' => '</div>',
        ];
        $form['quiz-form']['actions']['submit_confirmed'] = [
          '#type' => 'submit',
          '#attributes' => [
            'class' => ['submit-btn', 'hidden'],
          ],
        ];
      }
    }

    $form['#attached']['library'][] = 'lms_quiz/quiz_notification';
    $form['#attached']['drupalSettings']['start_time'] = $this->count_down ? $current_time : $this->start_time;
    $form['#attached']['drupalSettings']['end_time'] = $this->end_time;
    $form['#attached']['drupalSettings']['quiz_id'] = $this->quiz->id();
    $form['#attached']['drupalSettings']['uid'] = $this->currentUser->id();
    $form['#attached']['drupalSettings']['redirect_take_quiz_callback'] = Url::fromRoute('lms_quiz.redirect_take_quiz_callback')->toString();

    return $form;
  }

  /**
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return \Drupal\Core\Ajax\AjaxResponse
   */
  public function ajaxCallback(array &$form, FormStateInterface $form_state) {
    return $form['quiz-form'];
  }

  /**
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   */
  public function nextSubmit(array &$form, FormStateInterface $form_state) {
    $this->count_down = TRUE;
    $uid = $this->currentUser->id();
    $quiz_id = $this->quiz->id();
    $quiz_state = $this->getQuizState($uid, $quiz_id);
    if (!$quiz_state) {
      $quiz_state = [
        'uid' => $uid,
        'quiz_id' => $quiz_id,
        'count_count' => TRUE,
      ];
    }
    $quiz_state['step'] = $this->current_question + 1;
    $this->setQuizState($uid, $quiz_id, $quiz_state);
    $form_state->setRebuild(TRUE);
  }

  /**
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   */
  public function backSubmit(array &$form, FormStateInterface $form_state) {
    $this->count_down = TRUE;
    $uid = $this->currentUser->id();
    $quiz_id = $this->quiz->id();
    $quiz_state = $this->getQuizState($uid, $quiz_id);
    if (!$quiz_state) {
      $quiz_state = [
        'uid' => $uid,
        'quiz_id' => $quiz_id,
        'count_down' => TRUE,
      ];
    }
    $quiz_state['step'] = $this->current_question - 1;
    $this->setQuizState($uid, $quiz_id, $quiz_state);
    $form_state->setRebuild(TRUE);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
    if ($this->end_time >= time()) {
      $uid = $this->currentUser->id();
      $quiz_id = $this->quiz->id();
      $quiz_state = $this->getQuizState($uid, $quiz_id);
      if ($this->form_quiz_style == 'question_step') {
        $values = $form_state->getValues();
        $index = $this->current_question - 1;
        $answer_key = 'answers_select_' . $index;
        if (isset($values[$answer_key])) {
          $error = TRUE;
          foreach ($values[$answer_key] as $answer_value) {
            if ($answer_value !== 0) {
              $error = FALSE;
              break;
            }
          }
          if ($error) {
            $form_state->setErrorByName("answers_select_{$index}", "Question " . ($index + 1) . " can't be empty!");
          } else {
            $question_answer = $values[$answer_key];
            if ($question_answer) {
              $quiz_state['form_values'][$answer_key] = $question_answer;
            }
          }
        }
      } elseif ($this->form_quiz_style == 'one_page') {
        $values = $form_state->getValues();
        $field_question_count = count($this->questions);
        for ($index = 0; $index < $field_question_count; $index++) {
          $error = TRUE;
          $answer_key = 'answers_select_' . $index;
          foreach ($values[$answer_key] as $answer_value) {
            if ($answer_value !== 0) {
              $error = FALSE;
              break;
            }
          }
          if ($error) {
            $form_state->setErrorByName("answers_select_{$index}", "Question " . ($index + 1) . " can't be empty!");
          }
          $question_answer = $values[$answer_key];
          if ($question_answer) {
            $quiz_state['form_values'][$answer_key] = $question_answer;
          }
        }
      }
      $this->setQuizState($uid, $quiz_id, $quiz_state);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->end_time = time();
    $quiz_state = $this->getQuizState($this->currentUser->id(), $this->quiz->id());
    $quiz_result = $this->LMSQuizManager->getQuizResultExist($this->quiz->id(), $this->currentUser->id());
    if ($quiz_result) {
      $entity_quiz_result = $this->createQuizService->updateQuizResult($quiz_result, $this->quiz, $this->questions, $quiz_state['form_values'], $this->currentUser, $this->start_time, $this->end_time);
    } else {
      $entity_quiz_result = $this->createQuizService->createQuizResult($this->quiz, $this->questions, $quiz_state['form_values'], $this->currentUser, $this->start_time, $this->end_time);
    }
    $this->deleteQuizState($this->currentUser->id(), $this->quiz->id());
    $form_state->setRedirect('lms_quiz.result_quiz', ['lms_quiz_result' => $entity_quiz_result->id()]);
  }

  /**
   * @param $startTimestamp
   * @param $endTimestamp
   *
   * @return \Drupal\Core\StringTranslation\TranslatableMarkup|string
   */
  public function countDownInit($startTimestamp, $endTimestamp) {
    $remaining_time = $endTimestamp - $startTimestamp;
    if ($remaining_time >= 0) {
      $hours = floor($remaining_time / 3600);
      $minutes = floor(($remaining_time % 3600) / 60);
      $seconds = $remaining_time % 60;

      return $hours . 'h ' . $minutes . 'm ' . $seconds . 's';
    }
    return '0h 0m 0s';
  }
}
