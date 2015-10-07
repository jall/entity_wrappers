<?php

/**
 * @file
 * Wrapper class for Node entities.
 */

namespace Drupal\entity_wrappers\Wrappers;

use Drupal\entity_wrappers\Exceptions\NonUniqueResultException;
use EntityFieldQuery;
use InvalidArgumentException;

class Node extends EntityWrapper {

  const ENTITY_TYPE = 'node';

  /**
   * @return User
   */
  public function getAuthor() {
    return new User($this->{'author'}->raw());
  }

  /**
   * @param User $account
   */
  public function setAuthor(User $account) {
    $this->{'author'}->set($account->getIdentifier());
  }

  /**
   * @return string
   */
  public function getTitle() {
    return $this->{'title'}->value();
  }

  /**
   * @param string $title
   */
  public function setTitle($title) {
    $this->{'title'}->set($title);
  }

  /**
   * @return int
   *   Unix timestamp.
   */
  public function getCreatedTime() {
    return $this->{'created'}->value();
  }

  /**
   * @param int $timestamp
   *   Unix timestamp.
   */
  public function setCreatedTime($timestamp) {
    $this->{'created'}->set($timestamp);
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
    $nodes = static::loadMultipleByTitle($title);

    if (empty($nodes)) {
      throw new InvalidArgumentException(t('Unable to load "@title" node.', ['@title' => $title]));
    }

    if (count($nodes) > 1) {
      throw new NonUniqueResultException(t('Multiple results found for "@title" node.', ['@title' => $title]));
    }

    return array_pop($nodes);
  }

  /**
   * @param string $title
   *
   * @return static[]
   */
  public static function loadMultipleByTitle($title) {
    $cache = &drupal_static(__METHOD__ . '::' . static::BUNDLE, []);

    if (!isset($cache[$title])) {
      $query = new EntityFieldQuery();
      $result = $query
        ->entityCondition('entity_type', static::ENTITY_TYPE)
        ->entityCondition('bundle', static::BUNDLE)
        ->propertyCondition('title', $title)
        ->execute();

      $cache[$title] = !empty($result[static::ENTITY_TYPE]) ? array_keys($result[static::ENTITY_TYPE]) : [];
    }

    return static::getWrappers($cache[$title]);
  }

  /**
   * @param string $bundle
   *   The node bundle to reset the cache for.
   */
  public static function resetTitleCache($bundle) {
    $key = __CLASS__ . '::loadMultipleByTitle::' . $bundle;
    drupal_static_reset($key);
  }

}
