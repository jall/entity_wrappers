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
    $cache = &drupal_static(static::CACHE_KEY_NAME, []);

    if (!isset($cache[static::BUNDLE][$name])) {
      $query = new EntityFieldQuery();
      $result = $query
        ->entityCondition('entity_type', static::ENTITY_TYPE)
        ->propertyCondition('vid', static::getVocabulary()->getIdentifier())
        ->propertyCondition('name', $name)
        ->execute();

      $cache[static::BUNDLE][$name] = !empty($result[static::ENTITY_TYPE]) ? array_keys($result[static::ENTITY_TYPE]) : [];
    }

    return static::getWrappers($cache[static::BUNDLE][$name]);
  }

}
