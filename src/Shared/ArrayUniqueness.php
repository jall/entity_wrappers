<?php
/**
 * @file
 *
 */

namespace Drupal\entity_wrappers\Shared;

use Drupal\entity_wrappers\Exceptions\NonUniqueResultException;
use InvalidArgumentException;

trait ArrayUniqueness {

  /**
   * @param string $key
   * @param array $items
   *
   * @return mixed
   *
   * @throws InvalidArgumentException
   * @throws NonUniqueResultException
   */
  public static function extractSingleItem($key, array $items) {
    self::assertSingleArrayItem($key, $items);
    return array_pop($items);
  }

  /**
   * @param string $key
   * @param array $items
   *
   * @throws InvalidArgumentException
   * @throws NonUniqueResultException
   */
  public static function assertSingleArrayItem($key, array $items) {
    if (empty($items)) {
      throw new InvalidArgumentException(t('The item "@key" was not found.', ['@key' => $key]));
    }

    if (count($items) > 1) {
      throw new NonUniqueResultException(t('Multiple results found for the item "@key".', ['@key' => $key]));
    }
  }

}
