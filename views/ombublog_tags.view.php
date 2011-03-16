<?php

$view = new view;
$view->name = 'ombublog_tags';
$view->description = 'Ombu Blog Tags';
$view->tag = '';
$view->view_php = '';
$view->base_table = 'term_data';
$view->is_cacheable = FALSE;
$view->api_version = 2;
$view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */
$handler = $view->new_display('default', 'Defaults', 'default');
$handler->override_option('relationships', array(
  'vid' => array(
    'label' => '',
    'required' => 1,
    'id' => 'vid',
    'table' => 'term_node',
    'field' => 'vid',
    'relationship' => 'none',
  ),
));
$handler->override_option('fields', array(
  'tid' => array(
    'label' => 'Term ID',
    'alter' => array(
      'alter_text' => 0,
      'text' => '',
      'make_link' => 0,
      'path' => '',
      'link_class' => '',
      'alt' => '',
      'prefix' => '',
      'suffix' => '',
      'target' => '',
      'help' => '',
      'trim' => 0,
      'max_length' => '',
      'word_boundary' => 1,
      'ellipsis' => 1,
      'html' => 0,
      'strip_tags' => 0,
    ),
    'empty' => '',
    'hide_empty' => 0,
    'empty_zero' => 0,
    'set_precision' => FALSE,
    'precision' => 0,
    'decimal' => '.',
    'separator' => ',',
    'prefix' => '',
    'suffix' => '',
    'exclude' => 1,
    'id' => 'tid',
    'table' => 'term_data',
    'field' => 'tid',
    'relationship' => 'none',
  ),
  'name' => array(
    'label' => '',
    'alter' => array(
      'alter_text' => 0,
      'text' => '',
      'make_link' => 1,
      'path' => OMBUBLOG_BASE_PATH .'/tag/[tid]',
      'link_class' => '',
      'alt' => '',
      'prefix' => '',
      'suffix' => '',
      'target' => '',
      'help' => '',
      'trim' => 0,
      'max_length' => '',
      'word_boundary' => 1,
      'ellipsis' => 1,
      'html' => 0,
      'strip_tags' => 0,
    ),
    'empty' => '',
    'hide_empty' => 0,
    'empty_zero' => 0,
    'link_to_taxonomy' => 0,
    'exclude' => 0,
    'id' => 'name',
    'table' => 'term_data',
    'field' => 'name',
    'relationship' => 'none',
  ),
));
$handler->override_option('sorts', array(
  'name' => array(
    'order' => 'ASC',
    'id' => 'name',
    'table' => 'term_data',
    'field' => 'name',
    'relationship' => 'none',
  ),
));
$handler->override_option('filters', array(
  'vid' => array(
    'operator' => 'in',
    'value' => array(
      '1' => '1',
    ),
    'group' => '0',
    'exposed' => FALSE,
    'expose' => array(
      'operator' => FALSE,
      'label' => '',
    ),
    'id' => 'vid',
    'table' => 'term_data',
    'field' => 'vid',
    'relationship' => 'none',
  ),
));
$handler->override_option('access', array(
  'type' => 'none',
));
$handler->override_option('cache', array(
  'type' => 'none',
));
$handler->override_option('items_per_page', 0);
$handler->override_option('distinct', 1);
$handler = $view->new_display('block', 'Block', 'block_1');
$handler->override_option('title', 'Tags');
$handler->override_option('style_plugin', 'list');
$handler->override_option('style_options', array(
  'grouping' => '',
  'type' => 'ul',
));
$handler->override_option('block_description', 'Ombu Blog Tags');
$handler->override_option('block_caching', '8');
