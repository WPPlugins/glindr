<?php

if(!defined('ABSPATH')) { exit; }

if(!function_exists('gc_api_get_application')) {
	/**
	 * Calls the Glindr API to check the application status for a particular token. If no token is provided,
	 * checks the generic application status (whether applications are open or closed).
	 *
	 * @param string $token The application token for a particular application.
	 * @return bool|array   If no token is provided, returns true or false if applications are open or closed respectively. Otherwise
	 *                      returns an associative array with application status information. On any type of error, return an indicator that 
	 *                      applications are not open.
	 */
	function gc_api_get_application($token = '') {
		$request_url  = add_query_arg(['token' => $token], sprintf('%s/%s', untrailingslashit(GC_REST_BASE_URL), 'status'));
		$request_args = [
			'user-agent' => sprintf('Glindr Client - Version %s', GC_VERISON),
			'headers'    => [],
		];

		if(defined('GC_REST_HTTP_USER') && GC_REST_HTTP_USER && defined('GC_REST_HTTP_PASS') && GC_REST_HTTP_PASS) {
			$request_args['headers']['Authorization'] = sprintf('Basic %s', base64_encode(sprintf('%s:%s', GC_REST_HTTP_USER, GC_REST_HTTP_PASS)));
		}

		$response = wp_remote_get($request_url, $request_args);

		if(is_wp_error($response)) {
			$result = ['applications_open' => false];
		} else if(wp_remote_retrieve_response_code($response) >= 400) {
			$result = ['applications_open' => false];
		} else {
			$response_body = wp_remote_retrieve_body($response);
			$response_obj  = json_decode($response_body, true);

			if(null === $response_obj) {
				$result = ['applications_open' => false];
			} else {
				$result = $response_obj;
			}
		}
		
		return $result;
	}
}

if(!function_exists('gc_api_submit_application')) {
	/**
	 * Calls the Glindr API to submit an application. Returns a WP_Error object if there was an error and a token
	 * for the application otherwise.
	 *
	 * @param string $token           If reapplying, the existing application token. Otherwise, an empty string.
	 * @param string $submitter_email The submitter's email address.
	 * @param string $submitter_name  The submitter's name.
	 * @param string $site_name       The site's name.
	 * @param string $site_url        The site's url (for the home page).
	 * @return WP_Error|string        If the application succeeds, returns a token. Otherwise, return a descriptive error object.
	 */
	function gc_api_submit_application($token, $submitter_email, $submitter_name, $site_name, $site_url) {
		$request_url  = sprintf('%s/%s', untrailingslashit(GC_REST_BASE_URL), 'apply');
		$request_args = [
			'user-agent' => sprintf('Glindr Client - Version %s', GC_VERISON),
			'headers'    => [],
			'body'       => [
				'token'           => $token,
				'submitter_email' => $submitter_email,
				'submitter_name'  => $submitter_name,
				'site_name'       => $site_name,
				'site_url'        => $site_url,
			],
		];

		if(defined('GC_REST_HTTP_USER') && GC_REST_HTTP_USER && defined('GC_REST_HTTP_PASS') && GC_REST_HTTP_PASS) {
			$request_args['headers']['Authorization'] = sprintf('Basic %s', base64_encode(sprintf('%s:%s', GC_REST_HTTP_USER, GC_REST_HTTP_PASS)));
		}

		$response = wp_remote_post($request_url, $request_args);

		if(is_wp_error($response)) {
			return new WP_Error('glindr_submit_application', __('Your application encountered an error. Please ensure you provide your name and a valid email address.'));
		} else if(wp_remote_retrieve_response_code($response) >= 400) {
			return new WP_Error('glindr_submit_application', __('Your application encountered an error. Please ensure you provide your name and a valid email address.'));
		} else {
			$response_body = wp_remote_retrieve_body($response);
			$response_obj  = json_decode($response_body, true);

			if(isset($response_obj['token'])) {
				return $response_obj['token'];
			} else {
				return new WP_Error('glindr_submit_application', __('Your application encountered an error. Please ensure you provide your name and a valid email address.'));
			}
		}
	}
}


if(!function_exists('gc_api_syndicate')) {
	/**
	 * Calls the Glindr API to submit an application. Returns a WP_Error object if there was an error and a token
	 * for the application otherwise.
	 *
	 * @param string $title    The post's title.
	 * @param string $content  The post's content.
	 * @param string $excerpt  The post's excerpt.
	 * @param string $category The post's category.
	 * @param string $url      The post's url.
	 * @param int $image_id    The post's thumbnail id (or 0 if none is assigned).
	 * @param string $token    The token for this site.
	 * @return WP_Error|string If the syndication succeeds, returns a post id for viewing the post on Glindr. Otherwise, return a descriptive error object.
	 */
	function gc_api_syndicate($title, $content, $excerpt, $category, $url, $image_id, $token) {
		$attachment = $image_id && wp_attachment_is_image($image_id) ? get_attached_file($image_id) : false;
		
		$request_url  = sprintf('%s/%s', untrailingslashit(GC_REST_BASE_URL), 'syndicate');
		$request_args = [
			'user-agent' => sprintf('Glindr Client - Version %s', GC_VERISON),
			'headers'    => [],
			'body'       => [
				'title'      => $title,
				'content'    => $content,
				'excerpt'    => $excerpt,
				'category'   => $category,
				'url'        => $url,
				'image_bits' => $attachment ? base64_encode(file_get_contents($attachment)) : '',
				'image_name' => $attachment ? basename($attachment) : '',  
				'token'      => $token,
			],
		];

		if(defined('GC_REST_HTTP_USER') && GC_REST_HTTP_USER && defined('GC_REST_HTTP_PASS') && GC_REST_HTTP_PASS) {
			$request_args['headers']['Authorization'] = sprintf('Basic %s', base64_encode(sprintf('%s:%s', GC_REST_HTTP_USER, GC_REST_HTTP_PASS)));
		}

		$response = wp_remote_post($request_url, $request_args);
		
		if(is_wp_error($response)) {
			return new WP_Error('glindr_syndicate', __('Your syndication encountered an error. You must provide a non-empty title and content.'));
		} else if(wp_remote_retrieve_response_code($response) >= 400) {
			return new WP_Error('glindr_syndicate', __('Your syndication encountered an error. You must provide a non-empty title and content.'));
		} else {
			$response_body = wp_remote_retrieve_body($response);
			$response_obj  = json_decode($response_body, true);

			if(isset($response_obj['post_id'])) {
				return $response_obj['post_id'];
			} else {
				return new WP_Error('glindr_syndicate', __('Your syndication encountered an error. You must provide a non-empty title and content.'));
			}
		}
	}
}
