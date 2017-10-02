<?php

namespace Drupal\monahan_variables;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\RevisionLogInterface;

/**
 * Provides an interface defining a MV entity.
 * @ingroup monahan_variables
 */
interface MVInterface extends ContentEntityInterface, EntityChangedInterface, RevisionLogInterface {

}