<?php

/**
 * @file
 * Wrapper entity for taxonomy terms.
 */

namespace Drupal\entity_wrappers\Wrappers;

use Drupal\entity_wrappers\Exceptions\NonUniqueResultException;
use Drupal\entity_wrappers\Shared\ArrayUniqueness;
use EntityFieldQuery;
use InvalidArgumentException;

class TaxonomyTerm extends EntityWrapper {

  use ArrayUniqueness;

  const ENTITY_TYPE = 'taxonomy_term';

  const PROPERTY_ID = 'tid';
  const PROPERTY_NAME = 'name';
  const PROPERTY_VOCABULARY_ID = 'vid';
  const PROPERTY_VOCABULARY_NAME = 'vocabulary_machine_name';

  const CACHE_KEY_NAME = 'TaxonomyTerm::loadMultipleByName';

  /**
   * @param string $name
   *
   * @return static
   *
   * @throws InvalidArgumentException
   * @throws NonUniqueResultException
   */
  public static function loadByName($name) {
    return static::extractSingleItem($name, static::loadMultipleByName($name));
  }

  /**
   * @param string $name
   *
   * @return static[]
   */
  public static function loadMultipleByName($name) {
    $cache = &drupal_static(static::CACHE_KEY_NAME, []);

    if (!isset($cache[static::ENTITY_TYPE][$name])) {
      $query = new EntityFieldQuery();
      $result = $query
        ->entityCondition('entity_type', static::ENTITY_TYPE)
        ->propertyCondition(static::PROPERTY_NAME, $name)
        ->execute();

      $cache[static::ENTITY_TYPE][$name] = !empty($result[static::ENTITY_TYPE]) ? array_keys($result[static::ENTITY_TYPE]) : [];
    }

    return static::getWrappers($cache[static::ENTITY_TYPE][$name]);
  }

}
