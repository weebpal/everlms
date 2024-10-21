<?php

namespace Drupal\lms_base;

/**
 * Provides a course service.
 */
interface LMSBaseManagerInterface {

  /**
   *  Function to get list taxonomy by vid
   *
   * @param string $vid
   * vid
   *
   * @param string $type
   * Response type
   */
  public function getListTaxonomyByVid(string $vid, string $type = 'key');
}
