<?php

namespace GC\Components\Data\Opts;

if(!defined('ABSPATH')) { exit; }

class Application {

	public static function init() {
		self::add_actions();
		self::add_filters();
	}

	private static function add_actions() {
		if(is_admin()) {
			// Admin only actions
		} else {
			// Frontend only actions
		}

		// Admin and frontend actions
	}

	private static function add_filters() {
		if(is_admin()) {
			// Admin only filters
		} else {
			// Frontend only filters
		}

		// Admin and frontend filters
	}

	#region Public API

	public static function get() {
		$token = gc_get_application_token();
		$token = false === $token ? '' : $token;

		$result = get_transient(GC_DATA_OPTS_NAME__APPLICATION);

		if(!is_array($result) || defined('GC_CONFIG__APPLICATION_FRESH') && GC_CONFIG__APPLICATION_FRESH) {
			$result = gc_api_get_application($token);
			
			if(is_wp_error($result)) {
				$result = gc_api_get_application('');
			}
			
			if(!is_wp_error($result)) {
				set_transient(GC_DATA_OPTS_NAME__APPLICATION, $result, 15 * MINUTE_IN_SECONDS);
			}
		}
		
		if(is_array($result) && isset($result['status'])) {
			return $result; 
		} else if(is_array($result) && isset($result['applications_open'])) {
			return true === $result['applications_open'];
		} else {
			return false;
		}
	}

	public static function get_token() {
		$token = get_option(GC_DATA_OPTS_NAME__APPLICATION, '');

		return empty($token) ? false : $token;
	}

	public static function set_token($token) {
		$token = trim($token);

		if(empty($token)) {
			delete_option(GC_DATA_OPTS_NAME__APPLICATION);
		} else {
			update_option(GC_DATA_OPTS_NAME__APPLICATION, $token);
		}

		return true;
	}

	#endregion Public API
}

require_once(path_join(dirname(__FILE__), 'functions/application.functions.php'));

Application::init();
