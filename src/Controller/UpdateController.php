<?php

/**
 * @file
 * Update function controller class.
 */

namespace Drupal\entity_wrappers\Controller;

class UpdateController {

  /**
   * Update function helper for using the built in batch processing.
   *
   * @param array &$sandbox
   *   The update process sandbox. Must be passed by reference from the update
   *   hook.
   * @param array $items
   *   An array of items to process. At most $items_per_batch of these will be
   *   passed to the callback in one operation. If passing an array of entities
   *   it is generally a good idea to just pass the ids and then load a batch
   *   of entity objects in the callback. This avoids issues with unserialising
   *   complex objects.
   * @param callable $callback
   *   The function to call each batch.
   * @param int $items_per_batch
   *   How many items to process each batch operation.
   *
   * @return null|string
   *   A success or failure message once all batches are finished.
   */
  public static function batchProcess(&$sandbox, array $items, callable $callback, $items_per_batch = 100) {
    if (!isset($sandbox['total'])) {
      $sandbox['items'] = $items;
      $sandbox['total'] = count($sandbox['items']);
      $sandbox['current'] = 0;
    }

    if ($sandbox['total'] == 0) {
      return t('No items to process.');
    }

    $current_items = array_slice($sandbox['items'], $sandbox['current'], $items_per_batch);

    $callback($current_items, $sandbox);

    $sandbox['current'] += count($current_items);
    $sandbox['#finished'] = $sandbox['current'] / $sandbox['total'];

    if ($sandbox['#finished'] === 1) {
      return t('Processed %count items', ['%count' => $sandbox['total']]);
    }
  }

}
