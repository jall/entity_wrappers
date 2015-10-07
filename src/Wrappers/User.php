<?php

/**
 * @file
 * Wrapper class for User entities.
 */

namespace Drupal\entity_wrappers\Wrappers;

class User extends EntityWrapper {

  const ENTITY_TYPE = 'user';
  const BUNDLE = 'user';

  /**
   * @return string
   */
  public function getEmail() {
    return $this->{'mail'}->value();
  }

  /**
   * @param string $email
   */
  public function setEmail($email) {
    return $this->{'mail'}->set($email);
  }

  /**
   * @return string
   */
  public function getUsername() {
    return $this->{'name'}->value();
  }

  /**
   * @return string[]
   */
  public function getRoles() {
    return $this->{'roles'}->value();
  }

  /**
   * @param int $role_id
   */
  public function addRole($role_id) {
    $this->{'roles'}[] = $role_id;
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
   */
  public static function loadByEmail($email) {
    return new static(user_load_by_mail($email));
  }

}
