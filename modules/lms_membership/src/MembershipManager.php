<?php

namespace Drupal\lms_membership;

use DateTime;
use DateTimeZone;
use Drupal\commerce_order\Entity\Order;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Config\ImmutableConfig;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\lms_lesson\Entity\LMSLesson;
use Drupal\profile\Entity\Profile;
use Drupal\taxonomy\Entity\Term;
use Drupal\user\Entity\User;

class MembershipManager implements MembershipManagerInterface {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected EntityTypeManagerInterface $entityTypeManager;

  /**
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected ConfigFactoryInterface $configFactory;

  /**
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, ConfigFactoryInterface $config_factory) {
    $this->entityTypeManager = $entity_type_manager;
    $this->configFactory = $config_factory;
  }

  /**
   * {@inheritdoc}
   */
  public function createMembership(Order $order) {
    $user = $order->getCustomer();
    if ($user) {
      $current_time = $this->getTimeWithTimezone();
      $order_items = $order->getItems();
      /** @var \Drupal\commerce_order\Entity\OrderItem $order_item */
      foreach ($order_items as $order_item) {
        /** @var \Drupal\commerce_product\Entity\ProductVariation $purchased_entity */
        $purchased_entity = $order_item->getPurchasedEntity();
        if ($purchased_entity) {
          $quantity = $order_item->getQuantity();
          /** @var \Drupal\commerce_product\Entity\Product $product */
          $product = $purchased_entity->getProduct();
          if ($product) {
            $bundle = $product->bundle();
            if ($bundle == 'membership') {
              if ($product->hasField('field_membership_type')) {
                /** @var Term $membership_type */
                $membership_type = $product->get('field_membership_type')->entity;
                if ($membership_type && $membership_type->hasField('field_key') && !$membership_type->get('field_key')
                    ->isEmpty()) {
                  $user->addRole('membership');
                  $user->save();
                  $membership_type_key = $membership_type->get('field_key')
                    ->getString();
                  $user_membership = $this->getMembershipOfUser($user);
                  if ($user_membership && $user_membership->hasField('field_membership_time')) {
                    // When user has subscription
                    $start_date = $user_membership->get('field_membership_time')->value;
                    $end_date = $user_membership->get('field_membership_time')->end_value;
                    $new_start_date = $this->getStartDateOfMembership(strtotime($start_date), strtotime($end_date));
                    $new_end_date = $this->getExpiredDateOfMembership($membership_type_key, $quantity, strtotime($end_date));
                    $user_membership->set('field_membership_time', [
                      'value' => date('Y-m-d\TH:i:s', $new_start_date),
                      'end_value' => date('Y-m-d\TH:i:s', $new_end_date),
                    ]);
                    $user_membership->set('field_membership_type', $membership_type->id());
                    $user_membership->save();
                  }
                  else {
                    $start_date = $current_time;
                    $end_date = $this->getExpiredDateOfMembership($membership_type_key, $quantity);
                    $user_membership = Profile::create([
                      'type' => 'membership',
                      'uid' => $user->id(),
                      'field_membership_type' => $membership_type->id(),
                      'field_membership_time' => [
                        'value' => date('Y-m-d\TH:i:s', $start_date),
                        'end_value' => date('Y-m-d\TH:i:s', $end_date),
                      ],
                    ]);
                    $user_membership->save();
                  }
                }
              }
            }
          }
        }
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getMembershipOfUser(User $user) {
    $membership_profiles = \Drupal::entityTypeManager()
      ->getStorage('profile')
      ->loadByProperties([
        'uid' => $user->id(),
        'type' => 'membership',
      ]);
    if ($membership_profiles) {
      return reset($membership_profiles);
    }
    return NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function getExpiredDateOfMembership(string $membership_type_key, $quantity, $expired_time = NULL) {
    $current_time = $this->getTimeWithTimezone();
    if (empty($expired_time) || ($current_time > $expired_time)) {
      $expired_time = $current_time;
    }
    if ($membership_type_key == 'year') {
      $expired_time = $expired_time + (60 * 60 * 24 * 365 * $quantity);
    }
    elseif ($membership_type_key == 'month') {
      $expired_time = $expired_time + (60 * 60 * 24 * 30 * $quantity);
    }
    return $expired_time;
  }

  /**
   * {@inheritdoc}
   */
  public function getStartDateOfMembership($start_time, $expired_time) {
    $current_time = $this->getTimeWithTimezone();
    if ($current_time > $expired_time) {
      $start_time = $current_time;
    }
    return $start_time;
  }

  /**
   * {@inheritdoc}
   */
  public function getTimeWithTimezone(string $datetime = 'now', string $date_format = NULL) {
    $config = $this->configFactory->get('system.date');
    $timezone = $config->get('timezone.default') ?? 'UTC';
    $date_time = new DateTime($datetime);
    $date_time->setTimezone(new DateTimeZone($timezone));
    if ($date_format) {
      return $date_time->format($date_format);
    }
    return $date_time->getTimestamp();
  }

  /**
   * {@inheritdoc}
   */
  public function subscriptionCron() {
    $membership_profiles = \Drupal::entityTypeManager()
      ->getStorage('profile')
      ->loadByProperties([
        'type' => 'membership',
      ]);
    if ($membership_profiles) {
      /** @var Profile $membership_profile */
      foreach ($membership_profiles as $membership_profile) {
        $is_expired = $this->checkExpired($membership_profile);
        if ($is_expired) {
          /** @var User $user */
          $user = $membership_profile->getOwner();
          if ($user) {
            $user->removeRole('membership');
            $user->save();
          }
        }
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function checkExpired(Profile $profile) {
    $current_time = $this->getTimeWithTimezone();
    $end_date = $profile->get('field_membership_time')->end_value;
    if ($current_time > $end_date) {
      return TRUE;
    }
    return FALSE;
  }

}
