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
        add_shortcode( 'range_selector', [ $this, 'range_selector' ] );
        add_shortcode( 'custom_range_value', [ $this, 'custom_range_value' ] );
        add_shortcode( 'kunde_categories_tab', [ $this, 'kunde_categories_tab' ] );
	}

    public function kunde_categories_tab() {
        $categories = get_categories([
            'type' => 'kunde',
        ]);
        wp_enqueue_style( 'penneo_styles' );
        $i = 0;
        ob_start();
        ?>
        <div class="penneo-customers-page">
            <div class="penneo-tab-container">
                <?php foreach( $categories as $category ): ?>
                    <div class="penneo-tab-item-container">
                        <span class="penneo-tab-item"><?php echo $category->name; ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="
        </div>
        <?php
        return ob_get_clean();
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
            ?>
            <div class="slider_container">
			<input type='range' class='slider' min='0' max='100' step='0.1' value='0'
				<?php
					array_walk( $atts, function( $value, $key ) {
						echo " " . $key . '="' . $value . '"';
					});
				?>
			/>	
		</div>
        <?php
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

    public function range_selector( $atts, $content ) {
        ob_start();
        ?>
        <div class="range_selector"
            <?php
                array_walk( $atts, function( $value, $key ) {
                    echo " " . $key . '="' . $value . '"';
                });
            ?>
        /><?php echo $content; ?>
        </div>
        <?php
        return ob_get_clean();
    }

    public function custom_range_value( $atts ) {
        if( empty( $atts['from'] ) ) return false;
        ob_start();
        ?>
            <span class="custom_range_value" data-from="<?php echo $atts['from']; ?>"></span>
        <?php
        return ob_get_clean();
    }

}

$penneo_plugin = new Penneo_Plugin();
$penneo_plugin->run();