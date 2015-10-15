<?php

/**
 * @file
 * Wrapper entity for node bundles.
 */

namespace Drupal\entity_wrappers\Wrappers\Node;

use Drupal\entity_wrappers\Wrappers\Node;
use EntityFieldQuery;

abstract class NodeBundle extends Node {

  /**
   * {@inheritdoc}
   *
   * Allows per bundle load by title.
   */
  public static function loadMultipleByTitle($title) {
    $cache = &drupal_static(self::CACHE_KEY_TITLE, []);

    if (!isset($cache[static::BUNDLE][$title])) {
      $query = new EntityFieldQuery();
      $result = $query
        ->entityCondition('entity_type', static::ENTITY_TYPE)
        ->entityCondition('bundle', static::BUNDLE)
        ->propertyCondition('title', $title)
        ->execute();

      $cache[static::BUNDLE][$title] = !empty($result[static::ENTITY_TYPE]) ? array_keys($result[static::ENTITY_TYPE]) : [];
    }

    return static::getWrappers($cache[static::BUNDLE][$title]);
  }

}
