<?php
/**
 * @file
 * ombublog.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function ombublog_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "field_group" && $api == "field_group") {
    return array("version" => "1");
  }
}

/**
 * Implements hook_views_api().
 */
function ombublog_views_api($module = NULL, $api = NULL) {
  return array("api" => "3.0");
}

/**
 * Implements hook_flag_default_flags().
 */
function ombublog_flag_default_flags() {
  $flags = array();
  // Exported flag: "Featured".
  $flags['featured'] = array(
    'entity_type' => 'node',
    'title' => 'Featured',
    'global' => 1,
    'types' => array(
      0 => 'blog_post',
    ),
    'flag_short' => 'Feature',
    'flag_long' => '',
    'flag_message' => '',
    'unflag_short' => 'Unfeature',
    'unflag_long' => '',
    'unflag_message' => '',
    'unflag_denied_text' => '',
    'link_type' => 'toggle',
    'weight' => 0,
    'show_in_links' => array(
      'teaser' => TRUE,
      'full' => TRUE,
    ),
    'show_as_field' => FALSE,
    'show_on_form' => 1,
    'access_author' => '',
    'show_contextual_link' => FALSE,
    'i18n' => 0,
    'module' => 'ombublog',
    'locked' => array(
      0 => 'name',
    ),
    'api_version' => 3,
  );
  return $flags;

}

/**
 * Implements hook_default_profile2_type().
 */
function ombublog_default_profile2_type() {
  $items = array();

  $author_path = str_replace('/' , '\\/', ombublog_base_path() . 'author');

  $items['blog_author'] = entity_import('profile2_type', '{
    "userCategory" : true,
    "userView" : false,
    "type" : "blog_author",
    "label" : "Blog author",
    "weight" : "0",
    "data" : { "registration" : 0, "use_page" : 1, "page_path" : "' . $author_path . '" },
    "rdf_mapping" : []
  }');
  return $items;
}

/**
 * Implements hook_rdf_default_mappings().
 */
function ombublog_rdf_default_mappings() {
  $schemaorg = array();

  // Exported RDF mapping: blog_post
  $schemaorg['node']['blog_post'] = array(
    'rdftype' => array(
      0 => 'schema:BlogPosting',
      1 => 'sioc:Item',
      2 => 'foaf:Document',
    ),
    'title' => array(
      'predicates' => array(
        0 => 'schema:name',
      ),
    ),
    'created' => array(
      'predicates' => array(
        0 => 'dc:date',
        1 => 'dc:created',
      ),
      'datatype' => 'xsd:dateTime',
      'callback' => 'date_iso8601',
    ),
    'changed' => array(
      'predicates' => array(
        0 => 'dc:modified',
      ),
      'datatype' => 'xsd:dateTime',
      'callback' => 'date_iso8601',
    ),
    'body' => array(
      'predicates' => array(
        0 => 'schema:articleBody',
        1 => 'content:encoded',
      ),
    ),
    'uid' => array(
      'predicates' => array(
        0 => 'sioc:has_creator',
      ),
      'type' => 'rel',
    ),
    'name' => array(
      'predicates' => array(
        0 => 'schema:name',
      ),
    ),
    'comment_count' => array(
      'predicates' => array(
        0 => 'sioc:num_replies',
      ),
      'datatype' => 'xsd:integer',
    ),
    'last_activity' => array(
      'predicates' => array(
        0 => 'sioc:last_activity_date',
      ),
      'datatype' => 'xsd:dateTime',
      'callback' => 'date_iso8601',
    ),
    'field_image' => array(
      'predicates' => array(
        0 => 'schema:image',
      ),
      'type' => 'rel',
    ),
    'url' => array(
      'predicates' => array(
        0 => 'schema:url',
      ),
      'type' => 'rel',
    ),
    'field_blog' => array(
      'predicates' => array(
        0 => 'schema:articleSection',
      ),
      'type' => 'rel',
    ),
    'field_tags' => array(
      'predicates' => array(
        0 => 'schema:keywords',
      ),
      'type' => 'rel',
    ),
  );

  return $schemaorg;
}
