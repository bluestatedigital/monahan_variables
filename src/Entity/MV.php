<?php

namespace Drupal\monahan_variables\Entity;

use Drupal\Core\Annotation\Translation;
use Drupal\Core\Entity\Annotation\ContentEntityType;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\monahan_variables\MVInterface;
use Drupal\user\UserInterface;

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

    $fields['id']->setLabel(t('Monahan Variables ID'))
                 ->setDescription(t('The group ID.'));

    $fields['uuid']->setDescription(t('The MV UUID.'));

    $fields['revision_id']->setDescription(t('The revision ID.'));

    $fields['langcode']->setDescription(t('The MV language code.'));

    $fields['type']->setLabel(t('Monahan Variables Group'))->setDescription(t('The group.'));

    // Name field for the contact.
    // We set display options for the view as well as the form.
    // Users with correct privileges can change the view and edit configuration.

    $fields['label'] = BaseFieldDefinition::create('string')
     ->setLabel(t('Label'))
     ->setDescription(t('The label for the group.'))
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
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    $fields['revision_timestamp'] = BaseFieldDefinition::create('created')
     ->setLabel(t('Revision timestamp'))
     ->setDescription(t('The time that the current revision was created.'))
     ->setQueryable(FALSE)
     ->setRevisionable(TRUE);

    $fields['revision_uid'] = BaseFieldDefinition::create('entity_reference')
     ->setLabel(t('Revision user ID'))
     ->setDescription(t('The user ID of the author of the current revision.'))
     ->setSetting('target_type', 'user')
     ->setQueryable(FALSE)
     ->setRevisionable(TRUE);

    $fields['revision_log'] = BaseFieldDefinition::create('string_long')
     ->setLabel(t('Revision log message'))
     ->setDescription(t('Briefly describe the changes you have made.'))
     ->setRevisionable(TRUE)
     ->setDefaultValue('')
     ->setDisplayOptions('form', [
       'type' => 'string_textarea',
       'weight' => 25,
       'settings' => [
         'rows' => 4,
       ],
     ]);

    $fields['revision_translation_affected'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Revision translation affected'))
      ->setDescription(t('Indicates if the last edit of a translation belongs to current revision.'))
      ->setReadOnly(TRUE)
      ->setRevisionable(TRUE)
      ->setTranslatable(TRUE);

    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function getRevisionLog() {
    return $this->getRevisionLogMessage();
  }

  /**
   * {@inheritdoc}
   */
  public function setInfo($info) {
    $this->set('label', $info);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setRevisionLog($revision_log) {
    return $this->setRevisionLogMessage($revision_log);
  }

  /**
   * {@inheritdoc}
   */
  public function getRevisionCreationTime() {
    return $this->get('revision_timestamp')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setRevisionCreationTime($timestamp) {
    $this->set('revision_timestamp', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getRevisionUser() {
    return $this->get('revision_uid')->entity;
  }

  public function setRevisionUser(UserInterface $account) {
    $this->set('revision_uid', $account);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getRevisionUserId() {
    return $this->get('revision_uid')->entity->id();
  }

  /**
   * {@inheritdoc}
   */
  public function setRevisionUserId($user_id) {
    $this->set('revision_uid', $user_id);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getRevisionLogMessage() {
    return $this->get('revision_log')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setRevisionLogMessage($revision_log_message) {
    $this->set('revision_log', $revision_log_message);
    return $this;
  }
}