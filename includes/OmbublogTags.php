<?php

/**
 * @file
 * Bean for displaying tag could for ombublog.
 */

class OmbublogTags extends BeanPlugin {
  /**
   * Implements parent::values().
   */
  public function values() {
    return array(
      'date_start' => NULL,
      'date_end' => NULL,
      'sort' => 'count',
      'count' => '',
    );
  }

  /**
   * Implements parent::form().
   */
  public function form($bean, $form, &$form_state) {
    // Date range filters.
    $form['date_start'] = array(
      '#type' => 'date_popup',
      '#title' => t('Filter by date range:'),
      '#date_year_range' => '-10:0',
      '#date_label_position' => 'invisible',
      '#date_format' => 'Y-m-d',
      '#default_value' => $bean->date_start,
      '#prefix' => '<div class="date-float">',
      '#suffix' => '</div><div class="date-float" style="padding: 35px 5px 0 0"> - to - </div>',
    );
    $form['date_end'] = array(
      '#type' => 'date_popup',
      '#title' => t('&nbsp;'),
      '#date_label_position' => 'invisible',
      '#date_year_range' => '-10:0',
      '#date_format' => 'Y-m-d',
      '#default_value' => $bean->date_end,
      '#prefix' => '<div class="date-float">',
      '#suffix' => '</div><div class="date-no-float"></div>',
    );

    $form['sort'] = array(
      '#type' => 'select',
      '#title' => t('Sort order'),
      '#default_value' => $bean->sort,
      '#options' => array(
        'count' => t('By usage count'),
        'alpha' => t('Alphabetical by name'),
      ),
    );

    $form['count'] = array(
      '#type' => 'select',
      '#title' => t('Count'),
      '#description' => t('Number of blog posts to show.'),
      '#options' => array('' => '- All - ') + array_combine(range(1, 50), range(1, 50)),
      '#default_value' => $bean->count,
    );

    return $form;
  }

  /**
   * Implements parent::values().
   */
  public function validate($values, &$form_state) {
    // Ensure date values are an actual range.
    $date_start = strtotime($values['date_start']);
    $date_end = strtotime($values['date_end']);
    if ($date_start > $date_end) {
      form_set_error('date_start', 'Starting date must start before ending date for date filtering');
    }
  }

  /**
   * Imlements parent::view().
   */
  public function view($bean, $content, $view_mode = 'default', $langcode = NULL) {
    $tags = taxonomy_vocabulary_machine_name_load(variable_get('ombublog_tags_vocabulary', ''));
    $query = db_select('taxonomy_term_data', 'td');
    $query
      ->fields('td', array('tid'))
      ->condition('td.vid', $tags->vid);

    // Have to join to tags field in order to get node data.
    if (!empty($bean->date_start) || !empty($bean->date_end) || $bean->sort == 'count') {
      $query->leftJoin('field_data_field_tags', 'ft', 'td.tid = ft.field_tags_tid');
      $query->addExpression('COUNT(ft.entity_id)', 'node_count');
      $query->groupBy('ft.entity_id');
    }

    if ($bean->date_start || $bean->date_end) {
      $query->leftJoin('node', 'n', "ft.entity_type = 'node' AND ft.entity_id = n.nid");
    }
    if ($bean->date_start) {
      $query->condition('n.created', strtotime($bean->date_start), '>');
    }
    if ($bean->date_end) {
      $query->condition('n.created', strtotime($bean->date_end), '<');
    }

    switch ($bean->sort) {
      case 'alpha':
        $query->orderBy('td.name');
        break;

      case 'count':
        $query->orderBy('node_count', 'DESC');
        break;
    }

    if ($bean->count) {
      $query->range(0, $bean->count);
    }

    $tids = $query->execute()->fetchCol();
    if ($tids) {
      $terms = taxonomy_term_load_multiple($tids);
      $items = array();
      foreach ($terms as $term) {
        $path = entity_uri('taxonomy_term', $term);
        $items[] = l(entity_label('taxonomy_term', $term), $path['path'], $path['options']);
      }

      $content['bean'][$bean->delta]['terms'] = array(
        '#theme' => 'item_list',
        '#items' => $items,
      );
    }

    // Let any bean styles alter content.
    if (module_exists('bean_style')) {
      bean_style_view_alter($content, $bean);
    }

    return $content;
  }
}
