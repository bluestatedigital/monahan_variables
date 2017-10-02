<?php

namespace Drupal\monahan_variables\Form;

use Drupal\Core\Entity\EntityDeleteForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a confirmation form for deleting a Monahan Variables type.
 */
class MVTypeDeleteForm extends EntityDeleteForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $mv_groups = $this->entityTypeManager
      ->getStorage('monahan_variables_mv')
      ->getQuery()
      ->condition('type', $this->entity->id())
      ->execute();
    if (!empty($mv_groups)) {
      $caption = '<p>' . $this->formatPlural(
        count($mv_groups),
        '%label is used by 1 MV group on your site. You can not remove this MV type until you have removed all of the %label groups.',
        '%label is used by @count MV groups on your site. You may not remove %label until you have removed all of the %label groups.',
        ['%label' => $this->entity->label()]
      ) . '</p>';
      $form['description'] = ['#markup' => $caption];
      return $form;
    }
    else {
      return parent::buildForm($form, $form_state);
    }
  }
}