<?php

namespace Drupal\lms_user_certificate;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\lms_user_certificate\Entity\LMSUserCertificateInterface;

/**
 * Defines the lms_user_certificate storage.
 */
class LMSUserCertificateStorage extends SqlContentEntityStorage implements LMSUserCertificateStorageInterface {
}
