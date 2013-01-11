<?php
if (!defined('WP_PARSE_API_PATH')) die('.______.');

if (!current_user_can('manage_options')) {
	wp_die(__('You do not have sufficient permissions to access this page.'));
}
?>
<div class="wrap">
	<div id="icon-options-general" class="icon32"><br></div>
	<h2>Parse Api</h2>

	<p>Register your app on <a href="http://parse.com" target="_blank">parse.com</a> then complete this form with the information about your app.</p>

	<h3>Settings</h3>

	<form action="options.php" method="post">
		<?php settings_fields('wp-parse-api-settings-group'); ?>
		<?php //do_settings('wp-parse-api-settings-group'); ?>
		<table class="form-table">
			<tr valign="top">
				<th  scope="row">App ID</th>
				<td><input type="text" name="app_id" value="<?php echo get_option('app_id'); ?>"></td>
			</tr>
			<tr valign="top">
				<th  scope="row">App Masterkey</th>
				<td><input type="text" name="app_masterkey" value="<?php echo get_option('app_masterkey'); ?>"></td>
			</tr>
			<tr valign="top">
				<th  scope="row">App Rest Key</th>
				<td><input type="text" name="app_restkey" value="<?php echo get_option('app_restkey'); ?>"></td>
			</tr>
		</table>
		
		<h3>Parse Object</h3>
		
		<p>
			Also you need to create a <strong>class</strong> in the <strong>Data Browser</strong> with a name you like or create an <strong>class</strong> named <strong>Post</strong>, 
			with those fields:
		</p>

		<p>
			<ul>
				<li>- categories (Array)</li>
				<li>- content (String)</li>
				<li>- date (String)</li>
				<li>- thumbnail (Object)</li>
				<li>- title (String)</li>
				<li>- wpId (Number)</li>
			</ul>
		</p>
		
		<table class="form-table">
			<tr valign="top">
				<th  scope="row">Object Name*</th>
				<td>
					<input type="text" name="object_name" value="<?php echo get_option('object_name'); ?>">
				</td>
			</tr>
		</table>
		
		<?php submit_button(); ?>
		
		<p>* if null object name <strong>Post</strong> will be used.</p>
	</form>
	
	<form action="<?php echo admin_url( 'admin-post.php' ); ?>">
		<h3>Sync</h3>
		
		<p>If you have old post that you want to upload into your parse app, then use this button.</p>
		
		<input type="hidden" name="action" value="wp_parse_api_sync">
		
		<?php submit_button('Sync'); ?>
	</form>
</div>