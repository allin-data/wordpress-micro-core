<?php if (!defined('WPO_VERSION')) die('No direct access allowed');

$options = WP_Optimize()->get_options();

if (!isset($options) || !$options->get_option('compression_server')) {
	global $task_manager;
	$task_manager->set_default_options();
}

$smush_options['compression_server'] = $options->get_option('compression_server');
$smush_options['lossy_compression'] = $options->get_option('lossy_compression');
$smush_options['back_up_original'] = $options->get_option('back_up_original');
$smush_options['preserve_exif'] = $options->get_option('preserve_exif');
$smush_options['autosmush'] = $options->get_option('autosmush');
$smush_options['image_quality'] = $options->get_option('image_quality');

$custom = 100 == $smush_options['image_quality'] || 90 == $smush_options['image_quality'] ? false : true;
?>

<div id="wpo_smush_settings">
	<p>
		<?php _e('Note: Currently this feature uses third party services from reSmush.it and Nitrosmush (by iSenseLabs). The performance of these free smushing services may be limited for large workloads. We are working on a premium service.', 'wp-optimize'); ?>
	</p>
	<div class="wpo-fieldgroup">
		<div class="autosmush">
			<div class="align-left">
				<label class="switch">
					<input type="checkbox" id="smush-automatically" <?php checked($smush_options['autosmush']); ?> >
					<span class="slider round"></span>
				</label>
			</div>
			<div class="align-left">
				<h3><?php _e('Automatically compress newly-added images', 'wp-optimize');?>
				<b data-tooltip="<?php _e('The images will be added to a background queue, which will start automatically within the next hour. This avoids the site from freezing during media uploads. The time taken to complete the compression will depend upon the size and quantity of the images.', 'wp-optimize');?>"><span class="dashicons dashicons-editor-help"></span> </b>
				</h3>
			</div>

		</div>
		<div class="compression_options">
			<h3><?php _e('Global options', 'wp-optimize');?></h3>
			<input type="radio" id="enable_lossy_compression" name="compression_level" <?php checked($smush_options['image_quality'], 90); ?> class="smush-options compression_level"> 
			<label for="chk_thumbnail"><?php _e('Prioritize maximum compression', 'wp-optimize');?></label>
			<b data-tooltip="<?php _e('Uses lossy compression to ensure maximum savings per image, the resulting images are of a slightly lower quality', 'wp-optimize');?>"><span class="dashicons dashicons-editor-help"></span> </b>
			<br>						
			<input type="radio" id="enable_lossless_compression" <?php checked($smush_options['image_quality'], 100); ?>name="compression_level" class="smush-options compression_level"> 
			<label for="chk_thumbnail"><?php _e('Prioritize retention of detail', 'wp-optimize');?></label>
			<b data-tooltip="<?php _e('Uses lossless compression, which results in much better image quality but lower filesize savings per image', 'wp-optimize');?>"><span class="dashicons dashicons-editor-help"></span> </b>
			<br>
			<input id="enable_custom_compression" <?php checked($custom); ?> type="radio" name="compression_level" class="smush-options compression_level"> 
			<label for="chk_thumbnail"><?php _e('Custom', 'wp-optimize');?></label>
			<br>
			<div class="smush-options custom_compression" <?php if (!$custom) echo 'style="display:none;"';?> >
				<span><?php _e('Maximum Compression', 'wp-optimize');?></span>
				<input id="custom_compression_slider" class="compression_level" data-max="Maximum Compression"  type="range" step="2" value="<?php echo $smush_options['image_quality']; ?>" min="89" max="100" list="number" />
				<datalist id="number">
					<option value="89"/>
					<option value="93"/>
					<option value="95"/>
					<option value="97"/>
					<option value="100"/>
				</datalist>
				<span><?php _e('Best Image Quality', 'wp-optimize');?></span>
			</div>
		</div>
		<div class="save-options"> 
			<input type="button" id="wpo_smush_images_save_options_button" style="display:none" class="wpo_primary_small button-primary" value="<?php _e('Save options', 'wp-optimize'); ?>" />
			<img id="wpo_smush_images_save_options_spinner" class="display-none" src="<?php esc_attr_e(admin_url('images/spinner-2x.gif')); ?>" alt="...">
			<span id="wpo_smush_images_save_options_done" class="dashicons dashicons-yes display-none"><?php _e('Saved options', 'wp-optimize');?></span>
			<span id="wpo_smush_images_save_options_fail" class="dashicons dashicons-no display-none"><?php _e('Failed to save options', 'wp-optimize');?></span>
		</div>
		<p class="toggle-smush-advanced"><?php _e('Show advanced options', 'wp-optimize');?></p>
		<div class="smush-advanced">
			<div class="compression_server">
				<h3><?php _e('Compression service', 'wp-optimize');?></h3>
				<div> <input type="radio" name="compression_server" id="resmushit" value="resmushit" <?php checked($smush_options['compression_server'], 'resmushit'); ?> >			  
				<label for="resmushit">
					<h2><?php _e('reSmush.it', 'wp-optimize');?></h2>
					<p><?php _e('Can keep EXIF data', 'wp-optimize');?></p>
					<small><?php _e('Service provided by reSmush.it', 'wp-optimize'); ?></small>
				  </label>
				</div>
				<div> <input type="radio" name="compression_server" id="nitrosmush" value="nitrosmush" <?php checked($smush_options['compression_server'], 'nitrosmush'); ?> >
				  <label for="nitrosmush">
					<h2><?php _e('Nitrosmush', 'wp-optimize');?></h2>
					<p><?php _e('Max image size - 100MB', 'wp-optimize');?></p>
					  <small> <?php _e('Service provided by iSenseLabs', 'wp-optimize'); ?></small>
				  </label>
				</div>
			</div>
			<br>
			<div class="image_options">
				<input type="checkbox" id="smush-backup-original" class="smush-options back_up_original" <?php checked($smush_options['back_up_original']); ?> > 
				<label for="chk_thumbnail"><?php _e('Backup original images', 'wp-optimize');?></label>
				<b data-tooltip="<?php _e('The original images are stored alongside the compressed images, you can visit the edit screen of the individual images in the Media Library to restore them.', 'wp-optimize');?>"><span class="dashicons dashicons-editor-help"></span> </b>						
				<br>
				<input type="checkbox" id="smush-preserve-exif" class="smush-options preserve_exif" <?php checked($smush_options['preserve_exif']); ?> > 
				<label for="chk_thumbnail" class="smush-options preserve_exif"><?php _e('Preserve EXIF data', 'wp-optimize');?></label><br>
			</div>
		</div>
	</div>
	<div class="wpo-fieldgroup uncompressed-images">
		<h3><?php _e('Uncompressed images', 'wp-optimize');?></h3>
		<div class="wpo_smush_images_buttons_wrap">
			<div class="smush-select-actions. align-left">
				<a href="javascript:;" id="wpo_smush_images_select_all"><?php _e('Select all', 'wp-optimize');?></a> /
				<a href="javascript:;" id="wpo_smush_images_select_none"><?php _e('Select none', 'wp-optimize');?></a>
			</div>
			<div class="smush-refresh-icon align-right">
				<a href="javascript:;" id="wpo_smush_images_refresh"><?php _e('Refresh image list', 'wp-optimize');?> 
					<span class="dashicons dashicons-image-rotate"></span>
				</a>
				<img class="wpo_smush_images_loader" width="16" height="16" src="<?php echo admin_url(); ?>/images/spinner-2x.gif" />
			</div>
		</div>
		<div id="wpo_smush_images_grid"></div>
		<div class="smush-actions">
			<input type="button" id="wpo_smush_images_btn" class="wpo_primary_small button-primary align-left" value="<?php _e('Compress the selected images', 'wp-optimize'); ?>" />
			<input type="button" id="wpo_smush_get_logs" class="wpo_smush_get_logs wpo_primary_small button-primary align-right" value="<?php _e('View logs', 'wp-optimize'); ?>" />
		</div>
	</div>
</div>

<div id="wpo_smush_images_information_container" style="display:none;">
	<div id="wpo_smush_images_information_wrapper"> 
	<h3 id="wpo_smush_images_information_heading"><?php _e('Compressing images', 'wp-optimize');?></h3>
	<h4 id="wpo_smush_images_information_server"></h3>
	<div class="progress-bar orange stripes">
		<span style="width: 100%"></span>
	</div>
	<p><?php _e('The selected images are being processed; please do not close the browser', 'wp-optimize');?></p>
	<table id="smush_stats" class="smush_stats_table">
		<tbody>
			<tr class="smush_stats_row">
				<td> <?php _e('Images pending', 'wp-optimize');?></td>
				<td id="smush_stats_pending_images">&nbsp;...</td>
			</tr>
			<tr class="smush_stats_row">
				<td> <?php _e('Images completed', 'wp-optimize');?></td>
				<td id="smush_stats_completed_images">&nbsp;...</td>
			</tr>
			<tr class="smush_stats_row">
				<td> <?php _e('Size savings', 'wp-optimize');?></td>
				<td id="smush_stats_bytes_saved">&nbsp;...</td>
			</tr>
			<tr class="smush_stats_row">
				<td> <?php _e('Average savings per image', 'wp-optimize');?></td>
				<td id="smush_stats_percent_saved">&nbsp;...</td>
			</tr>
			<tr class="smush_stats_row">
				<td> <?php _e('Time elapsed', 'wp-optimize');?></td>
				<td id="smush_stats_timer">&nbsp;</td>
			</tr>
		</tbody>
	</table>
	</div>
	<input type="button" class="wpo_primary_small button-primary wpo_smush_stats_cta_btn" value="<?php _e('Cancel', 'wp-optimize'); ?>" />
</div>

<div id="smush-complete-summary" class="complete-animation" style="display:none;">
	<span class="dashicons dashicons-no-alt close"></span>
	<div class="animation"> 
		<div class="checkmark-circle">
		  <div class="background"></div>
		  <div class="checkmark draw"></div>
		</div>
	</div>
	<div id="summary-message"></div>
	<input type="button" id="wpo_smush_get_logs" class="wpo_smush_get_logs wpo_primary_small button-primary" value="<?php _e('View logs', 'wp-optimize'); ?>" />
	<input type="button" id="wpo_smush_clear_stats_btn" class="wpo_primary_small button-primary align-right" value="<?php _e('Clear compression statistics', 'wp-optimize'); ?>" />
	<img id="wpo_smush_images_clear_stats_spinner" class="display-none align-right" src="<?php esc_attr_e(admin_url('images/spinner-2x.gif')); ?>" alt="...">
	<span id="wpo_smush_images_clear_stats_done" class="dashicons dashicons-yes display-none save-done align-right"></span>
	<span class="clearfix"></span>
	<input type="button" class="wpo_primary_small button-primary wpo_smush_stats_cta_btn" value="<?php _e('Close', 'wp-optimize'); ?>" />
</div>

<div id="smush-log-modal" class="complete-animation" style="display:none;">
	<div id="log-panel"></div>
	<a href="#" class="wpo_primary_small button-primary"> <?php _e('Download log file', 'wp-optimize'); ?></a>
	<input type="button" class="wpo_primary_small button-primary close" value="<?php _e('Close', 'wp-optimize'); ?>" />
</div>

<div id="smush-information-modal" style="display:none;">
	<div id="smush-information"></div>
	<input type="button" class="wpo_primary_small button-primary information-modal-close" value="<?php _e('Close', 'wp-optimize'); ?>" />
</div>
