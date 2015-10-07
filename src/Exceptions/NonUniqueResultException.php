<?php

/**
 * @file
 * An exception returned when only one result is expected, but multiple are
 * found.
 */

namespace Drupal\entity_wrappers\Exceptions;

use Exception;

class NonUniqueResultException extends Exception {

}
