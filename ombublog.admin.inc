<?php

/**
 * @file
 * Admin callbacks for ombublog.module.
 */

/**
 * Blog settings admin form.
 */
function ombublog_settings_form($form, &$form_state) {
  $vocabs = taxonomy_vocabulary_get_names();
  foreach ($vocabs as $vocab) {
    $options[$vocab->machine_name] = $vocab->name;
  }

  $form['ombublog_category'] = array(
    '#type' => 'select',
    '#title' => t('Category vocabulary'),
    '#description' => t('Select the vocabulary to use for blog categories'),
    '#default_value' => variable_get('ombublog_category_vocabulary', ''),
    '#options' => $options,
  );

  $form['ombublog_tags'] = array(
    '#type' => 'select',
    '#title' => t('Tag vocabulary'),
    '#description' => t('Select the vocabulary to use for blog tags'),
    '#default_value' => variable_get('ombublog_tag_vocabulary', ''),
    '#options' => $options,
  );

  return system_settings_form($form);
}
