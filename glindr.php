<?php
/*
Plugin Name: Glindr
Plugin URI:  http://glindr.com/
Description: Quickly and easily syndicate your posts to <em>Glindr</em>.
Version:     1.0.0
Author:      Glindr
Author URI:  http://glindr.com/
*/

if(!defined('ABSPATH')) { exit; }

if(!defined('GC_CACHE_PERIOD')) {
	define('GC_CACHE_PERIOD', HOUR_IN_SECONDS * 12);
}

if(!defined('GC_VERSION')) {
	define('GC_VERSION', '1.0.0');
}

if(!defined('GC_PLUGIN_BASENAME')) {
	define('GC_PLUGIN_BASENAME', plugin_basename(__FILE__));
}

if(!defined('GC_PLUGIN_DIRPATH')) {
	define('GC_PLUGIN_DIRPATH', trailingslashit(dirname(__FILE__)));
}

if(!defined('GC_PLUGIN_FILEPATH')) {
	define('GC_PLUGIN_FILEPATH', __FILE__);
}

// Constants required for the plugin (with their documented explanations)
require_once(path_join(GC_PLUGIN_DIRPATH, 'functions/constants.php'));

// API communication with the Glindr server
require_once(path_join(GC_PLUGIN_DIRPATH, 'functions/glindr.php'));

// Various utility functions required prior to the plugin being load
require_once(path_join(GC_PLUGIN_DIRPATH, 'functions/utility.php'));

add_action('plugins_loaded', function() {
	if(true === ($errors = gc_dependencies_met())) {
		// Actions - Application
		require_once(path_join(GC_PLUGIN_DIRPATH, 'components/actions/application/application.php'));

		// Actions - Types
		require_once(path_join(GC_PLUGIN_DIRPATH, 'components/actions/types/post/post.php'));

		// Data - Meta
		require_once(path_join(GC_PLUGIN_DIRPATH, 'components/data/meta/post/post.php'));

		// Data - Options
		require_once(path_join(GC_PLUGIN_DIRPATH, 'components/data/opts/application/application.php'));

		// Views - Settings page
		require_once(path_join(GC_PLUGIN_DIRPATH, 'components/views/settings-page/settings-page.php'));

		// Views - Post types
		require_once(path_join(GC_PLUGIN_DIRPATH, 'components/views/types/post/post.php'));

		// CLI commands are only loaded when the WP_CLI framework is present
		if(defined('WP_CLI') && WP_CLI) {

		}
	} else {
		add_action('admin_notices', function() use ($errors) {
			printf('<div class="error" id="glindr-server-errors"><p>%s</div>', implode('<br />', $errors));
		});
	}
}, 11);
