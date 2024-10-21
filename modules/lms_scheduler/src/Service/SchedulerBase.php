<?php

namespace Drupal\lms_scheduler\Service;

/**
 * Class SchedulerBase
 * @package Drupal\lms_scheduler\Service
 */
class SchedulerBase {

  public function generateSchedules($scheduler) {
    $data = $this->getSchedulerInfo($scheduler);
    $this->generateSchedulesWeekly($scheduler, $data);
  }

  public function getSchedulerInfo($entity) {
    if(empty($entity)) {
      return NULL;
    }
    $data = [];

    $data['body'] = $entity->get('field_body')->value;

    $days_of_the_week = array_column($entity->get('field_days_of_the_week')->getValue(), 'target_id');

    foreach($days_of_the_week as $key => $value) {
      $data['days_of_the_week'][$key] = $this->getTaxonomyTermKey('days_of_the_week', $value);
    }

    $time_frames = $entity->get('field_time_frames')->getValue();
    foreach($time_frames as $key => $value) {
      $data['time_frames'][$key] = [
        'from' => date("H:i:s", $value['from']), 
        'to' => date("H:i:s", $value['to']),
        'from_timestamp' => $value['from'],
        'to_timestamp' => $value['to'],
      ];
    }

    $data['between_dates'] = $entity->get('field_between_dates')->getValue();
    $data['timezone'] = \Drupal::config('system.date')->get('timezone')['default'];

    return $data;
  }

  public function generateSchedulesWeekly($scheduler, $data) {
    $field_calendar_type = $scheduler->get('field_day_type')->getString();
    $field_slot = $scheduler->get('field_slot')->getString();
    foreach($data['between_dates'] as $i => $value) {
      $from = isset($value['value']) ? $value['value'] : NULL;
      $to = isset($value['end_value']) ? $value['end_value'] : NULL;
      if($from && $to) {
        $from_timestamp = strtotime($from);
        $to_timestamp = strtotime($to . ' 23:59:59');

        $from_date = substr($from, 0, 10);
        $from_date_timestamp = strtotime($from_date);
        $to_date = substr($to, 0, 10);
        $to_date_timestamp = strtotime($to_date);

        $checking_date = $from_date;
        $checking_date_timestamp = strtotime($checking_date);
        $checking_date_weekly_value = date("l", $checking_date_timestamp);

        do {
          if(in_array($checking_date_weekly_value, $data['days_of_the_week'])) {
            foreach($data['time_frames'] as $j => $time_value) {
              $schedule_start = $checking_date . " " . $time_value['from'];
              $schedule_end = $checking_date . " " . $time_value['to'];
              $time_frame_start = $checking_date . "T" . $time_value['from'];
              $time_frame_end = $checking_date . "T" . $time_value['to'];

              $schedule_start_timestamp = strtotime($schedule_start);
              $schedule_end_timestamp = strtotime($schedule_end);
              if($this->isValidDateRange($from_timestamp, $to_timestamp, $schedule_start_timestamp, $schedule_end_timestamp)) {
                // Generate lesson by 
                // $checking_date
                // $time_frame_start
                // $time_frame_end
                $schedule_entity = $this->entityTypeManager->getStorage('eticket_schedule')->create([
                  'name' => t("Weekly schedule from @start to @end", [
                    '@start' => $schedule_start,
                    '@end' => $schedule_end,
                  ]),
                  'type' => 'default',
                  'field_scheduler' => $scheduler->id(),
                  'field_date' => $checking_date,
                  'field_time_frame' => [
                    'value'=> $time_frame_start,
                    'end_value' => $time_frame_end
                  ],
                ]);                      
                $schedule_entity->save();
                $id = $schedule_entity->id();
              }
            }
          }
          $checking_date_timestamp += 60 * 60 * 24;
          $checking_date = date("Y-m-d", $checking_date_timestamp);
          $checking_date_weekly_value = date("l", $checking_date_timestamp);
        }
        while($checking_date_timestamp <= $to_timestamp);          
      }
    }
  }

  public function isValidDateRange($from, $to, $schedule_start, $schedule_end) {
    return $from <= $schedule_start && $schedule_start < $schedule_end && $schedule_end <= $to;
    return $from <= $schedule_start && $schedule_start < $schedule_end && $schedule_end <= $to;
  }

  public function getTaxonomyTermKey($vocabulary, $tid) {
      $term = $this->entityTypeManager->getStorage('taxonomy_term')->load($tid);
      if($term) {
        return $term->get('field_key')->value;
      }
      return NULL;
  }  
}
