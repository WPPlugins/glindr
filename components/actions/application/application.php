<?php

namespace GC\Components\Actions;

if(!defined('ABSPATH')) { exit; }

class Request {

	public static function init() {
		self::add_actions();
		self::add_filters();
	}

	private static function add_actions() {
		if(is_admin()) {
			// Admin only actions
			add_action('admin_action_gc_submit_application', [__CLASS__, 'process']);
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

	#region Process

	public static function process() {
		$data = stripslashes_deep($_POST);

		$nonce_a = 'gc_submit_application';
		$nonce   = isset($data['gc_submit_application_nonce']) ? $data['gc_submit_application_nonce'] : '';
		$nonce_v = $nonce && wp_verify_nonce($nonce, $nonce_a);

		if(current_user_can('manage_options') && $nonce_v) {
			$submitted = isset($data['gc']) ? $data['gc'] : array();
			$submitted = shortcode_atts([
				'token'           => gc_get_application_token(),
				'submitter_email' => '',
				'submitter_name'  => '',
				'site_name'       => get_bloginfo('site_name'),
				'site_url'        => home_url('/'),
			], $submitted);

			$token = gc_api_submit_application($submitted['token'], $submitted['submitter_email'], $submitted['submitter_name'], $submitted['site_name'], $submitted['site_url']);

			if(is_wp_error($token)) {
				add_settings_error('general', 'settings_updated', $token->get_error_message(), 'error');
			} else {
				gc_set_application_token($token);

				add_settings_error('general', 'settings_updated', __('Your application was submitted successfully. A Glindr administrator will process it shortly.'), 'updated');
			}

			set_transient('settings_errors', get_settings_errors(), 30);

			gc_redirect(gc_get_settings_page_url([ 'settings-updated' => 'true' ]));
		} else {
			wp_die(__('Cheatin&#8217; uh?'));
		}
	}

	#endregion Process
}

Request::init();
