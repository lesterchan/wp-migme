# migme
Contributors: GamerZ  
Donate link: http://lesterchan.net/site/donation/  
Tags: migme, mig33, miniblog, blog, post, social network  
Requires at least: 3.7  
Tested up to: 5.3  
Stable tag: trunk  
License: GPLv2 or later  
License URI: http://www.gnu.org/licenses/gpl-2.0.html  

Share a post to migme's Miniblog whenever you publish a post in WordPress.

## Description
This is migme's WordPress plugin. Right now, you can automatically share a post to migme's Miniblog whenever you publish a post in your WordPress's blog. More features to come soon.

### Build Status
[![Build Status](https://travis-ci.org/lesterchan/wp-migme.svg?branch=master)](https://travis-ci.org/lesterchan/wp-migme)

### Donations
I spent most of my free time creating, updating, maintaining and supporting these plugins, if you really love my plugins and could spare me a couple of bucks, I will really appreciate it. If not feel free to use it without any obligations.

## Changelog

### 1.0.1
* mig33 is now migme
* Do not allow space in tags

### 1.0.0
* Initial release

## Installation

1. Upload `migme` folder to the `/wp-content/plugins/` directory
2. Activate the `migme` plugin through the 'Plugins' menu in WordPress
3. You can access `migme` via `WP-Admin -> Settings -> migme`

## Screenshots

1. migme Administrator Page
2. migme Miniblog
3. Posts Shared on migme Miniblog
4. migme Miniblog Single Post Page

## Frequently Asked Questions

### How to embed migme's Follow Button?
* Place the following code anywhere in your theme:
<code>
<?php if( function_exists( 'migme_follow_button' ) ): ?>  
	<?php migme_follow_button(); ?>  
<?php endif; ?>
</code>

* You can embed another migme user follow button as well: Eg. `<?php migme_follow_button( 'lesterchan' ); ?>`
* First Argument: migme's Username

### How to embed migme's Share Button?
* Place the following code anywhere within the [WordPress loop](http://codex.wordpress.org/The_Loop "WordPress Loop") in your theme:
<code>
<?php if( function_exists( 'migme_share_button' ) ): ?>  
	<?php migme_share_button(); ?>  
<?php endif; ?>
</code>
* You can overwrite the default values: Eg.  `<?php migme_share_button( 'Lester Chan\'s Website', 'http://lesterchan.net', 'lesterchan' ); ?>`
* First Argument: Page Title
* Second Argument: Link To The Page
* Third Argument: migme's Username

## Upgrade Notice

N/A
