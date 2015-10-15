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
   * @return Node|TaxonomyTerm|User
   */
  public static function getWrapper($data, $entity_type, $bundle = self::NO_BUNDLE) {
    switch ($entity_type) {

      case Node::ENTITY_TYPE:
        return new Node($data);

      case TaxonomyTerm::ENTITY_TYPE:
        return new TaxonomyTerm($data);

      case User::ENTITY_TYPE:
        return new User($data);

      default:
        throw new InvalidArgumentException(self::invalidEntityTypeMessage($entity_type));
    }
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
