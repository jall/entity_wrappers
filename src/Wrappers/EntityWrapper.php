<?php

/**
 * @file
 * Abstract wrapper class for custom entities.
 */

namespace Drupal\entity_wrappers\Wrappers;

use EntityDrupalWrapper;
use EntityFieldQuery;
use Generator;

abstract class EntityWrapper extends EntityDrupalWrapper {

  const ENTITY_TYPE = '';
  const BUNDLE = NULL;

  /**
   * Wrapper around parent constructor to supply entity type automatically.
   *
   * @param $data
   *   (optional) The entity to wrap or its identifier.
   * @param array $info
   *   (optional) An array of additional info to pass to the constructor.
   */
  public function __construct($data, $info = []) {
    $info += ['bundle' => static::BUNDLE];
    parent::__construct(static::ENTITY_TYPE, $data, $info);
  }

  /**
   * @param int $id
   *
   * @return bool
   */
  public static function entityIdExists($id) {
    $cache = &drupal_static(__METHOD__, []);

    if (!isset($cache[static::ENTITY_TYPE][$id])) {
      $query = new EntityFieldQuery();
      $result = $query
        ->entityCondition('entity_type', static::ENTITY_TYPE)
        ->propertyCondition('id', $id)
        ->execute();

      $cache[static::ENTITY_TYPE][$id] = !empty($result[static::ENTITY_TYPE]);
    }

    return $cache[static::ENTITY_TYPE][$id];
  }

  /**
   * Takes an array of entity wrappers and returns an array of entity ids.
   *
   * @param self[] $wrappers
   *
   * @return int[]
   */
  public static function extractIds(array $wrappers) {
    return array_map(function (EntityWrapper $wrapper) {
      return $wrapper->getIdentifier();
    }, $wrappers);
  }

  /**
   * Takes an array of entity ids and returns an array of entity wrappers.
   *
   * @param int[]|object[] $ids
   *   A list of entity ids or loaded entities to wrap.
   *
   * @return static[]
   *   A list of wrapped entities.
   */
  public static function getWrappers(array $ids) {
    return array_map(function ($id) {
      return new static($id);
    }, $ids);
  }

  /**
   * Takes an array of entity ids and returns a generator of entity wrappers.
   *
   * @param int[]|object[] $ids
   *   A list of entity ids or loaded entities to wrap.
   *
   * @return Generator
   *   A generator containing the wrapped entities.
   */
  public static function yieldWrappers(array $ids) {
    foreach ($ids as $id) {
      yield new static($id);
    }
  }

  /**
   * Creates a new entity and returns its wrapper.
   *
   * Note that different entities need different properties.
   *
   * @param array $values
   *   The entity properties to initialise.
   *
   * @return static
   *   The entity's wrapper.
   */
  public static function create(array $values = []) {
    $values += ['type' => static::BUNDLE];
    $entity = entity_create(static::ENTITY_TYPE, $values);
    return new static($entity);
  }

}
