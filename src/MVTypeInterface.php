<?php

namespace Drupal\monahan_variables;

use Drupal\Core\Config\Entity\ConfigEntityInterface;
use Drupal\Core\Entity\RevisionableEntityBundleInterface;

/**
 * Provides an interface defining a Monahan Variables Group Type entity.
 */
interface MVTypeInterface extends ConfigEntityInterface, RevisionableEntityBundleInterface {

  /**
   * Returns the description of the Monahan Variables Group.
   *
   * @return string
   *   The description of the type of this group.
   */
  public function getDescription();
}