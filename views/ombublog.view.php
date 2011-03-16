<?php

$view = new view;
$view->name = 'ombublog';
$view->description = 'Ombu Blog';
$view->tag = '';
$view->view_php = '';
$view->base_table = 'node';
$view->is_cacheable = FALSE;
$view->api_version = 2;
$view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */
$handler = $view->new_display('default', 'Defaults', 'default');
$handler->override_option('sorts', array(
  'created' => array(
    'order' => 'DESC',
    'granularity' => 'second',
    'id' => 'created',
    'table' => 'node',
    'field' => 'created',
    'relationship' => 'none',
  ),
));
$handler->override_option('arguments', array(
  'tid' => array(
    'id' => 'tid',
    'table' => 'term_node',
    'field' => 'tid',
  ),
));
$handler->override_option('filters', array(
  'type' => array(
    'operator' => 'in',
    'value' => array(
      'blog_post' => 'blog_post',
    ),
    'group' => '0',
    'exposed' => FALSE,
    'expose' => array(
      'operator' => FALSE,
      'label' => '',
    ),
    'id' => 'type',
    'table' => 'node',
    'field' => 'type',
    'relationship' => 'none',
  ),
  'status' => array(
    'operator' => '=',
    'value' => '1',
    'group' => '0',
    'exposed' => FALSE,
    'expose' => array(
      'operator' => FALSE,
      'label' => '',
    ),
    'id' => 'status',
    'table' => 'node',
    'field' => 'status',
    'relationship' => 'none',
  ),
));
$handler->override_option('access', array(
  'type' => 'none',
));
$handler->override_option('cache', array(
  'type' => 'none',
));
$handler->override_option('use_pager', '1');
$handler->override_option('row_plugin', 'node');
$handler->override_option('row_options', array(
  'relationship' => 'none',
  'build_mode' => 'teaser',
  'links' => 0,
  'comments' => 0,
));
$handler = $view->new_display('block', 'Blog Homepage', 'blog_by_date');
$handler->override_option('arguments', array(
  'created_year' => array(
    'default_action' => 'ignore',
    'style_plugin' => 'default_summary',
    'style_options' => array(),
    'wildcard' => 'all',
    'wildcard_substitution' => 'All',
    'title' => '',
    'breadcrumb' => '',
    'default_argument_type' => 'fixed',
    'default_argument' => '',
    'validate_type' => 'none',
    'validate_fail' => 'not found',
    'id' => 'created_year',
    'table' => 'node',
    'field' => 'created_year',
    'validate_user_argument_type' => 'uid',
    'validate_user_roles' => array(
      '2' => 0,
      '5' => 0,
      '3' => 0,
      '4' => 0,
    ),
    'override' => array(
      'button' => 'Use default',
    ),
    'relationship' => 'none',
    'default_options_div_prefix' => '',
    'default_argument_fixed' => '',
    'default_argument_user' => 0,
    'default_argument_image_size' => '_original',
    'default_argument_php' => '',
    'validate_argument_node_type' => array(
      'image' => 0,
      'blog_post' => 0,
      'case_study' => 0,
      'connection' => 0,
      'page' => 0,
    ),
    'validate_argument_node_access' => 0,
    'validate_argument_nid_type' => 'nid',
    'validate_argument_vocabulary' => array(
      '1' => 0,
      '2' => 0,
    ),
    'validate_argument_type' => 'tid',
    'validate_argument_transform' => 0,
    'validate_user_restrict_roles' => 0,
    'image_size' => array(
      '_original' => '_original',
      'thumbnail' => 'thumbnail',
      'preview' => 'preview',
    ),
    'validate_argument_php' => '',
  ),
  'created_month' => array(
    'default_action' => 'ignore',
    'style_plugin' => 'default_summary',
    'style_options' => array(),
    'wildcard' => 'all',
    'wildcard_substitution' => 'All',
    'title' => '',
    'breadcrumb' => '',
    'default_argument_type' => 'fixed',
    'default_argument' => '',
    'validate_type' => 'none',
    'validate_fail' => 'not found',
    'id' => 'created_month',
    'table' => 'node',
    'field' => 'created_month',
    'validate_user_argument_type' => 'uid',
    'validate_user_roles' => array(
      '2' => 0,
      '5' => 0,
      '3' => 0,
      '4' => 0,
    ),
    'override' => array(
      'button' => 'Use default',
    ),
    'relationship' => 'none',
    'default_options_div_prefix' => '',
    'default_argument_fixed' => '',
    'default_argument_user' => 0,
    'default_argument_image_size' => '_original',
    'default_argument_php' => '',
    'validate_argument_node_type' => array(
      'image' => 0,
      'blog_post' => 0,
      'case_study' => 0,
      'connection' => 0,
      'page' => 0,
    ),
    'validate_argument_node_access' => 0,
    'validate_argument_nid_type' => 'nid',
    'validate_argument_vocabulary' => array(
      '1' => 0,
      '2' => 0,
    ),
    'validate_argument_type' => 'tid',
    'validate_argument_transform' => 0,
    'validate_user_restrict_roles' => 0,
    'image_size' => array(
      '_original' => '_original',
      'thumbnail' => 'thumbnail',
      'preview' => 'preview',
    ),
    'validate_argument_php' => '',
  ),
));
$handler->override_option('block_description', '');
$handler->override_option('block_caching', -1);
$handler = $view->new_display('block', 'Blog Posts by Tag', 'blog_by_tags');
$handler->override_option('arguments', array(
  'tid' => array(
    'default_action' => 'ignore',
    'style_plugin' => 'default_summary',
    'style_options' => array(),
    'wildcard' => 'all',
    'wildcard_substitution' => 'All',
    'title' => '',
    'breadcrumb' => '',
    'default_argument_type' => 'fixed',
    'default_argument' => '',
    'validate_type' => 'none',
    'validate_fail' => 'not found',
    'break_phrase' => 1,
    'add_table' => 0,
    'require_value' => 0,
    'reduce_duplicates' => 1,
    'set_breadcrumb' => 0,
    'id' => 'tid',
    'table' => 'term_node',
    'field' => 'tid',
    'validate_user_argument_type' => 'uid',
    'validate_user_roles' => array(
      '2' => 0,
      '5' => 0,
      '3' => 0,
      '4' => 0,
    ),
    'override' => array(
      'button' => 'Use default',
    ),
    'relationship' => 'none',
    'default_options_div_prefix' => '',
    'default_argument_fixed' => '',
    'default_argument_user' => 0,
    'default_argument_image_size' => '_original',
    'default_argument_php' => '',
    'validate_argument_node_type' => array(
      'image' => 0,
      'blog_post' => 0,
      'case_study' => 0,
      'connection' => 0,
      'page' => 0,
    ),
    'validate_argument_node_access' => 0,
    'validate_argument_nid_type' => 'nid',
    'validate_argument_vocabulary' => array(
      '1' => 0,
      '2' => 0,
    ),
    'validate_argument_type' => 'tid',
    'validate_argument_transform' => 0,
    'validate_user_restrict_roles' => 0,
    'image_size' => array(
      '_original' => '_original',
      'thumbnail' => 'thumbnail',
      'preview' => 'preview',
    ),
    'validate_argument_php' => '',
  ),
));
$handler->override_option('block_description', '');
$handler->override_option('block_caching', -1);
