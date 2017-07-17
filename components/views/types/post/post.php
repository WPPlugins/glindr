<?php

namespace GC\Components\Views\Types;

if(!defined('ABSPATH')) { exit; }

class Post {

	public static function init() {
		self::add_actions();
		self::add_filters();
	}

	private static function add_actions() {
		if(is_admin()) {
			// Admin only actions
			add_action('add_meta_boxes_post', [__CLASS__, 'add_meta_boxes']);
			add_action('admin_enqueue_scripts', [__CLASS__, 'enqueue_resources']);
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

	#region Meta Boxes - Add

	public static function add_meta_boxes($post) {
		$application = gc_get_application();

		if(is_array($application) && 'approved' === $application['status']) {
			add_meta_box('gc_request', __('Syndication'), [__CLASS__, 'display_meta_boxes'], get_current_screen(), 'side', 'high');
		}
	}

	#endregion Meta Boxes - Add

	#region Meta Boxes - Display

	public static function display_meta_boxes($post) {
		$application   = gc_get_application();
		$syndicated_id = gc_get_post_syndicated_id($post);

		if(false !== $syndicated_id) {
			printf('<div><strong>%s:</strong><br /><a href="%s" target="_blank">%s</a></div>', esc_html__('Post Syndicated'), esc_url(add_query_arg(['p' => $syndicated_id], GC_GLINDR_BASE_URL)), esc_html__('View on Glindr'));
		} else if(is_array($application) && 'approved' === $application['status']) {
			include(path_join(dirname(__FILE__), 'views/syndication.php'));
		}
	}

	#endregion Meta Boxes - Display
	
	#region Resources
	
	public static function enqueue_resources() {
		$screen = get_current_screen();
		
		if(isset($screen->post_type) && 'post' === $screen->post_type && isset($screen->base) && 'post' === $screen->base) {
			wp_enqueue_style('wp-pointer');
			wp_enqueue_script('gc-views-post', plugins_url('resources/post.js', __FILE__), ['jquery', 'wp-pointer'], GC_VERSION, true);
			wp_localize_script('gc-views-post', 'GC_Views_Post', [
				'pointers' => [
					'categories' => [
						'content'  => sprintf('<h3>%s</h3><p>%s</p>', __('Please Choose a Category'), __('You must choose at least one category to syndicate this post to Glindr.')),
						'position' => [
							'align' => 'middle',
							'edge'  => 'right'
						]
					],
					
					'content' => [
						'content'  => sprintf('<h3>%s</h3><p>%s</p>', __('Please Write Something'), __('You must provide some content to syndicate this post to Glindr.')),
						'position' => [
							'align' => 'left',
							'edge'  => 'top'
						]
					],
					
					'title' => [
						'content'  => sprintf('<h3>%s</h3><p>%s</p>', __('Please Provide a Title'), __('You must enter a title to syndicate this post to Glindr.')),
						'position' => [
							'align' => 'left',
							'edge'  => 'top'
						]
					],
					
					'syndication' => [
						'content'  => sprintf('<h3>%s</h3><p>%s</p>', __('Syndication not Selected'), __('You selected a syndication category - did you mean to syndicate this post?')),
						'position' => [
							'align' => 'middle',
							'edge'  => 'right'
						]
					],
				]
			]);
		}
	}
	
	#endregion Resouces
}

Post::init();
