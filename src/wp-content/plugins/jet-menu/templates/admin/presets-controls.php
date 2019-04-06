<?php
/**
 * Presets manager view
 */
?>
<div class="cherry-ui-kit cherry-control">
	<div class="cherry-control__info">
		<h4 class="cherry-ui-kit__title cherry-control__title" role="banner"><?php
			esc_html_e( 'Create Preset', 'jet-menu' );
		?></h4>
		<div class="cherry-ui-kit__description cherry-control__description" role="note"><?php
			esc_html_e( 'Create new preset from current options configuration', 'jet-menu' );
		?></div>
	</div>
	<div class="cherry-ui-kit__content cherry-control__content" role="group">
		<div class="cherry-ui-container">
			<div class="cherry-ui-control-preset-wrapper">
				<input type="text" class="cherry-ui-text jet-preset-name" placeholder="<?php esc_html_e( 'Preset Name', 'jet-menu' ); ?>">
				<?php $this->preset_action( 'jet-menu-create-preset', esc_html__( 'Save', 'jet-menu' ) ); ?>
			</div>
		</div>
	</div>
</div>
<div class="cherry-ui-kit cherry-control">
	<div class="cherry-control__info">
		<h4 class="cherry-ui-kit__title cherry-control__title" role="banner"><?php
			esc_html_e( 'Update Preset', 'jet-menu' );
		?></h4>
		<div class="cherry-ui-kit__description cherry-control__description" role="note"><?php
			esc_html_e( 'Save current options configuration to existing preset', 'jet-menu' );
		?></div>
	</div>
	<div class="cherry-ui-kit__content cherry-control__content" role="group">
		<div class="cherry-ui-container">
			<div class="cherry-ui-control-preset-wrapper"><?php
				$presets = $this->get_presets();
				if ( ! empty( $presets ) ) {
					$this->preset_select(
						'jet-update-preset',
						esc_html__( 'Select preset to update...', 'jet-menu' )
					);
					$this->preset_action( 'jet-menu-update-preset', esc_html__( 'Update', 'jet-menu' ) );
				} else {
					esc_html_e( 'You haven\'t created any presets yet', 'jet-menu' );
				}
			?></div>
		</div>
	</div>
</div>
<div class="cherry-ui-kit cherry-control">
	<div class="cherry-control__info">
		<h4 class="cherry-ui-kit__title cherry-control__title" role="banner"><?php
			esc_html_e( 'Apply This Preset Globally', 'jet-menu' );
		?></h4>
		<div class="cherry-ui-kit__description cherry-control__description" role="note"><?php
			esc_html_e( 'Load preset to use it for all menu locations', 'jet-menu' );
		?></div>
	</div>
	<div class="cherry-ui-kit__content cherry-control__content" role="group">
		<div class="cherry-ui-container">
			<div class="cherry-ui-control-preset-wrapper"><?php
				$presets = $this->get_presets();
				if ( ! empty( $presets ) ) {
					$this->preset_select(
						'jet-load-preset',
						esc_html__( 'Select preset to apply...', 'jet-menu' )
					);
					$this->preset_action( 'jet-menu-load-preset', esc_html__( 'Load', 'jet-menu' ) );
				} else {
					esc_html_e( 'You haven\'t created any presets yet', 'jet-menu' );
				}
			?></div>
		</div>
	</div>
</div>
<div class="cherry-ui-kit cherry-control jet-delete-preset-wrap">
	<div class="cherry-control__info">
		<h4 class="cherry-ui-kit__title cherry-control__title" role="banner"><?php
			esc_html_e( 'Delete Preset', 'jet-menu' );
		?></h4>
		<div class="cherry-ui-kit__description cherry-control__description" role="note"><?php
			esc_html_e( 'Delete existing preset', 'jet-menu' );
		?></div>
	</div>
	<div class="cherry-ui-kit__content cherry-control__content" role="group">
		<div class="cherry-ui-container">
			<div class="cherry-ui-control-preset-wrapper"><?php
				$presets = $this->get_presets();
				if ( ! empty( $presets ) ) {
					$this->preset_select(
						'jet-delete-preset',
						esc_html__( 'Select preset to delete...', 'jet-menu' )
					);
					$this->preset_action( 'jet-menu-delete-preset', esc_html__( 'Delete', 'jet-menu' ) );
				} else {
					esc_html_e( 'You haven\'t created any presets yet', 'jet-menu' );
				}
			?></div>
		</div>
	</div>
</div>
