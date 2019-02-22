<?php

namespace Drupal\monahan_variables;

use Drupal\content_translation\ContentTranslationHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Form\FormStateInterface;

class MVTranslationHandler extends ContentTranslationHandler {
  /**
   * {@inheritdoc}
   */
  public function entityFormAlter(array &$form, FormStateInterface $form_state, EntityInterface $entity) {
    parent::entityFormAlter($form, $form_state, $entity);

    if (isset($form['content_translation'])) {
      $form['content_translation']['status']['#access'] = FALSE;
      $form['content_translation']['name']['#access'] = FALSE;
      $form['content_translation']['created']['#access'] = FALSE;
    }
  }

  /**
   * {@inheritdoc}
   */
  protected function entityFormTitle(EntityInterface $entity) {
    return t('Edit @subject variables', ['@subject' => $entity->label()]);
  }

  /**
   * {@inheritdoc}
   */
  public function entityFormEntityBuild($entity_type, EntityInterface $entity, array $form, FormStateInterface $form_state) {
    if ($form_state->hasValue('content_translation')) {
      $translation = &$form_state->getValue('content_translation');
      /** @var \Drupal\monahan_variables\MVInterface $entity */
      $translation['status'] = $entity->isPublished();
      $translation['name'] = $entity->getAuthorName();
    }
    parent::entityFormEntityBuild($entity_type, $entity, $form, $form_state);
  }
}