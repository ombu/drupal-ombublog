<?php
/**
 * @file
 * ombublog.context.inc
 */

/**
 * Implements hook_context_default_contexts().
 */
function ombublog_context_default_contexts() {
  $export = array();

  $context = new stdClass();
  $context->disabled = FALSE; /* Edit this to true to make a default context disabled initially */
  $context->api_version = 3;
  $context->name = 'blogs';
  $context->description = 'Context for the blogs on the site';
  $context->tag = '';
  $context->conditions = array(
    'node' => array(
      'values' => array(
        'blog_post' => 'blog_post',
      ),
      'options' => array(
        'node_form' => '0',
      ),
    ),
    'views' => array(
      'values' => array(
        'ombublog' => 'ombublog',
        'ombublog_tags' => 'ombublog_tags',
      ),
    ),
  );
  $context->reactions = array(
    'block' => array(
      'blocks' => array(
        'ombublog-rss_feed' => array(
          'module' => 'ombublog',
          'delta' => 'rss_feed',
          'region' => 'sidebar_first',
          'weight' => '-10',
        ),
        'ombublog-search' => array(
          'module' => 'ombublog',
          'delta' => 'search',
          'region' => 'sidebar_first',
          'weight' => '-9',
        ),
        'views-ombublog-recent_posts' => array(
          'module' => 'views',
          'delta' => 'ombublog-recent_posts',
          'region' => 'sidebar_first',
          'weight' => '-8',
        ),
        'views-ombublog-most_popular' => array(
          'module' => 'views',
          'delta' => 'ombublog-most_popular',
          'region' => 'sidebar_first',
          'weight' => '-7',
        ),
        'ombublog-tags_list' => array(
          'module' => 'ombublog',
          'delta' => 'tags_list',
          'region' => 'sidebar_first',
          'weight' => '-6',
        ),
        'ombublog-archive_list' => array(
          'module' => 'ombublog',
          'delta' => 'archive_list',
          'region' => 'sidebar_first',
          'weight' => '-5',
        ),
      ),
    ),
    'theme_html' => array(
      'class' => 'blog',
    ),
  );
  $context->condition_mode = 0;

  // Translatables
  // Included for use with string extractors like potx.
  t('Context for the blogs on the site');
  $export['blogs'] = $context;

  return $export;
}
