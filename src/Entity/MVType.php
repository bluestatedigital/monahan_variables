<?php

namespace Drupal\monahan_variables\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;
use Drupal\monahan_variables\MVTypeInterface;

/**
 * Defines the Monahan Variables Group Type entity.
 * @package Drupal\monahan_variables\Entity
 *
 * @ConfigEntityType(
 *   id = "monahan_variables_mv_type",
 *   label = @Translation("Monahan Variables Group Type"),
 *   handlers = {
 *     "form" = {
 *       "add" = "Drupal\monahan_variables\Form\MVTypeForm",
 *       "edit" = "Drupal\monahan_variables\Form\MVTypeForm",
 *       "delete" = "Drupal\monahan_variables\Form\MVTypeDeleteForm"
 *     },
 *     "list_builder" = "Drupal\monahan_variables\MVTypeListBuilder",
 *     "access" = "Drupal\monahan_variables\MVTypeAccessControlHandler",
 *   },
 *   admin_permission = "administer monahan_variables_mv_type",
 *   config_prefix = "type",
 *   bundle_of = "monahan_variables_mv",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label"
 *   },
 *   links = {
 *     "edit-form" = "/admin/structure/monahan_variables/manage/{monahan_variables_mv_type}",
 *     "delete-form" = "/admin/structure/monahan_variables/manage/{monahan_variables_mv_type}/delete",
 *     "collection" = "/admin/structure/monahan_variables",
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "revision",
 *     "description",
 *   }
 * )
 */
class MVType extends ConfigEntityBundleBase implements MVTypeInterface {
  /**
   * The custom block type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The custom block type label.
   *
   * @var string
   */
  protected $label;

  /**
   * The default revision setting for custom blocks of this type.
   *
   * @var bool
   */
  protected $revision;

  /**
   * The description of the block type.
   *
   * @var string
   */
  protected $description;

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    return $this->description;
  }

  /**
   * {@inheritdoc}
   */
  public function shouldCreateNewRevision() {
    return $this->revision;
  }
}