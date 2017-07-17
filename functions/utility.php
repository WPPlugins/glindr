<?php

if(!defined('ABSPATH')) { exit; }

if(!function_exists('gc_debug')) {
	/**
	 * Function that abstracs error logging. If a log file is specified, then write the data
	 * provided to the log file. Appropriately handle scalar and non-scalar values. Logs all
	 * values passed to the function with their location and line number.
	 *
	 * @return void
	 */
	function gc_debug() {
		if(defined('GC_DEBUG') && GC_DEBUG && is_file(GC_DEBUG) && is_writable(GC_DEBUG)) {
			$variables = func_get_args();
			$backtrace = debug_backtrace();

			$tracefile = str_replace(GC_LISTINGC_PLUGIN_DIRPATH, '', $backtrace[0]['file']);
			$traceline = $backtrace[0]['line'];

			foreach($variables as $variable) {
				$fileline = "{$tracefile}::{$traceline}";

				if(is_scalar($variable)) {
					file_put_contents(GC_DEBUG, "{$fileline} - {$variable}\n", FILE_APPEND);
				} else {
					file_put_contents(GC_DEBUG, "{$fileline} - complex\n", FILE_APPEND);
					file_put_contents(GC_DEBUG, var_export($variable, true), FILE_APPEND);
				}
			}
		}
	}
}

if(!function_exists('gc_dependencies_met')) {
	/**
	 * Returns a value indicating whether all dependencies for the plugin have been met prior to loading most
	 * of the functionality.
	 *
	 * @return array|bool True if all dependencies are met and an array of error messages otherwise.
	 */
	function gc_dependencies_met() {
		$errors = array();
		
		if(version_compare(phpversion(), GC_REQS__PHP_VERSION, 'lt')) {
			$errors['gc_phpversion'] = sprintf(__('The Glindr plugin requires at least <em>PHP %s</em>. Please upgrade your PHP version.'), GC_REQS__PHP_VERSION);
		}

		return empty($errors) ? true : $errors;
	}
}

if(!function_exists('gc_redirect')) {
	/**
	 * Function that abstracs `wp_redirect` so that developers don't
	 * have to remember to call `exit` afterwards.
	 *
	 * @param string $url  The URL to redirect to.
	 * @param int    $code The HTTP status code to use for redirection.
	 * @return void
	 */
	function gc_redirect($url, $code = 302) {
		wp_redirect($url, $code); exit;
	}
}
