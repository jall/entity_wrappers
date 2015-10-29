<?php

/**
 * @file
 * Factory class to return entity wrappers.
 */

namespace Drupal\entity_wrappers\Wrappers;

use InvalidArgumentException;

class EntityWrapperFactory {

  const NO_BUNDLE = '__NO_BUNDLE__';

  /**
   * Returns an entity wrapper class for the entity type provided.
   *
   * @param mixed $data
   *   The data to pass to the wrapper class constructor. Usually the id or
   *   loaded entity.
   * @param string $entity_type
   *   The entity type to load the class for.
   * @param string $bundle
   *   (optional) The bundle of the entity type to retrieve the class for.
   *
   * @return EntityWrapper
   */
  public static function getWrapper($data, $entity_type, $bundle = self::NO_BUNDLE) {
    $info = entity_get_info($entity_type);

    if ($bundle !== static::NO_BUNDLE) {
      if (isset($info['bundles'][$bundle]['entity_wrappers']['class'])) {
        $class = $info['bundles'][$bundle]['entity_wrappers']['class'];
      }
      else {
        throw new InvalidArgumentException(static::invalidBundleMessage($bundle));
      }
    }
    elseif (isset($info['entity_wrappers']['class'])) {
      $class = $info['entity_wrappers']['class'];
    }
    else {
      throw new InvalidArgumentException(static::invalidEntityTypeMessage($entity_type));
    }

    return new $class($data);
  }

  /**
   * @param string $entity_type
   *
   * @return string
   */
  protected static function invalidEntityTypeMessage($entity_type) {
    return t('Invalid entity type %entity_type passed for EntityWrapperFactory', ['%entity_type' => $entity_type]);
  }

  /**
   * @param string $bundle
   *
   * @return string
   */
  protected static function invalidBundleMessage($bundle) {
    return t('Invalid bundle %bundle passed for EntityWrapperFactory', ['%bundle' => $bundle]);
  }

}
