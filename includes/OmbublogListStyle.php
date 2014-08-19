<?php

/**
 * @file
 * OmbuBlog Listing Style.
 */

class OmbublogListStyle extends BeanStyle {
  /**
   * Display mode for entity items.
   */
  protected $display_mode = 'teaser';

  /**
   * Implements parent::prepareView().
   */
  public function prepareView($build, $bean) {
    parent::prepareView($build, $bean);

    if (!empty($build['#nodes'])) {
      $build['nodes'] = node_view_multiple($build['#nodes'], $this->display_mode);
    }

    return $build;
  }
}
