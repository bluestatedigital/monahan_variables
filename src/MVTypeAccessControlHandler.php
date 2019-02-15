<?php

namespace Drupal\monahan_variables;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Session\AccountInterface;

/**
 * Defines the access control handler for Monahan Variables Types.
 */
class MVTypeAccessControlHandler extends EntityAccessControlHandler {
  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    $admin_permission = $this->entityType->getAdminPermission();
    return AccessResult::allowedIfHasPermission($account, $admin_permission)->cachePerPermissions();
  }
}