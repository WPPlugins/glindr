<?php

use \GC\Components\Views\SettingsPage;

if(!defined('ABSPATH')) { exit; }

if(!function_exists('gc_get_setting_field_id')) {
	function gc_get_setting_field_id($setting_name) {
		$setting_field_id = SettingsPage::get_setting_field_id($setting_name);

		return apply_filters('gc_get_setting_field_id', $setting_field_id, $setting_field_id, $setting_name);
	}
}

if(!function_exists('gc_get_setting_field_name')) {
	function gc_get_setting_field_name($setting_name, $multiple = false) {
		$setting_field_name = SettingsPage::get_setting_field_name($setting_name, $multiple);

		return apply_filters('gc_get_setting_field_name', $setting_field_name, $setting_field_name, $setting_name, $multiple);
	}
}

if(!function_exists('gc_get_settings_page_url')) {
	/**
	 * Returns the URL to the Glindr settings page.
	 *
	 * @param array $query_args An array of query arguments to append to the required ones. Optional.
	 * @return string           The URL with appropriate query arguments of the Glindr settings page.
	 */
	function gc_get_settings_page_url($query_args = array()) {
		return apply_filters('gc_get_settings_page_url', SettingsPage::get_url($query_args), $query_args);
	}
}
