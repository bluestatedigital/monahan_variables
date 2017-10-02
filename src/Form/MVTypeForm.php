<?php

namespace Drupal\monahan_variables\Form;

use Drupal\Core\Entity\BundleEntityFormBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\language\Entity\ContentLanguageSettings;

/**
 * Base form for Monahan Variable Group Types.
 */
class MVTypeForm extends BundleEntityFormBase {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    /* @var \Drupal\monahan_variables\MVTypeInterface $mv_type */
    $mv_type = $this->entity;

    if ($this->operation == 'add') {
      $form['#title'] = $this->t('Add Monahan Variables group type');
    }
    else {
      $form['#title'] = $this->t(
        'Edit %label group type',
        ['%label' => $mv_type->label()]
      );
    }

    $form['label'] = [
      '#type' => 'textfield',
      '#title' => t('Label'),
      '#maxlength' => 255,
      '#default_value' => $mv_type->label(),
      '#description' => t("Provide a label for this group type to help identify it in the administration pages."),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $mv_type->id(),
      '#machine_name' => [
        'exists' => '\Drupal\monahan_variables\Entity\MVType::load',
      ],
      '#maxlength' => EntityTypeInterface::BUNDLE_MAX_LENGTH,
    ];

    $form['description'] = [
      '#type' => 'textarea',
      '#default_value' => $mv_type->getDescription(),
      '#description' => t('Enter a description for this group type.'),
      '#title' => t('Description'),
    ];

    $form['revision'] = [
      '#type' => 'checkbox',
      '#title' => t('Create new revision'),
      '#default_value' => $mv_type->shouldCreateNewRevision(),
      '#description' => t('Create a new revision by default.'),
    ];

    if ($this->moduleHandler->moduleExists('language')) {
      $form['language'] = [
        '#type' => 'details',
        '#title' => t('Language settings'),
        '#group' => 'additional_settings',
      ];

      $language_configuration
        = ContentLanguageSettings::loadByEntityTypeBundle(
          'monahan_variables_mv',
          $mv_type->id()
      );
      $form['language']['language_configuration'] = [
        '#type' => 'language_configuration',
        '#entity_information' => [
          'entity_type' => 'monahan_variables_mv',
          'bundle' => $mv_type->id(),
        ],
        '#default_value' => $language_configuration,
      ];

      $form['#submit'][] = 'language_configuration_element_submit';
    }

    $form['actions'] = ['#type' => 'actions'];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => t('Save'),
    ];

    return $this->protectBundleIdElement($form);
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {

    /* @var \Drupal\monahan_variables\MVTypeInterface $mv_type */
    $mv_type = $this->entity;
    $status = $mv_type->save();

    if ($status == SAVED_UPDATED) {
      drupal_set_message(t('Monahan Variables group type %label has been updated.', ['%label' =>$mv_type->label()]));
    }
    else {
      drupal_set_message(t('Monahan Variables group type %label has been added.', ['%label' => $mv_type->label()]));
    }

    $form_state->setRedirectUrl($this->entity->toUrl('collection'));
  }
}