[⌂ Table of Contents](/docs/README.md) | [← Previous Article](/docs/gulp/qa-tasks.md) | [Next Article →](/docs/composer/README.md)

# Lighthouse Tasks
Lighthouse scans of the entire site can be done with the following gulp task:

* `gulp lighthouse`
	* `--site=SITEURL` - `SITEURL` should be full URL such as `https://computan.com`. This can be used to manually set the site for scanning.
	* `--auth=AUTH` - this can be used to specify the basic auth. `AUTH` is the value of the username and password (they need to match).
	* `--blockLimit=X` - this parameter can be used to limit the number of blocks from the block library that get scanned. X is an integer.
	* `--noSitemap` - runs the tests without checking the sitemap pages and will only check the individual block library blocks. Useful before production to make sure the individual blocks perform well.
	* `--noBlocks` - runs the tests without checking the individual block library blocks. Useful if the blocks have already been checked and the produced sitemap pages need to be checked.

Note: local scanning doesn't work very well. The `--site` parameter should be used when running scans locally to instead test the dev, staging, or production site.

## Reports
Once the lighthouse scans are complete, reports will be automatically generated at `https://siteurl.com/lighthouse/` with navigation between four different reports for desktop, mobile, blocks desktop, and blocks mobile.

The blocks reports include all the blocks found in the block library. The desktop/mobile reports include pages found in the sitemap. Similar pages (such as blog posts or taxonomy term pages) will be limited 5 per type.