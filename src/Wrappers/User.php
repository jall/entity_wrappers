<?php

/**
 * @file
 * Wrapper class for User entities.
 */

namespace Drupal\entity_wrappers\Wrappers;

use InvalidArgumentException;

class User extends EntityWrapper {

  const ENTITY_TYPE = 'user';
  const BUNDLE = 'user';

  const PROPERTY_EMAIL = 'mail';
  const PROPERTY_ROLES = 'roles';
  const PROPERTY_USERNAME = 'name';
  const PROPERTY_STATUS = 'status';

  const STATUS_ACTIVE = 1;
  const STATUS_BLOCKED = 0;

  /**
   * @return string
   */
  public function getEmail() {
    return $this->{static::PROPERTY_EMAIL}->value();
  }

  /**
   * @param string $email
   *
   * @return $this
   */
  public function setEmail($email) {
   $this->{static::PROPERTY_EMAIL}->set($email);
   return $this;
  }

  /**
   * @return string
   */
  public function getUsername() {
    return $this->{static::PROPERTY_USERNAME}->value();
  }

  /**
   * @param string $username
   *
   * @return $this
   */
  public function setUsername($username) {
    $this->{static::PROPERTY_USERNAME}->set($username);
    return $this;
  }

  /**
   * @return string[]
   */
  public function getRoles() {
    return $this->{static::PROPERTY_ROLES}->value();
  }

  /**
   * @param int $role_id
   *
   * @return $this
   */
  public function addRole($role_id) {
    $this->{static::PROPERTY_ROLES}[] = $role_id;
    return $this;
  }

  /**
   * @return int
   */
  public function getStatus() {
    return (int) $this->{static::PROPERTY_STATUS}->value();
  }

  /**
   * @param int $status
   *
   * @return $this
   */
  public function setStatus($status) {
    $this->{static::PROPERTY_STATUS}->set($status);
    return $this;
  }

  public function isActive() {
    return $this->getStatus() === static::STATUS_ACTIVE;
  }

  public function isBlocked() {
    return $this->getStatus() === static::STATUS_BLOCKED;
  }

  /**
   * @return static
   */
  public static function loadCurrent() {
    return new static($GLOBALS['user']->uid);
  }

  /**
   * @param string $email
   *
   * @return static
   *
   * @throws InvalidArgumentException
   */
  public static function loadByEmail($email) {
    $account = user_load_by_mail($email);

    if ($account) {
      return new static($account);
    }

    throw new InvalidArgumentException(t('Unable to load user with email %email', ['%email' => $email]));
  }

}
