# mig33
Contributors: GamerZ  
Donate link: http://lesterchan.net/site/donation/  
Tags: mig33, miniblog, blog, post, social network  
Requires at least: 3.7  
Tested up to: 3.8  
Stable tag: master  
License: GPLv2 or later  
License URI: http://www.gnu.org/licenses/gpl-2.0.html  

Share a post to mig33's Miniblog whenever you publish a post in WordPress.

## Description

This is mig33's WordPress plugin. Right now, you can automatically share a post to mig33's Miniblog whenever you publish a post in your WordPress's blog. More features to come soon.

## Changelog

### 1.0.0
* Initial release

## Installation

1. Upload `mig33` folder to the `/wp-content/plugins/` directory
2. Activate the `mig33` plugin through the 'Plugins' menu in WordPress
3. You can access `mig33` via `WP-Admin -> Settings -> mig33`

## Screenshots

1. mig33 Administrator Page
2. mig33 Miniblog
3. Posts Shared on mig33 Miniblog
4. mig33 Miniblog Single Post Page

## Frequently Asked Questions

### How to embed mig33's Follow Button?
* Place the following code anywhere in your theme:
```
<?php if( function_exists( 'mig33_follow_button' ) ): ?>  
	<?php mig33_follow_button(); ?>  
<?php endif; ?>
```
* You can embed another mig33 user follow button as well: Eg. `<?php mig33_follow_button( 'lesterchan' ); ?>`
* First Argument: mig33's Username

### How to embed mig33's Share Button?
* Place the following code anywhere within the [WordPress loop](http://codex.wordpress.org/The_Loop "WordPress Loop") in your theme:
```
<?php if( function_exists( 'mig33_share_button' ) ): ?>  
	<?php mig33_share_button(); ?>  
<?php endif; ?>
```
* You can overwrite the default values: Eg.  `<?php mig33_share_button( 'Lester Chan\'s Website', 'http://lesterchan.net', 'lesterchan' ); ?>`
* First Argument: Page Title
* Second Argument: Link To The Page
* Third Argument: mig33's Username

## Upgrade Notice

N/A
