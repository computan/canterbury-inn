[⌂ Table of Contents](/docs/README.md) | [← Previous Article](/docs/setup/README.md) | [Next Article →](/docs/setup/local-setup.md)

# System Setup

MacOS is the recommended OS (Windows has been lightly tested but might be buggy). The following global packages need to be installed (recommend installing via [Brew ↗](https://brew.sh/)):

* [PHP 8.2 ↗](https://formulae.brew.sh/formula/php): `brew install php@8.2`
* [Composer ↗](https://formulae.brew.sh/formula/composer): `brew install composer`
* [Gulp CLI ↗](https://formulae.brew.sh/formula/gulp-cli): `brew install gulp-cli`
* [NVM ↗](https://formulae.brew.sh/formula/nvm): `brew install nvm`
* `Node 20`: `nvm install 20` and `nvm use 20`

[Local ↗](https://localwp.com/) is the recommended software for setting up local environments, and [VS Code ↗](https://code.visualstudio.com/) is the recommended IDE. This documentation will assume you are using both.

## Managing PHP Versions
Different projects often use different versions of PHP. Before beginning work on a project, be sure to check the [composer.json](/themes/catapult/composer.json) file's `config->platform->php` setting. Older versions of Catapult will use PHP 8.0. Pre-Catapult versions of the Computan Base Theme usually use 7.4.

To install a version on your system, run (using 8.2 for example):

```brew install php@8.2```

To switch which version is used by your system, the brew `unlink` and `link` commands must both be used to unlink the previous version and link the version you want to use:

```brew unlink php; brew link php@8.2```

After that command runs, use the following command to make sure your system is now using the correct version:

```php -v``` 

Using the wrong version will cause errors with [PHP linting](/docs/best-practices/linting/php-phpcs.md) so using the correct system version is essential.

Alternatively, [https://localheinz.com/articles/2020/05/05/switching-php-versions-when-using-homebrew/ ↗](https://localheinz.com/articles/2020/05/05/switching-php-versions-when-using-homebrew/) has instructions for creating a Zsh shell script to easily change PHP versions by just typing the version number `X.X` in the terminal:

1. Open the `~/.zshrc` file and add the following code:
	```
	# determine versions of PHP installed with HomeBrew
	installedPhpVersions=($(brew ls --versions | ggrep -E 'php(@.*)?\s' | ggrep -oP '(?<=\s)\d\.\d' | uniq | sort))

	# create alias for every version of PHP installed with HomeBrew
	for phpVersion in ${installedPhpVersions[*]}; do
		value="{"

		for otherPhpVersion in ${installedPhpVersions[*]}; do
			if [ "${otherPhpVersion}" = "${phpVersion}" ]; then
				continue;
			fi

			value="${value} brew unlink php@${otherPhpVersion};"
		done

		value="${value} brew link php@${phpVersion} --force --overwrite; } &> /dev/null && echo \"PHP version changed to ${phpVersion}\" || echo \"Error changing PHP version to ${phpVersion}\""

		alias "${phpVersion}"="${value}"
	done
	```
2. Restart your Zsh terminal. Typing `8.2`, for example, should switch the current brew PHP version to 8.2.

## Managing Node Versions
Different projects often use different versions of Node. Before beginning work on a project, be sure to check the [package.json](/themes/catapult/package.json) file's `engines->node` setting. Older versions of Catapult will use Node 16. Pre-Catapult versions of the Computan Base Theme use either 16 or 12. The current version of Catapult uses Node 20.

To install a version on your system, run (using 20 for example):

```nvm install 20```

To then switch to that version run:

```nvm use 20```

You can also use the following command to see which versions are already on your system and which is currently being used:

```nvm list```

Using the wrong version will most likely cause errors when you run `npm install` during [local setup](/docs/setup/local-setup.md).