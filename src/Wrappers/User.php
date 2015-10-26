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

  /**
   * @return string
   */
  public function getEmail() {
    return $this->{static::PROPERTY_EMAIL}->value();
  }

  /**
   * @param string $email
   */
  public function setEmail($email) {
    return $this->{static::PROPERTY_EMAIL}->set($email);
  }

  /**
   * @return string
   */
  public function getUsername() {
    return $this->{static::PROPERTY_USERNAME}->value();
  }

  /**
   * @return string[]
   */
  public function getRoles() {
    return $this->{static::PROPERTY_ROLES}->value();
  }

  /**
   * @param int $role_id
   */
  public function addRole($role_id) {
    $this->{static::PROPERTY_ROLES}[] = $role_id;
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
