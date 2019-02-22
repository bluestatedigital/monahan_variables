<?php

namespace Drupal\monahan_variables\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityPublishedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\RevisionLogEntityTrait;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\monahan_variables\MVInterface;

/**
 * Defines the MV entity
 * @package Drupal\monahan_variables\Entity
 *
 * @ContentEntityType(
 *   id = "monahan_variables_mv",
 *   label = @Translation("Monahan Variables"),
 *   bundle_label = @Translation("Monahan Variables Group"),
 *   handlers = {
 *    "storage" = "Drupal\Core\Entity\Sql\SqlContentEntityStorage",
 *    "list_builder" = "Drupal\monahan_variables\MVListBuilder",
 *    "view_builder" = "Drupal\monahan_variables\MVViewBuilder",
 *    "form" = {
 *       "default" = "Drupal\monahan_variables\Form\MVForm",
 *       "add" = "Drupal\monahan_variables\Form\MVForm",
 *       "edit" = "Drupal\monahan_variables\Form\MVForm",
 *       "delete" = "Drupal\monahan_variables\Form\MVDeleteForm",
 *     },
 *    "access" = "Drupal\monahan_variables\MVAccessControlHandler",
 *    "translation" = "Drupal\monahan_variables\MVTranslationHandler",
 *   },
 *   base_table = "monahan_variables_mv",
 *   revision_table = "monahan_variables_mv_revision",
 *   data_table = "monahan_variables_mv_field_data",
 *   revision_data_table = "monahan_variables_mv_field_revision",
 *   show_revision_ui = TRUE,
 *   translatable = TRUE,
 *   entity_keys = {
 *     "id" = "id",
 *     "revision" = "revision_id",
 *     "bundle" = "type",
 *     "label" = "label",
 *     "langcode" = "langcode",
 *     "uuid" = "uuid"
 *   },
 *   revision_metadata_keys = {
 *     "revision_user" = "revision_uid",
 *     "revision_created" = "revision_timestamp",
 *     "revision_log_message" = "revision_log"
 *   },
 *   links = {
 *     "collection" = "/admin/content/monahan_variables",
 *     "edit-form" = "/admin/content/monahan_variables/{monahan_variables_mv}",
 *     "delete-form" = "/admin/content/monahan_variables/{monahan_variables_mv}/delete",
 *   },
 *   bundle_entity_type = "monahan_variables_mv_type",
 *   field_ui_base_route = "entity.monahan_variables_mv_type.edit_form",
 * )
 */
class MV extends ContentEntityBase implements MVInterface {

  use EntityChangedTrait;
  use EntityPublishedTrait;
  use RevisionLogEntityTrait;

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function getChangedTime() {
    return $this->get('changed')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setChangedTime($timestamp) {
    $this->set('changed', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getChangedTimeAcrossTranslations()  {
    $changed = $this->getUntranslated()->getChangedTime();
    foreach ($this->getTranslationLanguages(FALSE) as $language)    {
      $translation_changed = $this->getTranslation($language->getId())->getChangedTime();
      $changed = max($translation_changed, $changed);
    }
    return $changed;
  }

  /**
   * {@inheritdoc}
   *
   * Define the field properties here.
   *
   * Field name, type and size determine the table structure.
   *
   * In addition, we can define how the field and its content can be manipulated
   * in the GUI. The behaviour of the widgets used can be determined here.
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {

    /** @var \Drupal\Core\Field\BaseFieldDefinition[] $fields */
    $fields = parent::baseFieldDefinitions($entity_type);

    // Add the revision metadata fields.
    $fields += static::revisionLogBaseFieldDefinitions($entity_type);

    $fields['label'] = BaseFieldDefinition::create('string')
     ->setLabel(t('Label'))
     ->setDescription(t('The label for the group.'))
     ->setTranslatable(TRUE)
     ->setSettings(array(
       'default_value' => '',
       'max_length' => 255,
       'text_processing' => 0,
     ))
     ->setRequired(TRUE)
     ->setDisplayOptions('view', array(
       'label' => 'hidden',
       'type' => 'string',
       'weight' => -6,
     ))
     ->setDisplayOptions('form', array(
       'type' => 'string_textfield',
       'weight' => -6,
     ));

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'))
      ->setTranslatable(TRUE);

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'))
      ->setTranslatable(TRUE);

    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function setInfo($info) {
    $this->set('label', $info);
    return $this;
  }
}