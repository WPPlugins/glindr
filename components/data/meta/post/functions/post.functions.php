<?php

use GC\Components\Data\Meta\Post;

if(!defined('ABSPATH')) { exit; }

if(!function_exists('gc_get_post_syndicated_id')) {
	/**
	 * Returns the syndicated id for a particular Post.
	 *
	 * @param int|WP_Post $post Post ID or WP_Post object indicating the Post to get data for.
	 * @return mixed      Returns false if the Post specified is invalid or no syndicated id is set. Returns the syndicated id if one is set.
	 */
	function gc_get_post_syndicated_id($post) {
		return apply_filters('gc_get_post_syndicated_id', Post::get($post), $post);
	}
}

if(!function_exists('gc_set_post_syndicated_id')) {
	/**
	 * Sets the data for a particular request.
	 *
	 * @param int|WP_Post $post          Post ID or WP_Post object indicating the Post to set the syndicated id for.
	 * @param array       $syndicated_id The syndicated id to set for the Post.
	 * @return bool       True if the data was set appropriately and false otherwise.
	 */
	function gc_set_post_syndicated_id($post, $syndicated_id) {
		return apply_filters('gc_set_post_syndicated_id', Post::set($post, $syndicated_id), $post, $syndicated_id);
	}
}
