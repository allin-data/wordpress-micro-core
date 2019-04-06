<?php
/**
 * SVG circle template
 */

$settings   = $this->get_settings_for_display();
$size       = is_array( $settings['circle_size'] ) ? $settings['circle_size']['size'] : $settings['circle_size'];
$radius     = $size / 2;
$center     = $radius;
$viewbox    = sprintf( '0 0 %1$s %1$s', $size );
$val_stroke = is_array( $settings['value_stroke'] ) ? $settings['value_stroke']['size'] : $settings['value_stroke'];
$bg_stroke  = is_array( $settings['bg_stroke'] ) ? $settings['bg_stroke']['size'] : $settings['bg_stroke'];

// Fix radius relative to stroke
$max    = ( $val_stroke >= $bg_stroke ) ? $val_stroke : $bg_stroke;
$radius = $radius - ( $max / 2 );

$value = 0;

if ( 'percent' === $settings['values_type'] ) {
	$value = $settings['percent_value']['size'];
} elseif ( 0 !== absint( $settings['absolute_value_max'] ) ) {

	$curr  = $settings['absolute_value_curr'];
	$max   = $settings['absolute_value_max'];
	$value = round( ( ( absint( $curr ) * 100 ) / absint( $max ) ), 0 );
}

$circumference = 2 * M_PI * $radius;

$meter_stroke = ( 'color' === $settings['bg_stroke_type'] ) ? $settings['val_bg_color'] : 'url(#circle-progress-meter-gradient-' . $this->get_id() . ')';
$value_stroke = ( 'color' === $settings['val_stroke_type'] ) ? $settings['val_stroke_color'] : 'url(#circle-progress-value-gradient-' . $this->get_id() . ')';

// Tablet size data.
$tablet_size    = is_array( $settings['circle_size_tablet'] ) ? $settings['circle_size_tablet']['size'] : $settings['circle_size_tablet'];
$tablet_size    = ! empty( $tablet_size ) ? $tablet_size : $size;
$tablet_viewbox = sprintf( '0 0 %1$s %1$s', $tablet_size );
$tablet_center  = $tablet_size / 2;

$tablet_val_stroke = is_array( $settings['value_stroke_tablet'] ) ? $settings['value_stroke_tablet']['size'] : $settings['value_stroke_tablet'];
$tablet_val_stroke = ! empty( $tablet_val_stroke ) ? $tablet_val_stroke : $val_stroke;
$tablet_bg_stroke  = is_array( $settings['bg_stroke_tablet'] ) ? $settings['bg_stroke_tablet']['size'] : $settings['bg_stroke_tablet'];
$tablet_bg_stroke  = ! empty( $tablet_bg_stroke ) ? $tablet_bg_stroke : $bg_stroke;

$tablet_max    = ( $tablet_val_stroke >= $tablet_bg_stroke ) ? $tablet_val_stroke : $tablet_bg_stroke;
$tablet_radius = ( $tablet_size / 2 ) - ( $tablet_max / 2 );

$tablet_circumference = 2 * M_PI * $tablet_radius;

// Mobile size data.
$mobile_size    = is_array( $settings['circle_size_mobile'] ) ? $settings['circle_size_mobile']['size'] : $settings['circle_size_mobile'];
$mobile_size    = ! empty( $mobile_size ) ? $mobile_size : $tablet_size;
$mobile_viewbox = sprintf( '0 0 %1$s %1$s', $mobile_size );
$mobile_center  = $mobile_size / 2;

$mobile_val_stroke = is_array( $settings['value_stroke_mobile'] ) ? $settings['value_stroke_mobile']['size'] : $settings['value_stroke_mobile'];
$mobile_val_stroke = ! empty( $mobile_val_stroke ) ? $mobile_val_stroke : $tablet_val_stroke;
$mobile_bg_stroke  = is_array( $settings['bg_stroke_mobile'] ) ? $settings['bg_stroke_mobile']['size'] : $settings['bg_stroke_mobile'];
$mobile_bg_stroke  = ! empty( $mobile_bg_stroke ) ? $mobile_bg_stroke : $tablet_bg_stroke;

$mobile_max    = ( $mobile_val_stroke >= $mobile_bg_stroke ) ? $mobile_val_stroke : $mobile_bg_stroke;
$mobile_radius = ( $mobile_size / 2 ) - ( $mobile_max / 2 );

$mobile_circumference = 2 * M_PI * $mobile_radius;

$responsive_sizes = array(
	'desktop' => array(
		'size'          => $size,
		'viewBox'       => $viewbox,
		'center'        => $center,
		'radius'        => $radius,
		'valStroke'     => $val_stroke,
		'bgStroke'      => $bg_stroke,
		'circumference' => $circumference,
	),
	'tablet' => array(
		'size'          => $tablet_size,
		'viewBox'       => $tablet_viewbox,
		'center'        => $tablet_center,
		'radius'        => $tablet_radius,
		'valStroke'     => $tablet_val_stroke,
		'bgStroke'      => $tablet_bg_stroke,
		'circumference' => $tablet_circumference,
	),
	'mobile' => array(
		'size'          => $mobile_size,
		'viewBox'       => $mobile_viewbox,
		'center'        => $mobile_center,
		'radius'        => $mobile_radius,
		'valStroke'     => $mobile_val_stroke,
		'bgStroke'      => $mobile_bg_stroke,
		'circumference' => $mobile_circumference,
	),
);

$val_bg_gradient_angle     = ! empty( $settings['val_bg_gradient_angle'] ) ? $settings['val_bg_gradient_angle'] : 0;
$val_stroke_gradient_angle = ! empty( $settings['val_stroke_gradient_angle'] ) ? $settings['val_stroke_gradient_angle'] : 0;

?>
<svg class="circle-progress" width="<?php echo $size; ?>" height="<?php echo $size; ?>" viewBox="<?php echo $viewbox; ?>" data-radius="<?php echo $radius; ?>" data-circumference="<?php echo $circumference; ?>" data-responsive-sizes="<?php echo esc_attr( json_encode( $responsive_sizes ) ); ?>">
	<linearGradient id="circle-progress-meter-gradient-<?php echo $this->get_id(); ?>" gradientUnits="objectBoundingBox" gradientTransform="rotate(<?php echo $val_bg_gradient_angle; ?> 0.5 0.5)" x1="-0.25" y1="0.5" x2="1.25" y2="0.5">
		<stop offset="0%" stop-color="<?php echo $settings['val_bg_gradient_color_a']; ?>"/>
		<stop offset="100%" stop-color="<?php echo $settings['val_bg_gradient_color_b']; ?>"/>
	</linearGradient>
	<linearGradient id="circle-progress-value-gradient-<?php echo $this->get_id(); ?>" gradientUnits="objectBoundingBox" gradientTransform="rotate(<?php echo $val_stroke_gradient_angle; ?> 0.5 0.5)" x1="-0.25" y1="0.5" x2="1.25" y2="0.5">
		<stop offset="0%" stop-color="<?php echo $settings['val_stroke_gradient_color_a']; ?>"/>
		<stop offset="100%" stop-color="<?php echo $settings['val_stroke_gradient_color_b']; ?>"/>
	</linearGradient>
	<circle
		class="circle-progress__meter"
		cx="<?php echo $center; ?>"
		cy="<?php echo $center; ?>"
		r="<?php echo $radius; ?>"
		stroke="<?php echo $meter_stroke; ?>"
		stroke-width="<?php echo $bg_stroke; ?>"
		fill="none"
	/>
	<circle
		class="circle-progress__value"
		cx="<?php echo $center; ?>"
		cy="<?php echo $center; ?>"
		r="<?php echo $radius; ?>"
		stroke="<?php echo $value_stroke; ?>"
		stroke-width="<?php echo $val_stroke; ?>"
		data-value="<?php echo $value; ?>"
		style="stroke-dasharray: <?php echo $circumference; ?>; stroke-dashoffset: <?php echo $circumference; ?>;"
		fill="none"
	/>
</svg>
