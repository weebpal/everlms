<?php

namespace Drupal\lms_user_ceritificate;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\lms_user_ceritificate\Entity\LMSUserCertificateInterface;

/**
 * Defines the lms_user_ceritificate storage.
 */
class LMSUserCertificateStorage extends SqlContentEntityStorage implements LMSUserCertificateStorageInterface {
}
