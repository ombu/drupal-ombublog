OmbuBlogs
=========

Provides the functionality for multi-section blogs.  Each blog section is 
autonomous, meaning it contains its own search, blocks, and archive.

Setup
-----

In order for the multi-section functionality to work, another module needs to 
define a vocabulary to use for the blog sections (in order to keep the ombublog 
module as reusable as possible).  This module needs to create the vocabulary and 
assign the vocabulary machine name to the `ombublog_category_vocabulary` variable.

This can be done either in a feature or in a `hook_install()`.  Here's an
example of a `hook_install()` implementation:

    /**
     * Implements hook_install().
     */
    function my_module_install() {
      $vocab = (object) array(
        'name' => t('Blog Types'),
        'description' => t('The available blog types.'),
        'machine_name' => 'blogs',
        'help' => $help,

      );
      taxonomy_vocabulary_save($vocab);
      variable_set('ombublog_category_vocabulary', $vocab->machine_name);
    }

An important note: make sure whatever module loads the blogs vocabulary is
installed _before_ ombublog, since ombublog depends on the
`ombublog_category_vocabulary` variable during install.
