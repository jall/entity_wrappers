<?php

/**
 * @file
 * Entity wrapper methods to load all entities of that type.
 */

namespace Drupal\b2b\Shared;

use EntityFieldQuery;
use Generator;

trait LoadAllEntities {

  /**
   * Returns ALL entities of a certain type.
   *
   * This returns a generator, as we don't really want to be storing potentially
   * thousands of objects in memory at once.
   *
   * @return Generator
   */
  public static function yieldAll() {
    $query = new EntityFieldQuery();
    $result = $query
      ->entityCondition('entity_type', static::ENTITY_TYPE)
      ->entityCondition('bundle', static::BUNDLE)
      ->execute();

    $ids = !empty($result[static::ENTITY_TYPE]) ? array_keys($result[static::ENTITY_TYPE]) : [];
    return static::yieldWrappers($ids);
  }

  /**
   * Returns ALL entities of a certain type.
   *
   * Only use this in situations where yieldAll can't be used, as it can have
   * bad effects if there are a lot of entities.
   *
   * @return static[]
   */
  public static function loadAll() {
    $query = new EntityFieldQuery();
    $result = $query
      ->entityCondition('entity_type', static::ENTITY_TYPE)
      ->entityCondition('bundle', static::BUNDLE)
      ->execute();

    $ids = !empty($result[static::ENTITY_TYPE]) ? array_keys($result[static::ENTITY_TYPE]) : [];
    return static::getWrappers($ids);
  }

}