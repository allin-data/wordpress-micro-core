<?php
/**
 * Weather template
 */
$settings = $this->get_settings_for_display();
?>
<div class="jet-weather">
	<div class="jet-weather__container"><?php
		echo $this->get_weather_title();

		if ( isset( $settings['show_current_weather'] ) && 'true' === $settings['show_current_weather'] ) {
			include $this->__get_global_template( 'current' );
		}

		if ( isset( $settings['show_forecast_weather'] ) && 'true' === $settings['show_forecast_weather'] ) {
			include $this->__get_global_template( 'forecast' );
		}
	?></div>
</div>
