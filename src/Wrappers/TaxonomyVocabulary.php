<?php

/**
 * @file
 * Taxonomy vocabulary wrapper.
 */

namespace Drupal\entity_wrappers\Wrappers;

class TaxonomyVocabulary extends EntityWrapper {

  const ENTITY_TYPE = 'taxonomy_vocabulary';

  /**
   * @return string
   */
  public function getMachineName() {
    return $this->{'machine_name'}->value();
  }

  /**
   * @param string $machine_name
   *
   * @return static
   */
  public static function loadByMachineName($machine_name) {
    return new static(taxonomy_vocabulary_machine_name_load($machine_name));
  }

}
