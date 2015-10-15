<?php

/**
 * @file
 * Wrapper entity for taxonomy terms bundles.
 */

namespace Drupal\entity_wrappers\Wrappers\TaxonomyTerm;

use Drupal\entity_wrappers\Wrappers\TaxonomyTerm;
use Drupal\entity_wrappers\Wrappers\TaxonomyVocabulary;
use EntityFieldQuery;

abstract class TaxonomyTermBundle extends TaxonomyTerm {

  /**
   * @return TaxonomyVocabulary
   */
  public static function getVocabulary() {
    return TaxonomyVocabulary::loadByMachineName(static::BUNDLE);
  }

  /**
   * @param string $name
   *
   * @return static[]
   */
  public static function loadMultipleByName($name) {
    $cache = &drupal_static(__METHOD__, []);

    if (!isset($cache[$name])) {
      $query = new EntityFieldQuery();
      $result = $query
        ->entityCondition('entity_type', static::ENTITY_TYPE)
        ->propertyCondition('name', $name)
        ->propertyCondition('vid', static::getVocabulary()->getIdentifier())
        ->execute();

      $cache[$name] = !empty($result[static::ENTITY_TYPE]) ? array_keys($result[static::ENTITY_TYPE]) : [];
    }

    return static::getWrappers($cache[$name]);
  }

}
