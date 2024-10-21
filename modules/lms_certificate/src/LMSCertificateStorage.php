<?php

namespace Drupal\lms_certificate;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\lms_certificate\Entity\LMSCertificateInterface;

/**
 * Defines the lms_certificate storage.
 */
class LMSCertificateStorage extends SqlContentEntityStorage implements LMSCertificateStorageInterface {
}
