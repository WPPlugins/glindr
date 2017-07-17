<?php if(!defined('ABSPATH')) { exit; } ?>
<div class="wrap">
	<h2><?php _e('Glindr'); ?></h2>

	<?php if(false === $application || (isset($_GET['gc_reapply']) && 'true' === $_GET['gc_reapply'] && is_array($application) && false === $application['applications_open'])) { /* the user has not applied and cannot currently submit applications */ ?>

		<div class="error">
			<p>
				<strong><?php _e('Sorry!'); ?></strong>
				<?php _e('Applications for Glindr syndication are currently closed.'); ?>
			</p>
		</div>
		
		<p><?php _e('Please check back soon to see if applications for Glindr syndication have been opened.'); ?></p>

	<?php } else if(true === $application || (isset($_GET['gc_reapply']) && 'true' === $_GET['gc_reapply'] && is_array($application) && true === $application['applications_open'])) { /* the user has not applied and can currently submit applications */ ?>

		<form action="<?php echo esc_url(admin_url('admin.php')); ?>" method="post">
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row"><label for="gc_submitter_name"><?php _e('Your Name'); ?></label></th>
						<td>
							<input type="text" required class="regular-text" id="gc_submitter_name" name="gc[submitter_name]" value="<?php echo esc_attr(wp_get_current_user()->display_name); ?>" />
						</td>
					</tr>

					<tr>
						<th scope="row"><label for="gc_submitter_email"><?php _e('Your Email'); ?></label></th>
						<td>
							<input type="email" required class="regular-text" id="gc_submitter_email" name="gc[submitter_email]" value="<?php echo esc_attr(wp_get_current_user()->user_email); ?>" />
						</td>
					</tr>

					<tr>
						<th scope="row"><label for=""><?php _e('Site Name'); ?></label></th>
						<td>
							<em><?php echo esc_html(get_bloginfo('name')); ?></em>
						</td>
					</tr>

					<tr>
						<th scope="row"><label for=""><?php _e('Site URL'); ?></label></th>
						<td>
							<em><?php echo esc_html(home_url('/')); ?></em>
						</td>
					</tr>
				</tbody>
			</table>

			<p class="submit">
				<?php wp_nonce_field('gc_submit_application', 'gc_submit_application_nonce'); ?>
				<input type="hidden" name="action" value="gc_submit_application" />
				<input type="submit" name="submit" class="button button-primary" value="<?php _e('Submit Application'); ?>" />
			</p>
		</form>

	<?php } else { /* the user has applied and we can display their status */ ?>

		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row"><?php _e('Application Status'); ?></th>
					<td>
						<em><?php echo esc_html(ucwords($application['status'])); ?></em>
					</td>
				</tr>

				<?php if(isset($application['categories']) && is_array($application['categories']) && !empty($application['categories'])) { ?>
				<tr>
					<th scope="row"><?php _e('Available Categories'); ?></th>
					<td>
						<?php printf('<ul class="gc-nomargin">%s</ul>', implode("\n", array_map(function($category) { return sprintf('<li>%s</li>', esc_html($category)); }, wp_list_pluck($application['categories'], 'name')))); ?>
					</td>
				</tr>
				<?php } ?>

				<?php if(isset($application['syndicated'])) { ?>
				<tr>
					<th scope="row"><?php _e('Syndicated Posts'); ?></th>
					<td>
						<?php printf(__('You have syndicated <strong>%s</strong>.'), sprintf(_n('%s post', '%s posts', $application['syndicated']), number_format_i18n($application['syndicated']))); ?>
					</td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
						
		<?php if('declined' === $application['status'] && true === $application['applications_open']) { ?>
		
		<p><?php _e('If you believe your site has been declined incorrectly, you can reapply for syndication privileges.'); ?></p>
		
		<p><a class="button button-primary" href="<?php echo esc_url(gc_get_settings_page_url([ 'gc_reapply' => 'true' ])); ?>"><?php _e('Reapply Now'); ?></a></p>	
		
		<?php } ?>
		
	<?php } ?>
</div>
