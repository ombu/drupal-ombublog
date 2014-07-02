<?php
/**
 * @file
 * ombublog.bean.inc
 */

class OmbublogList extends BeanPlugin {
  public function values() {
    return array(
      'tid' => FALSE,
    );
  }

  public function form($bean, $form, &$form_state) {
    // Get all state terms.
    $vocab = taxonomy_vocabulary_machine_name_load(variable_get('ombublog_vocabulary', ''));
    if ($vocab) {
      $terms = taxonomy_get_tree($vocab->vid, 0, NULL, TRUE);

      $options = array();
      foreach ($terms as $term) {
        $options[$term->tid] = $term->name;
      }

      // Add option for default.
      // $options = array('default' => 'Dynamic based on current page') + $options;

      $form['tid'] = array(
        '#title' => $vocab->name,
        '#type' => 'select',
        '#options' => $options,
        '#default_value' => $bean->tid,
        '#description' => t('Select the !vocab to show blog posts from.', array('!vocab' => strtolower($vocab->name))),
      );
    }

    return $form;
  }

  public function view($bean, $content, $view_mode = 'default', $langcode = NULL) {
    // Default to current page's state.
    // if ($bean->tid == 'default') {
    //   $state = state_get_active_state();
    //   $bean->tid = $state->tid;
    // }

    $content['#markup'] = views_embed_view('ombublog', 'latest_posts', $bean->tid);
    return $content;
  }
}
