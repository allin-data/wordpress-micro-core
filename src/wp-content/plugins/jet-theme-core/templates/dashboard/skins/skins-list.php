<?php
/**
 * Skins list template
 */
?>
<div class="jet-skins">
<?php foreach ( $skins as $slug => $skin ) : ?>
<div class="jet-skin">
	<div class="jet-skin__wrap">
		<div class="jet-skin__thumb">
			<img src="<?php echo $skin['thumb'] ?>" alt="" class="jet-skin__thumb-img">
		</div>
		<div class="jet-skin__content">
			<h5 class="jet-skin__title"><?php echo $skin['name']; ?></h5>
			<div class="jet-skin__actions">
				<?php $this->install_skin_link( $slug ); ?>
				<a href="<?php echo $skin['demo']; ?>" class="cx-button cx-button-normal-style" target="_blank"><?php
					_e( 'Live Demo', 'jet-theme-core' );
				?></a>
			</div>
		</div>
	</div>
</div>
<?php endforeach; ?>
</div>