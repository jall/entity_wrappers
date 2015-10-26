<?php

/**
 * @file
 * Wrapper class for Node entities.
 */

namespace Drupal\entity_wrappers\Wrappers;

use Drupal\entity_wrappers\Exceptions\NonUniqueResultException;
use Drupal\entity_wrappers\Shared\ArrayUniqueness;
use EntityFieldQuery;
use InvalidArgumentException;

class Node extends EntityWrapper {

  use ArrayUniqueness;

  const ENTITY_TYPE = 'node';

  const PROPERTY_AUTHOR = 'author';
  const PROPERTY_CHANGED = 'changed';
  const PROPERTY_CREATED = 'created';
  const PROPERTY_ID = 'nid';
  const PROPERTY_STATUS = 'status';
  const PROPERTY_TITLE = 'title';

  const CACHE_KEY_TITLE = 'Node::loadMultipleByTitle';

  /**
   * @return User
   */
  public function getAuthor() {
    return new User($this->{static::PROPERTY_AUTHOR}->raw());
  }

  /**
   * @param User $account
   */
  public function setAuthor(User $account) {
    $this->{static::PROPERTY_AUTHOR}->set($account->getIdentifier());
  }

  /**
   * @return string
   */
  public function getTitle() {
    return $this->{static::PROPERTY_TITLE}->value();
  }

  /**
   * @param string $title
   */
  public function setTitle($title) {
    $this->{static::PROPERTY_TITLE}->set($title);
  }

  /**
   * @return int
   *   Unix timestamp.
   */
  public function getCreatedTime() {
    return $this->{static::PROPERTY_CREATED}->value();
  }

  /**
   * @param int $timestamp
   *   Unix timestamp.
   */
  public function setCreatedTime($timestamp) {
    $this->{static::PROPERTY_CREATED}->set($timestamp);
  }

  /**
   * @param string $title
   *
   * @return bool
   */
  public static function isTitleInUse($title) {
    try {
      return (bool) static::loadByTitle($title);
    }
    catch (InvalidArgumentException $e) {
      return FALSE;
    }
    catch (NonUniqueResultException $e) {
      watchdog_exception('b2b_import', $e);
      return TRUE;
    }
  }

  /**
   * @param string $title
   *
   * @return static
   *
   * @throws InvalidArgumentException
   * @throws NonUniqueResultException
   */
  public static function loadByTitle($title) {
    return static::extractSingleItem($title, static::loadMultipleByTitle($title));
  }

  /**
   * @param string $title
   *
   * @return static[]
   */
  public static function loadMultipleByTitle($title) {
    $cache = &drupal_static(self::CACHE_KEY_TITLE, []);

    if (!isset($cache[static::ENTITY_TYPE][$title])) {
      $query = new EntityFieldQuery();
      $result = $query
        ->entityCondition('entity_type', static::ENTITY_TYPE)
        ->propertyCondition(static::PROPERTY_TITLE, $title)
        ->execute();

      $cache[static::ENTITY_TYPE][$title] = !empty($result[static::ENTITY_TYPE]) ? array_keys($result[static::ENTITY_TYPE]) : [];
    }

    return static::getWrappers($cache[static::ENTITY_TYPE][$title]);
  }

}
