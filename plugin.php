<?php
/*
 * Plugin Name: Penneo Plugin
 * PluginURI: http://www.renesejling.dk
 * Description: Custom Plugin for the customization plugin of Penneo Website
 * Author: René Sejling
 * Version: 1.0.0
 */


class Penneo_Plugin {

	public function run() {
        add_action( 'wp_enqueue_scripts', [ $this, 'wp_enqueue_scripts' ] );
        add_shortcode( 'custom_range_input', [ $this, 'custom_range_input' ] );
        add_shortcode( 'custom_range_sum', [ $this, 'custom_range_sum' ] );
	}

    public function wp_enqueue_scripts() {
        wp_register_style( 'penneo_styles', plugins_url( '/penneo-plugin/css/styles.css' ) );
        wp_register_script( 'penneo_script', plugins_url( '/penneo-plugin/js/script.js' ) );
    }

	public function custom_range_input( $atts ) {
		if( empty( $atts['data-values'] ) ) return false;
		ob_start();
        wp_enqueue_style( 'penneo_styles' );
		$values = explode( ',', $atts['data-values'] );
		echo "<input type='range' min='0' max='" . ( count( $values ) - 1 ) . "' step='1' value='0' ";
		array_walk( $atts, function( $value, $key ) {
			echo " " . $key . '="' . $value . '"';
		});
		echo "/>";
		return ob_get_clean();
	}

    public function custom_range_sum( $atts ) {
        if( empty( $atts['left'] ) || empty( $atts['right'] ) ) return false;
        ob_start();
        wp_enqueue_script( 'penneo_script' );
?>
    <span id="custom_range_sum" data-left="<?php echo $atts['left']; ?>" data-right="<?php echo $atts['right']; ?>" />
<?php
        return ob_get_clean();
    }

}

$penneo_plugin = new Penneo_Plugin();
$penneo_plugin->run();