<?php
/**
 * Skins actions
 */
?>
<div class="jet-skins-actions">
	<a href="<?php $this->synch_skins_link(); ?>" class="cx-button cx-button-success-style"><?php
		_e( 'Synchronise Skins Library', 'jet-theme-core' );
	?></a>
	<?php

		$license = $this->manager->get( 'license' );

		if ( $license ) {

			if ( null === $this->has_license ) {
				$this->has_license = $license->get_license();
			}

			if ( empty( $this->has_license ) ) {
				printf(
					'<a href="%1$s" class="cx-button cx-button-primary-style">%2$s</a>',
					$license->get_current_page_link(),
					__( 'Activate License to Install Skins', 'jet-theme-core' )
				);
			}

		}

	?>
</div>