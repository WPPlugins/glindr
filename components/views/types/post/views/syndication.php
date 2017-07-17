<?php if(!defined('ABSPATH')) { exit; } ?>

<p>
	<input type="hidden" name="gc_syndicate_post" value="no" />
	<label>
		<input type="checkbox" id="gc_syndicate_post" name="gc[syndicate_post]" value="yes" />
		<?php _e('Syndicate to Glindr'); ?>
	</label>
</p>

<div class="categorydiv" id="gc_syndicate_cats">
	<div class="tabs-panel">
		<ul>
			<?php foreach($application['categories'] as $index => $category) { ?>
			<li class="selectit">
				<label>
					<input type="checkbox" class="gc_syndicate_cat" name="gc[syndicate_cat][]" value="<?php echo esc_attr($category['id']); ?>" />
					<?php echo esc_html($category['name']); ?>
				</label>
			</li>
			<?php } ?>
		</ul>
	</div>
</div>

<?php wp_nonce_field('gc_syndicate', 'gc_syndicate_nonce'); ?>
