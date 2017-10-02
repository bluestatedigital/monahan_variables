<?php

namespace Drupal\monahan_variables\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\monahan_variables\MVTypeInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MVController extends ControllerBase {

  /**
   * The MVType storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $mvTypeStorage;

  /**
   * The MVType definition.
   *
   * @var \Drupal\Core\Entity\EntityTypeInterface
   */
  protected $mvTypeDefinition;

  /**
   * The MV storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $mvStorage;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $entity_manager = $container->get('entity.manager');
    return new static(
      $entity_manager->getStorage('monahan_variables_mv_type'),
      $entity_manager->getDefinition('monahan_variables_mv_type'),
      $entity_manager->getStorage('monahan_variables_mv')
    );
  }

  /**
   * Constructs an MVController object.
   *
   * @param \Drupal\Core\Entity\EntityStorageInterface $mvTypeStorage
   *   The monahan_variables_mv_type storage.
   * @param \Drupal\Core\Entity\EntityTypeInterface $mvTypeDefinition
   *   The monahan_variables_mv_type definition.
   * @param \Drupal\Core\Entity\EntityStorageInterface $mvStorage
   *   The monahan_variables_mv_type storage.
   */
  public function __construct(EntityStorageInterface $mvTypeStorage, EntityTypeInterface $mvTypeDefinition, EntityStorageInterface $mvStorage) {
    $this->mvTypeStorage = $mvTypeStorage;
    $this->mvTypeDefinition = $mvTypeDefinition;
    $this->mvStorage = $mvStorage;
  }

  /**
   * Displays links for the available MV types.
   */
  public function addPage() {
    $types = $this->mvTypeStorage->loadMultiple();

    // If there is only one MV Group, there's no reason to show the list.
    // Redirect the user straight to the add page.
    if (count($types) == 1) {
      $type = array_shift($types);
      return $this->redirect('monahan_variables_mv.add', ['monahan_variables_mv_type' => $type->id()]);
    }

    return ['#theme' => 'mv_add_list', '#content' => $types];
  }

  /**
   * Provides the Monahan Variables submission form.
   *
   * @param \Drupal\monahan_variables\MVTypeInterface $monahan_variables_mv_type
   *   The MVType entity for the MV.
   *
   * @return array
   *   A submission form.
   */
  public function add(MVTypeInterface $monahan_variables_mv_type) {
    $mv = $this->mvStorage->create([
      'type' => $monahan_variables_mv_type->id(),
    ]);
    $form = $this->entityFormBuilder()->getForm($mv);

    return $form;
  }
}