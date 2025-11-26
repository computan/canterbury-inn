[⌂ Table of Contents](/docs/README.md) | [← Previous Article](/docs/gulp/primary-tasks.md) | [Next Article →](/docs/gulp/additional-tasks.md)

# Gulp Block Tasks

## Creating new ACF Gutenberg Blocks

To automatically create the starting php, scss, and ACF json files for a new block, run:
```
gulp block:add
```

This will prompt you for some settings (all fields are required):

* `Block title` - the title of your block as it will appear in the editor (use capitalization and spaces as needed). This will also be converted into the slug/directory/class name of your block.
* `Block description` - a sentence to describe your block within the editor.
* `WordPress dashicon slug` - the slug for the WordPress icon - pick one that resembles the block content from https://developer.wordpress.org/resource/dashicons/
* `Keywords` - terms used to find the block in the editor search field. Separate terms using commas.

The task will then automatically create the following files:
* /themes/*ThemeName*/blocks/acf-blocks/*Block-Slug*/`block.php`
* /themes/*ThemeName*/blocks/acf-blocks/*Block-Slug*/src/`style.scss`
* /themes/*ThemeName*/blocks/acf-blocks/*Block-Slug*/src/`editor.scss`
* /themes/*ThemeName*/acf-json/`group_`*`uniqueid`*`.json`

The block.php file and style.scss files will then be automatically opened in your system's default editor.

---

## Removing ACF Gutenberg Blocks

To remove exists ACF Gutenberg Blocks, run:
```
gulp block:remove
```

You will then be prompted to select which blocks to remove (select blocks with the `space` key). For each block, you must confirm that you want to delete all corresponding files.

`NOTE:` this task will remove any associated ACF json files, however it does NOT remove these fields from the WordPress database. You must also go to the "Custom Fields" page in the WordPress dashboard and manually delete these fields there.