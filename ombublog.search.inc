<?php
// $id$

/**
 * @file
 * Exposes search forms & callbacks for blog posts at the url /blog/search
 *
 * @todo: this can be converted to strictly views.
 */

/**
 * Menu callback; presents the search form and/or search results.
 *
 * @see search.pages.inc : search_view()
 */
function ombublog_search_view($which_blog, $keys = '') {
  context_set('context', 'blogs', context_load('blogs'));

  $which_blog = _ombublog_get_current_blog_tid();
  $term = taxonomy_term_load($which_blog);
  drupal_set_title($term->name .' Blog: Search');

  $info = FALSE;
  $redirect = FALSE;
  $keys = trim($keys);
  // Also try to pull search keywords out of the $_REQUEST variable to
  // support old GET format of searches for existing links.
  if (!$keys && !empty($_REQUEST['keys'])) {
    $keys = trim($_REQUEST['keys']);
  }

  $active_module_info = search_get_info();
  $info = $active_module_info['node'];

  // Default results output is an empty string.
  $results = array('#markup' => '');
  // Process the search form. Note that if there is $_POST data,
  // search_form_submit() will cause a redirect to search/[module path]/[keys],
  // which will get us back to this page callback. In other words, the search
  // form submits with POST but redirects to GET. This way we can keep
  // the search query URL clean as a whistle.
  if (empty($_POST['form_id']) || $_POST['form_id'] != 'search_form') {
    $conditions =  NULL;
    if (isset($info['conditions_callback']) && function_exists($info['conditions_callback'])) {
      // Build an optional array of more search conditions.
      $conditions = call_user_func($info['conditions_callback'], $keys);
    }
    // Only search if there are keywords or non-empty conditions.
    if ($keys || !empty($conditions)) {
      // Log the search keys.
      watchdog('search', 'Searched %type for %keys.', array('%keys' => $keys, '%type' => $info['title']), WATCHDOG_NOTICE, l(t('results'), 'search/' . $info['path'] . '/' . $keys));

      // Collect the search results.
      $internal_keys = "$keys type:blog_post term:$which_blog";
      $results = search_data($internal_keys, $info['module'], $conditions);
    }
  }
  // The form may be altered based on whether the search was run.
  $build['search_form'] = drupal_get_form('ombublog_search_form', $keys);
  $build['search_results'] = $results;

  return $build;
}

/**
 * Render a search form for the blog.
 *
 * @return
 *   An HTML string containing the search form.
 */
function ombublog_search_form($form, &$form_state, $keys = '', $which_blog = NULL) {

  if (!$which_blog) {
    $which_blog = _ombublog_get_current_blog_name();
  }
  $form_state['#which_blog'] = $which_blog;

  // Add CSS
  drupal_add_css(drupal_get_path('module', 'search') .'/search.css', 'module', 'all', FALSE);

  $form = array(
    '#action' => base_path() . OMBUBLOG_BASE_PATH .'/'. $form_state['#which_blog'] .'/search',
    '#attributes' => array('class' => 'search-form'),
  );
  $form['basic']['inline'] = array('#prefix' => '<div class="container-inline">', '#suffix' => '</div>');
  $form['basic']['inline']['keys'] = array(
    '#type' => 'textfield',
    '#title' => '',
    '#default_value' => $keys,
    '#maxlength' => 255,
    '#size' => 30,
  );
  $form['basic']['inline']['keys']['#attributes']['placeholder'] = 'Keywords';
  // processed_keys is used to coordinate keyword passing between other forms
  // that hook into the basic search form.
  $form['basic']['inline']['processed_keys'] = array('#type' => 'value', '#value' => array());
  $form['basic']['inline']['submit'] = array('#type' => 'submit', '#value' => t('Search Blog'));

  return $form;
}

/**
 * Process a search form submission.
 *   Redirects to a GET request with the keys as the 3rd arg
 */
function ombublog_search_form_submit($form, &$form_state) {
  $keys = trim($form_state['values']['keys']);
  if ($keys == '') {
    form_set_error('keys', t('Please enter some keywords.'));
  }
  $form_state['redirect'] = OMBUBLOG_BASE_PATH .'/'. $form_state['#which_blog'] .'/search/'. $keys;
  return;
}
