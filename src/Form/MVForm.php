<?php

namespace Drupal\monahan_variables\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form handler for the Monahan Variables create and edit forms.
 */
class MVForm extends ContentEntityForm {

  /**
   * The MV entity.
   *
   * @var \Drupal\monahan_variables\MVInterface
   */
  protected $entity;

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $mv = $this->entity;

    $form = parent::form($form, $form_state);

    if ($this->operation == 'edit') {
      $form['#title'] = $this->t(
        'Edit Monahan Variables %label',
        ['%label' => $mv->label()]
      );
    }

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $mv = $this->entity;

    $insert = $mv->isNew();
    $mv->save();
    $t_args = ['%info' => $mv->label()];

    if ($insert) {
      drupal_set_message($this->t('%info has been created.', $t_args));
    }
    else {
      drupal_set_message($this->t('%info has been updated.', $t_args));
    }

    if ($mv->id()) {
      $form_state->setRedirectUrl($mv->toUrl('collection'));
    }
    else {
      // In the unlikely case something went wrong on save, the block will be
      // rebuilt and block form redisplayed.
      drupal_set_message($this->t('The variables could not be saved.'), 'error');
      $form_state->setRebuild();
    }
  }
}