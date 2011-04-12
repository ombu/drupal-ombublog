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
function ombublog_search_view() {
  drupal_set_title('Search the blog');
  $type = 'node';

  // Search form submits with POST but redirects to GET. This way we can keep
  // the search query URL clean as a whistle:
  // blog/search/keyword+keyword
  if (!isset($_POST['form_id'])) {

    $keys = search_get_keys();
    // Only perform search if there is non-whitespace search term:
    $results = '';
    if (trim($keys)) {
      // Log the search keys:
      watchdog('ombublog_search', '%keys (@type).', array('%keys' => $keys, '@type' => module_invoke($type, 'search', 'name')), WATCHDOG_NOTICE, l(t('results'), OMBUBLOG_BASE_PATH. '/search/'. $keys));

      $keys_with_type = $keys .' type:blog_post';

      // Collect the search results:
      $results = search_data($keys_with_type, $type);

      if ($results) {
        $results = '<div class="box" id="search-results"><h3>'. t('Search results for ') .'<em>'. $keys .'</em></h3>'. $results .'</div>';
      }
      else {
        $results = '<div class="box" id="search-results"><h3>'. t('Your search yielded no results') .'</h3>'. search_help('search#noresults', drupal_help_arg()) .'</div>';
      }
    }

    // Construct the search form.
    $output = drupal_get_form('ombublog_search_form', NULL, $keys);
    $output .= $results;

    return $output;
  }

  return drupal_get_form('ombublog_search_form', NULL, empty($keys) ? '' : $keys);
}

/**
 * Render a search form for the blog.
 *
 * @return
 *   An HTML string containing the search form.
 */
function ombublog_search_form(&$form_state, $keys = '') {
  // Add CSS
  drupal_add_css(drupal_get_path('module', 'search') .'/search.css', 'module', 'all', FALSE);

  $form = array(
    '#action' => base_path() . OMBUBLOG_BASE_PATH .'/search',
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
  // processed_keys is used to coordinate keyword passing between other forms
  // that hook into the basic search form.
  $form['basic']['inline']['processed_keys'] = array('#type' => 'value', '#value' => array());
  $form['basic']['inline']['submit'] = array('#type' => 'submit', '#value' => t('Search'));

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
  $form_state['redirect'] = OMBUBLOG_BASE_PATH .'/search/'. $keys;
  return;
}
