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
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    if ($operation === 'view') {
      return AccessResult::allowed();
    }
    return parent::checkAccess($entity, $operation, $account);
  }

}
