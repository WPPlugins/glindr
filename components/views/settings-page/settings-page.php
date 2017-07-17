<?php

namespace GC\Components\Views;

if(!defined('ABSPATH')) { exit; }

class SettingsPage {

	public static function init() {
		self::add_actions();
		self::add_filters();
	}

	private static function add_actions() {
		if(is_admin()) {
			// Admin only actions
			add_action('admin_menu',                        [__CLASS__, 'add']);
			add_action('\GC\Components\Views\SettingsPage::load', [__CLASS__, 'enqueue_resources']);
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
		add_filter('\GC\Components\Views\SettingsPage::get_settings',      [__CLASS__, 'add_settings_defaults'], 199, 3);
		add_filter('\GC\Components\Views\SettingsPage::sanitize_settings', [__CLASS__, 'add_settings_defaults'], 199, 3);
	}

	#region Scripts and Styles

	public static function enqueue_resources() {
		wp_enqueue_style('gc-settings-page', plugins_url('resources/settings-page.css', __FILE__), [], GC_VERSION);
		wp_enqueue_script('gc-settings-page', plugins_url('resources/settings-page.js', __FILE__), ['jquery'], GC_VERSION, true);
		wp_localize_script('gc-settings-page', 'GC_SettingsPage', [

		]);
	}

	#endregion Scripts and Styles

	#region Add

	public static function add() {
		$page = add_options_page(__('Glindr'), __('Glindr'), 'manage_options', GC_PAGE_SLUG_NAME__SETTINGS, [__CLASS__, 'display']);

		add_action("load-{$page}", [__CLASS__, 'load']);
	}

	#endregion Add

	#region Display

	public static function display() {
		do_action('\GC\Components\Views\SettingsPage::display');

		$application = gc_get_application();
		
		include(path_join(dirname(__FILE__), 'views/settings-page.php'));
	}

	#endregion Display

	#region Load

	public static function load() {
		do_action('\GC\Components\Views\SettingsPage::load');
	}

	#endregion Load

	#region Public API

	public static function get_setting_field_id($setting_name) {
		return sprintf('%s-%s', GC_DATA_OPTS_NAME__SETTINGS, $setting_name);
	}

	public static function get_setting_field_name($setting_name, $multiple = false) {
		return sprintf('%s[%s]%s', GC_DATA_OPTS_NAME__SETTINGS, $setting_name, ($multiple ? '[]' : ''));
	}
	
	public static function get_url($query_args) {
		return add_query_arg(array_merge([
			'page' => GC_PAGE_SLUG_NAME__SETTINGS,
		], $query_args), admin_url('options-general.php'));
	}

	#endregion Public API
}

require_once(path_join(dirname(__FILE__), 'functions/functions.settings-page.php'));

SettingsPage::init();
