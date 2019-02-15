<?php

namespace Drupal\monahan_variables;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Retrieves field values from Monahan Variables.
 */
class MVManager {
  /**
   * The entity type manager.
   *
   * @var EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The entity storage interface for Monahan Variables.
   *
   * @var EntityStorageInterface
   */
  protected $entityStorage;

  /**
   * MVManager constructor.
   *
   * @param EntityTypeManagerInterface $entityTypeManager
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function __construct(EntityTypeManagerInterface $entityTypeManager) {
    $this->entityTypeManager = $entityTypeManager;
    $this->entityStorage = $entityTypeManager
      ->getStorage('monahan_variables_mv');
  }

  /**
   * Load the Monahan Variables entity for a given type.
   *
   * @param string $type
   *   ID of the MVType group to load.
   * @return \Drupal\monahan_variables\MVInterface|bool
   *   Monahan Variables entity or FALSE if none was found.
   */
  protected function loadByType($type) {
    $mvQuery = $this->entityStorage->getQuery();
    $mvQuery->condition('type', $type)->range(0, 1);
    $mvResult = $mvQuery->execute();
    if (empty($mvResult)) {
      return FALSE;
    }
    // Get just the first result if there are multiple.
    $mvId = array_shift($mvResult);
    return $this->entityStorage->load($mvId);
  }

  /**
   * Return the render array for a set of Monahan Variables.
   *
   * @param string $type
   *   The MV Type from which to retrieve the variables.
   * @return array|NULL
   *   The render array for the variable group or NULL if nothing was found.
   */
  public function getVariables($type) {
    $mv = $this->loadByType($type);
    if (!$mv) {
      return NULL;
    }
    /* @var \Drupal\Core\Entity\EntityViewBuilderInterface $render_controller */
    $renderController = $this->entityTypeManager
      ->getViewBuilder($mv->getEntityTypeId());
    return $renderController->view($mv);
  }

  /**
   * Return the value of a single field in a Monahan Variables group.
   *
   * @param string $type
   *   The MV Type from which to retrieve the variables.
   * @param string $field
   *   The ID of the field to retrieve.
   * @return mixed|NULL
   *   The value of the field or NULL if either the Monahan Variable or the
   *   field could not be found.
   */
  public function getValue($type, $field) {
    $mv = $this->loadByType($type);
    if (!$mv) {
      return NULL;
    }
    if ($mv->hasField($field)) {
      return $mv->get($field)->getValue();
    }
    return NULL;
  }
}