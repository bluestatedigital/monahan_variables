<?php

namespace Drupal\monahan_variables;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Session\AccountInterface;

/**
 * Defines the access control handler for Monahan Variables.
 *
 * @see \Drupal\block_content\Entity\BlockContent
 */
class MVAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    if ($account->hasPermission('create monahan_variables_mv')) {
      return AccessResult::allowed()->cachePerPermissions();
    }
    else {
      return parent::checkCreateAccess($account, $context, $entity_bundle);
    }
  }

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    if ($operation === 'view') {
      return AccessResult::allowed();
    }
    if ($account->isAuthenticated()) {
      switch ($operation) {
        case 'edit':
        case 'update':
          return AccessResult::allowedIfHasPermission($account,'edit monahan_variables_mv')
            ->cachePerPermissions()
            ->cachePerUser()
            ->addCacheableDependency($entity);
        case 'delete':
          return AccessResult::allowedIfHasPermission($account,'delete monahan_variables_mv')
            ->cachePerPermissions()
            ->cachePerUser()
            ->addCacheableDependency($entity);
      }
    }
    $access_result = parent::checkAccess($entity, $operation, $account);
    // Make sure the variable group is added as a cache dependency.
    $access_result->addCacheableDependency($entity);
    return $access_result;
  }

}
