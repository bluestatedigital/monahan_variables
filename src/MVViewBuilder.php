<?php

namespace Drupal\monahan_variables;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityViewBuilder;
use Drupal\Core\Render\Element;

/**
 * View builder for Monahan Variables.
 */
class MVViewBuilder extends EntityViewBuilder {

  /**
   * {@inheritdoc}
   */
  public function view(EntityInterface $entity, $view_mode = 'full', $langcode = NULL) {
    return $this->viewMultiple([$entity], $view_mode, $langcode)[0];
  }

  /**
   * {@inheritdoc}
   */
  public function viewMultiple(array $entities = [], $view_mode = 'full', $langcode = NULL) {
    $build_list = parent::viewMultiple($entities, $view_mode, $langcode);
    // Apply the buildMultiple() #pre_render callback immediately, to make
    // bubbling of attributes and contextual links to the actual block work.
    // @see \Drupal\block\BlockViewBuilder::buildBlock()
    unset($build_list['#pre_render'][0]);
    return $this->buildMultiple($build_list);
  }

  /**
   * {@inheritdoc}
   */
  public function buildMultiple(array $build_list) {
    $list = parent::buildMultiple($build_list);
    foreach ($list as $index => $entity) {
      if (!empty($entity['#monahan_variables_mv'])) {
        $render_array = [];
        $children = Element::children($entity);
        foreach ($children as $key) {
          $field = $entity[$key];
          $field['#cache'] = $entity['#cache'];
          $render_array[$key] = $field;
        }
        $list[$index] = $render_array;
      }
    }
    return $list;
  }
}