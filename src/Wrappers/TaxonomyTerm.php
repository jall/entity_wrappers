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
    $cache = &drupal_static(__METHOD__, []);

    if (!isset($cache[$name])) {
      $query = new EntityFieldQuery();
      $result = $query
        ->entityCondition('entity_type', static::ENTITY_TYPE)
        ->propertyCondition('name', $name)
        ->execute();

      $cache[$name] = !empty($result[static::ENTITY_TYPE]) ? array_keys($result[static::ENTITY_TYPE]) : [];
    }

    return static::getWrappers($cache[$name]);
  }

}
