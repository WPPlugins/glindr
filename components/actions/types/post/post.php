<?php

namespace GC\Components\Actions\Types;

if(!defined('ABSPATH')) { exit; }

class Post {

	public static function init() {
		self::add_actions();
		self::add_filters();
	}

	private static function add_actions() {
		if(is_admin()) {
			// Admin only actions
			add_action('save_post', [__CLASS__, 'save_post'], 10, 2);
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

	#region Save Post

	public static function save_post($post_id, $post) {
		$data = stripslashes_deep($_POST);

		$nonce_a = 'gc_syndicate';
		$nonce   = isset($data['gc_syndicate_nonce']) ? $data['gc_syndicate_nonce'] : '';
		$nonce_v = $nonce && wp_verify_nonce($nonce, $nonce_a);

		if(current_user_can('edit_post', $post_id) && 'publish' === $post->post_status && $nonce_v) {
			$submitted = isset($data['gc']) ? $data['gc'] : array();
			$submitted = shortcode_atts([
				'syndicate_post' => 'no',
				'syndicate_cat'   => 0,
			], $submitted);

			if('yes' === $submitted['syndicate_post']) {
				$syndicated_id = gc_api_syndicate($post->post_title, $post->post_content, $post->post_excerpt, $submitted['syndicate_cat'], get_permalink($post->ID), get_post_thumbnail_id($post->ID), gc_get_application_token());

				if(is_wp_error($syndicated_id)) {
					// Swallow this
				} else {
					gc_set_post_syndicated_id($post, $syndicated_id);
				}
			}
		}
	}

	#endregion Save Post
}

Post::init();
