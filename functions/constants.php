<?php

if(!defined('ABSPATH')) { exit; }

/**
 * Table of Contents for constans defined in this file:
 *
 * 1.0 Meta names
 * 1.1 Option names
 * 2.0 Settings page
 * 3.0 Rest
 * 4.0 Requirements
 */

// 1.0 Meta names

if(!defined('GC_DATA_META_NAME__POST_SYNDICATED_ID')) {
	define('GC_DATA_META_NAME__POST_SYNDICATED_ID', 'gc_syndicated_id');
}

// 1.1 Option names

if(!defined('GC_DATA_OPTS_NAME__APPLICATION')) {
	define('GC_DATA_OPTS_NAME__APPLICATION', 'gc_application');
}


if(!defined('GC_DATA_OPTS_NAME__APPLICATION_TOKEN')) {
	define('GC_DATA_OPTS_NAME__APPLICATION_TOKEN', 'gc_application_token');
}

// 2.0 Settings page

if(!defined('GC_PAGE_SLUG_NAME__SETTINGS')) {
	define('GC_PAGE_SLUG_NAME__SETTINGS', 'gc_settings');
}

// 3.0 REST API

if(!defined('GC_REST_BASE_URL')) {
	define('GC_REST_BASE_URL', 'http://glindr.com/wp-json/glindr/1.0');
}

if(!defined('GC_GLINDR_BASE_URL')) {
	define('GC_GLINDR_BASE_URL', 'http://glindr.com');
}

// 4.0 Requirements

if(!defined('GC_REQS__PHP_VERSION')) {
	define('GC_REQS__PHP_VERSION', '5.4');
}
