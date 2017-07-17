<?php

use GC\Components\Data\Opts\Application;

if(!defined('ABSPATH')) { exit; }

if(!function_exists('gc_get_application')) {
	/**
	 * Returns the data for this site's application to the Glindr syndication program.
	 *
	 * @return mixed Returns true if applications are open and the site has not applied, false if applications are not open and
	 *               the site has not applied, and an array of data regarding the applications status otherwise.
	 */
	function gc_get_application() {
		return apply_filters('gc_get_application', Application::get());
	}
}

if(!function_exists('gc_get_application_token')) {
	/**
	 * Gets the token for this site's application to the Glindr syndication program.
	 *
	 * @return bool|string False if no token is set and the token otherwise.
	 */
	function gc_get_application_token() {
		return apply_filters('gc_get_application_token', Application::get_token());
	}
}


if(!function_exists('gc_set_application_token')) {
	/**
	 * Sets the token for this site's application to the Glindr syndication program.
	 *
	 * @param array $token The application token to set for the plugin.
	 * @return bool True if the token was set appropriately and false otherwise.
	 */
	function gc_set_application_token($token) {
		return apply_filters('gc_set_application_token', Application::set_token($token), $token);
	}
}
