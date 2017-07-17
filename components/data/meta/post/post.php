<?php

namespace GC\Components\Data\Meta;

if(!defined('ABSPATH')) { exit; }

class Post {

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

	public static function get($post) {
		$post = get_post($post);;

		if(null === $post || 'post' !== $post->post_type) { return false; }

		$original_url = get_post_meta($post->ID, GC_DATA_META_NAME__POST_SYNDICATED_ID, true);

		return empty($original_url) ? false : $original_url;
	}

	public static function set($post, $original_url) {
		$post = get_post($post);;

		if(null === $post || 'post' !== $post->post_type) { return false; }

		update_post_meta($post->ID, GC_DATA_META_NAME__POST_SYNDICATED_ID, $original_url);
	}

	#endregion Public API
}

require_once(path_join(dirname(__FILE__), 'functions/post.functions.php'));

Post::init();
