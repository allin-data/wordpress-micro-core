<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$popup_id = get_the_ID();

$uniq_popup_id = 'jet-popup-' . $popup_id;

$meta_settings = get_post_meta( $popup_id, '_elementor_page_settings', true );

$popup_settings_main = wp_parse_args( $meta_settings, jet_popup()->generator->popup_default_settings );

$close_button_html = '';

$use_close_button = isset( $popup_settings_main['use_close_button'] ) ? filter_var( $popup_settings_main['use_close_button'], FILTER_VALIDATE_BOOLEAN ) : true;

if ( isset( $popup_settings_main['close_button_icon'] ) && $use_close_button ) {
	$close_button_icon = $popup_settings_main['close_button_icon'];
	$close_button_html = sprintf( '<div class="jet-popup__close-button"><i class="%s"></i></div>', $close_button_icon );
}

$overlay_html = '';

$use_overlay = isset( $popup_settings_main['use_overlay'] ) ? filter_var( $popup_settings_main['use_overlay'], FILTER_VALIDATE_BOOLEAN ) : true;

if ( $use_overlay ) {
	$overlay_html = '<div class="jet-popup__overlay"></div>';
}

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<?php if ( ! current_theme_supports( 'title-tag' ) ) : ?>
		<title><?php echo wp_get_document_title(); ?></title>
		<?php endif; ?>
		<?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?>>
		<div id="<?php echo $uniq_popup_id; ?>" class="jet-popup jet-popup--front-mode jet-popup--single-preview">
			<div class="jet-popup__inner">
				<?php echo $overlay_html; ?>
				<div class="jet-popup__container">
					<?php echo $close_button_html; ?>
					<div class="jet-popup__container-inner">
						<div class="jet-popup__container-overlay"></div><?php

					do_action( 'jet-popup/blank-page/before-content' );

					while ( have_posts() ) :
						the_post();
						the_content();
					endwhile;

					do_action( 'jet-popup/blank-page/after-content' );
					?>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
