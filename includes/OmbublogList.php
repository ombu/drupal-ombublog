<?php
/**
 * @file
 * Listing of blog posts.
 */

class OmbublogList extends BeanPlugin {
  /**
   * Implements parent::values().
   */
  public function values() {
    return array(
      'uid_type' => 'none',
      'uid' => NULL,
      'category_type' => 'none',
      'category' => NULL,
      'tags_type' => 'none',
      'tags' => array(),
      'date_start' => NULL,
      'date_end' => NULL,
      'count' => 10,
      'pager' => FALSE,
    );
  }

  /**
   * Implements parent::form().
   */
  public function form($bean, $form, &$form_state) {
    $form['uid_type'] = array(
      '#type' => 'radios',
      '#title' => t('Filter by author:'),
      '#options' => array(
        'none' => t('None'),
        'context' => t('Based on the visitors context'),
        'manual' => t('Manually selected'),
      ),
      '#default_value' => $bean->uid_type,
    );

    $form['uid'] = array(
      '#type' => 'textfield',
      '#title' => t('Author'),
      '#autocomplete_path' => 'user/autocomplete',
      '#default_value' => $bean->uid,
      '#description' => t('Show blog posts from selected user.'),
      '#states' => array(
        'visible' => array(
          ':input[name="uid_type"]' => array('value' => 'manual'),
        ),
      ),
    );

    // Get all category terms.
    $category = variable_get('ombublog_category_vocabulary', '');
    if ($category) {
      $category = taxonomy_vocabulary_machine_name_load($category);
      $options = $this->taxonomyOptions($category);

      $form['category_type'] = array(
        '#type' => 'radios',
        '#title' => t('Filter by !category:', array(
          '!category' => strtolower($category->name),
        )),
        '#options' => array(
          'none' => t('None'),
          'context' => t('Based on the visitors context'),
          'manual' => t('Manually selected'),
        ),
        '#default_value' => $bean->category_type,
      );

      $form['category'] = array(
        '#title' => $category->name,
        '#type' => 'select',
        '#options' => $options,
        '#default_value' => $bean->category,
        '#description' => t('Select the !category to show blog posts from.', array('!category' => strtolower($category->name))),
        '#states' => array(
          'visible' => array(
            ':input[name="category_type"]' => array('value' => 'manual'),
          ),
        ),
      );
    }

    // Get all tag terms.
    $tags = variable_get('ombublog_tags_vocabulary', '');
    if ($tags) {
      $tags = taxonomy_vocabulary_machine_name_load($tags);
      $options = $this->taxonomyOptions($tags);

      $form['tags_type'] = array(
        '#type' => 'radios',
        '#title' => t('Filter by !tags:', array(
          '!tags' => strtolower($tags->name),
        )),
        '#options' => array(
          'none' => t('None'),
          'context' => t('Based on the visitors context'),
          'manual' => t('Manually selected'),
        ),
        '#default_value' => $bean->tags_type,
      );

      $form['tags'] = array(
        '#title' => $tags->name,
        '#type' => 'select',
        '#options' => $options,
        '#multiple' => TRUE,
        '#default_value' => $bean->tags,
        '#description' => t('Select the !tags to show blog posts from.', array('!tags' => strtolower($tags->name))),
        '#states' => array(
          'visible' => array(
            ':input[name="tags_type"]' => array('value' => 'manual'),
          ),
        ),
      );
    }

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

    $form['count'] = array(
      '#type' => 'select',
      '#title' => t('Count'),
      '#description' => t('Number of blog posts to show.'),
      '#options' => array_combine(range(1, 20), range(1, 20)),
      '#default_value' => $bean->count,
    );


    $form['pager'] = array(
      '#type' => 'checkbox',
      '#title' => t('Show pager?'),
      '#default_value' => $bean->pager,
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
   * Generate options for a taxonomy select list.
   */
  protected function taxonomyOptions($vocab) {
    $options = array();
    if ($terms = taxonomy_get_tree($vocab->vid)) {
      foreach ($terms as $term) {
        $options[$term->tid] = str_repeat('-', $term->depth) . $term->name;
      }
    }
    return $options;
  }

  /**
   * Implements parent::view().
   */
  public function view($bean, $content, $view_mode = 'default', $langcode = NULL) {
    $query = new EntityFieldQuery();
    $query
      ->entityCondition('entity_type', 'node')
      ->entityCondition('bundle', 'blog_post')
      ->propertyCondition('status', 1);

    if ($uid = $this->getAuthorFilter($bean)) {
      $query->propertyCondition('uid', $uid);
    }

    if ($category = $this->getCategoryFilter($bean)) {
      $query->fieldCondition('field_blog', 'tid', $category);
    }

    if ($tags = $this->getTagsFilter($bean)) {
      $query->fieldCondition('field_tags', 'tid', $category);
    }

    if ($bean->date_start) {
      $query->propertyCondition('created', strtotime($bean->date_start), '>');
    }

    if ($bean->date_end) {
      $query->propertyCondition('created', strtotime($bean->date_end), '<');
    }

    if ($bean->pager) {
      $query->pager($bean->count);
      $content['bean'][$bean->delta]['pager'] = array(
        '#theme' => 'pager',
        '#weight' => 10,
      );
    }
    else {
      $query->range(0, $bean->count);
    }

    $query->propertyOrderBy('created', 'DESC');

    $results = $query->execute();
    if ($results['node']) {
      $nodes = node_load_multiple(array_keys($results['node']));
      $content['bean'][$bean->delta]['#nodes'] = $nodes;

      // Let any bean styles alter content.
      if (module_exists('bean_style')) {
        bean_style_view_alter($content, $bean);
      }
    }

    return $content;
  }

  /**
   * Gets proper author uid based on configured type.
   */
  protected function getAuthorFilter($bean) {
    switch ($bean->uid_type) {
      case 'none':
        return NULL;
        break;

      case 'context':
        if ($node = menu_get_object()) {
          return $node->uid;
        }
        elseif (($user = menu_get_object('user'))) {
          return $user->uid;
        }
        break;

      case 'manual':
        if ($bean->author) {
          return $bean->author;
        }
        break;
    }
  }

  /**
   * Gets proper category tid based on configured type.
   */
  protected function getCategoryFilter($bean) {
    switch ($bean->category_type) {
      case 'none':
        return NULL;
        break;

      case 'context':
        if (($node = menu_get_object()) && $node->type == 'blog_post') {
          $category = field_get_items('node', $node, 'field_blog');
          if ($category) {
            return $category[0]['tid'];
          }
        }
        break;

      case 'manual':
        if ($bean->category) {
          return $bean->category;
        }
        break;
    }
  }

  /**
   * Gets proper tag tids based on configured type.
   */
  protected function getTagsFilter($bean) {
    switch ($bean->tags_type) {
      case 'none':
        return NULL;
        break;

      case 'context':
        if (($node = menu_get_object()) && $node->type == 'blog_post') {
          $tags = field_get_items('node', $node, 'field_tags');
          if ($tags) {
            return $tags[0]['tid'];
          }
        }
        break;

      case 'manual':
        if ($bean->tags) {
          return $bean->tags;
        }
        break;
    }
  }
}
