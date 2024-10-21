<?php


namespace Drupal\lms_quiz;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\State\StateInterface;
use Drupal\lms_quiz\Entity\LMSQuiz;
use Drupal\user\Entity\User;

class LMSQuizManager implements LMSQuizManagerInterface {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected EntityTypeManagerInterface $entityTypeManager;

  /**
   * @var \Drupal\Core\State\StateInterface
   */
  protected StateInterface $state;

  /**
   * @var \Drupal\Core\Database\Connection
   */
  protected Connection $database;

  /**
   * @var \Drupal\Core\Logger\LoggerChannelFactoryInterface
   */
  protected LoggerChannelFactoryInterface $loggerChannelFactory;

  /**
   * Constructs a new User Permission service.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, StateInterface $state, Connection $database, LoggerChannelFactoryInterface $logger_channel_factory) {
    $this->entityTypeManager = $entity_type_manager;
    $this->state = $state;
    $this->database = $database;
    $this->loggerChannelFactory = $logger_channel_factory;
  }

  /**
   * {@inheritdoc}
   */
  public function getQuizResultExist(string $quiz_id, string $uid) {
    $quiz_results = \Drupal::entityTypeManager()
      ->getStorage('lms_quiz_result')
      ->loadByProperties([
        'type' => 'default',
        'field_taker' => $uid,
        'field_quiz' => $quiz_id,
      ]);
    if ($quiz_results) {
      return reset($quiz_results);
    }
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function submitQuizExpired() {
    $logger = $this->loggerChannelFactory->get('lms_quiz');
    $lmsQuizCreate = \Drupal::service('lms_quiz.create');
    $query = $this->database->query("SELECT name FROM key_value WHERE collection='state' and name LIKE 'lms_quiz_state%'");
    $quiz_states = $query->fetchAllAssoc('name');
    foreach ($quiz_states as $key => $value) {
      if (isset($value->name)) {
        $name = $value->name;
        $data = $this->state->get($name);
        $quiz_states = $data ? Json::decode($data) : [];
        if ($quiz_states) {
          $quiz_id = $quiz_states['quiz_id'] ?? NULL;
          $uid = $quiz_states['uid'] ?? NULL;
          $start_time = $quiz_states['start_time'] ?? NULL;
          $end_time = $quiz_states['end_time'] ?? NULL;
          $form_values = $quiz_states['form_values'] ?? [];
          if (!empty($end_time) && $end_time > time()) {
            if (!empty($quiz_id) && !empty($uid) && !empty($start_time) && !empty($form_values)) {
              $quiz = LMSQuiz::load($quiz_id);
              if ($quiz) {
                $user = User::load($uid);
                if ($quiz->hasField('field_questions') && !$quiz->get('field_questions')
                    ->isEmpty()) {
                  $questions = $quiz->get('field_questions')
                    ->referencedEntities();
                  $lmsQuizCreate->createQuizResult($quiz, $questions, $form_values, $user, $start_time, $end_time);
                  $logger->notice(t('Submit quiz of state: @state', ['@state' => $name]));
                }
              }
            }
            $this->state->delete($name);
            $logger->notice(t('Delete quiz of state: @state', ['@state' => $name]));
          }
          elseif (empty($end_time)) {
            $this->state->delete($name);
            $logger->notice(t('Delete quiz of state: @state', ['@state' => $name]));
          }
        }
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function deleteQuizStateAnonymous() {
    $logger = $this->loggerChannelFactory->get('lms_quiz');
    $query = $this->database->query("SELECT name FROM key_value WHERE collection='state' and name LIKE 'lms_quiz_state%'");
    $quiz_states = $query->fetchAllAssoc('name');
    foreach ($quiz_states as $key => $value) {
      if (isset($value->name)) {
        $name = $value->name;
        if (str_contains($name, 'lms_quiz_state_0_')) {
          $this->state->delete($name);
          $logger->notice(t('Delete quiz of state: @state', ['@state' => $name]));
        }
      }
    }
  }

}
