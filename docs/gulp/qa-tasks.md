[⌂ Table of Contents](/docs/README.md) | [← Previous Article](/docs/gulp/additional-tasks.md) | [Next Article →](/docs/gulp/lighthouse.md)

# QA Tasks
There are several QA-related tasks that can be used to automatically download block images from Figma and visually compare them with the rendered blocks in the WordPress block library. This feature relies on the Figma file being structured correctly and may not work for all blocks.

Before running these tasks for the first time, the following command is needed to install the playwright package on your local environment:

```
npx playwright install
```

* `gulp qa`
	* This task will run both of the following tasks.

* `gulp qa:load`
	* `--blocks` or `--block` - this parameter can be added to this task to only download new images for the specified block(s). Takes a comma-separated list of block(s) to download.
	* `--token` or `--key` - used to specify the Figma API token rather than entering via the prompt.
	* `--file` - used to specify the Figma File ID rather than entering via the prompt.
	* This task will prompt you for two values to connect to the Figma API:
		* Figma API token
		* Figma File ID (found in the Figma URL for the project: https://www.figma.com/file/THIS_IS_THE_FILE_ID/...)
	* It will then automatically download images of the blocks in the Block Library. The Figma components must be structured as follows:
		* Section ending with `-Blocks`
			* Block name/heading containing `-Label`
				* Block ending with `-Desktop` and `-Mobile` that have the block label as a prefix.
	* This task will also create a [/themes/catapult/qa/missing-blocks.json](/themes/catapult/qa/missing-blocks.json) file. This file should be thoroughly reviewed, and the Computan designer should be contacted to fix any issues with mismatched block names. The file contains the following information:
		* Block Labels in Figma without any matching Figma blocks. All block components in Figma should contain the block label as a prefix. The designer will likely need to update the component names to match the block labels.
		* WordPress block library posts not found in Figma. Check to make sure they exist and the WordPress library block post titles match the Figma block component names. The developer will likely need to update the library block post titles to match Figma.
		* Figma blocks not found within WordPress. Check to make sure the block code exists within WordPress and a Library Block post has been created.

* `gulp qa:test`
	* This test uses [BackstopJS ↗](https://github.com/garris/BackstopJS) to compare the blocks in the Block Library with the images downloaded from Figma and outputs a report in the [/themes/catapult/qa/reports/](/themes/catapult/qa/reports/) directory.
	* `--blocks` or `--block` - this parameter can be added to this task to only test specific block(s). Takes a comma-separated list of blocks to test. For example:
		```
		gulp qa:test --blocks="Hero-Standard,Hero-Centered"
		```

## Test Result Files
Both of these files should be thoroughly reviewed to make sure all the blocks from Figma are accounted for and development matches the designs.

* [/themes/catapult/qa/missing-blocks.json](/themes/catapult/qa/missing-blocks.json) - this file contains information about the current design and development status of all the blocks and is used to make sure the Figma file is structured correctly and that development of the blocks is complete and matches the Figma design system. It is updated by the `gulp qa:load` task and contains the following:
	* Block Labels in Figma without any matching Figma blocks. All block components in Figma should contain the block label as a prefix. Contact the Computan designer if any blocks are listed here. They will likely need to update the component names to match the block labels.
	* WordPress block library posts not found in Figma. Check to make sure these blocks exist and the WordPress library block post titles match the Figma block component names. The developer will likely need to update the library block post titles to match Figma.
	* Figma blocks not found within WordPress. Check to make sure the block code exists within WordPress and a Library Block post has been created.
* [/themes/catapult/qa/block-qa-status.json](/themes/catapult/qa/block-qa-status.json) - this file contains information about the latest full automated visual QA test. It gets updated by the `gulp qa:test` task and contains the following:
	* `Failed Blocks` - These are blocks that failed the visual comparison. The `/themes/catapult/qa/reports/html/index.html` and `/themes/catapult/qa/reports/json/jsonReport.json` files can be used to view details about why these blocks do not match.
	* `Missing Blocks` - These are blocks that are missing an image from the Figma file.
	* `Ignored Blocks` - These are the blocks that are ignored by the `blocksToIgnore` setting in the [/themes/catapult/qa/qa-settings.json](/themes/catapult/qa/qa-settings.json) file.
	* `Undeveloped Blocks` - These are blocks that are in Figma but either are missing from the Block Library or haven't been developed yet.
	* `Passing Blocks` - These are blocks that exist and match the Figma designs. The goal is to get every block to appear in this list.

## Configuration Files

* [/themes/catapult/qa/qa-settings.json](/themes/catapult/qa/qa-settings.json) - this file contains settings for adjusting the QA tasks. This file should only be adjusted when needed. It contains the following settings:
	* `blocksToIgnore` - blocks listed in this setting will be totally ignored. Their images will not be downloaded from Figma, and they will not be visually tested. Blocks should only be added to this list if they can not be reasonably tested (such as an open lightbox that requires user interaction before being able to view the block).
	* `blockScenarioOverrides` - This allows setting custom per-block values to the Backstop configuration. Most often used to set a less strict `misMatchThreshold` value for a specific block. This should only be done if there are rendering differences from the Figma file versus the web.
	* `ignoreBlockVariations` - this can be used to ignore specific block variations from the QA test. This should only be used occasionally when there are variations that aren't fully reflected in the block library (such as block's variation for a specific CPT that appears in a sidebar, but doesn't appear in the block library in the same was as the CPT).

## Informational Files

* [/themes/catapult/qa/figma-and-wp-block-names.json](/themes/catapult/qa/figma-and-wp-block-names.json) - this contains the WordPress block library post names, and the associated name of the Figma components with `-1`, `-2`, `-3` etc. value added for block variations.
* [/themes/catapult/qa/figma-block-nodes.json](/themes/catapult/qa/figma-block-nodes.json) - this contains a list of how many block nodes were found matching the WordPress block library posts.
