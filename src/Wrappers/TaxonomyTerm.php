<?php

/**
 * @file
 * Wrapper entity for taxonomy terms.
 */

namespace Drupal\entity_wrappers\Wrappers;

class TaxonomyTerm extends EntityWrapper {

  const ENTITY_TYPE = 'taxonomy_term';

  /**
   * @return TaxonomyVocabulary
   */
  public static function getVocabulary() {
    return TaxonomyVocabulary::loadByMachineName(static::BUNDLE);
  }

}
