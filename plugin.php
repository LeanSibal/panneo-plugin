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
        add_shortcode( 'range_left_text', [ $this, 'range_left_text' ] );
        add_shortcode( 'range_right_text', [ $this, 'range_right_text' ] );
		add_action('wp_ajax_get_post_modal', [ $this, 'get_post_modal' ] );
		add_action('wp_ajax_nopriv_get_post_modal', [ $this, 'get_post_modal' ] );
	}

    public function range_left_text() {
        ob_start();
        ?>
        <span id="range_left_text"></span>
        <?php
        return ob_get_clean();
    }

    public function range_right_text() {
        ob_start();
        ?>
        <span id="range_right_text"></span>
        <?php
        return ob_get_clean();
    }

    public  function get_post_modal(){
        if( empty( $_POST['post_id'] ) ) wp_die();
        $post = wp_get_single_post( $_POST['post_id'] );
        $featured_image = get_the_post_thumbnail_url( $_POST['post_id'], 'large' );
        echo json_encode([
            'post_title' => $post->post_title,
            'post_content' => $post->post_content,
            'permalink' => get_permalink( $_POST['post_id'] ),
            'featured_image' => $featured_image
        ]);
        wp_die();
    }

    public function popup(){
        ob_start();
        ?>
        <div class="modal fade" id="penneo-modal" role="dialog" aria-labelledby="important-msg-label" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <img id="modal-featured_image"/>
                            </div>
                            <div class="col-md-6">
                                <div class="text-content">
                                    <button type="button" class="close" data-dismiss="modal">
                                        LUK CASEN
                                        <span aria-hidden="true">&times;</span>
                                        <span class="sr-only">Close</span>
                                    </button>
                                    <div class="text-group-content">
                                        <h2 id="modal-post_title"></h2>
                                        <p id="modal-post_content"></p>
                                        <a id="modal-permalink" href="#">BOOK EN DEMO</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        echo ob_get_clean();
    }
    public function kunde_categories_tab() {
        $categories = get_categories([
            'type' => 'kunde',
        ]);
        add_action('wp_footer', [ $this, 'popup' ] );
        wp_localize_script('penneo_script', 'penneo', [
            'ajax_url' => admin_url( 'admin-ajax.php' ) . "?action=get_post_modal"
        ]);
        $customers = get_posts([
            'post_type' => 'kunde'
        ]);
        wp_enqueue_script( 'penneo_script' );
        wp_enqueue_style( 'jquery.bxslider' );
        wp_enqueue_style( 'bootstrap.modal' );
        wp_enqueue_style( 'penneo_styles' );
        $i = 0;
        ob_start();
        ?>
        <div class="penneo-customers-page">
            <div class="penneo-tab-container">
                <?php foreach( $categories as $category ): ?>
                    <div class="penneo-tab-item-container">
                        <span class="penneo-tab-item <?php echo ( $i++ == 0 ) ? "penneo-tab-active" : ""; ?>" data-category_id="<?php echo $category->cat_ID; ?>"><?php echo $category->name; ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
			<?php $i = 0 ; ?>
			<div class="penneo-tab-content-container">
				<?php foreach( $categories as $category ): ?>
				<div class="penneo-tab-content <?php echo ( $i++ == 0 ) ? "penneo-tab-current" : ""; ?>" data-category_id="<?php echo $category->cat_ID; ?>">
					<div class="penneo-slider">
						<?php foreach( get_posts([ 'category' => $category->cat_ID ] ) as $__post ): ?>
						<div class="penneo-slider-container container" data-post_id="<?php echo $__post->ID; ?>">
							<div class="row">
								<div class="col-sm-6 post-slider-text">
									<div class="penneo-white-line"></div>
									<h2><?php echo $__post->post_title; ?></h2>
								</div>
								<div class="col-sm-6 post-slider-image">
									<?php echo get_the_post_thumbnail( $__post->ID ); ?>
								</div>
							</div>
						</div>
						<?php endforeach; ?>
					</div>
                    <div class="container">
                        <div class="row">
                        <?php foreach( get_posts(['category' => $category->cat_ID, 'post_type' => 'kunde' ]) as $client  ): ?>
                        <?php $image = get_field( 'client_logo', $client->ID ); ?>
                            <div class="col-md-5ths">
                                <img src="<?php echo $image['sizes']['medium']; ?>" />
                            </div>
                        <?php endforeach; ?>
                        </div>
                    </div>
				</div>
				<?php endforeach; ?>
			</div>
        </div>
        <?php
        return ob_get_clean();
    }

    public function wp_enqueue_scripts() {
        wp_register_style( 'penneo_styles', plugins_url( '/penneo-plugin/css/styles.css' ) );
        wp_register_style( 'jquery.bxslider', plugins_url( '/penneo-plugin/css/jquery.bxslider.css' ) );
        wp_register_style( 'bootstrap.modal', plugins_url( '/penneo-plugin/css/bootstrap.min.css' ) );
        wp_register_style( 'bootstrap-theme.modal', plugins_url( '/penneo-plugin/css/bootstrap-theme.min.css' ) );
        wp_register_script( 'penneo_script', plugins_url( '/penneo-plugin/js/script.js' ), [ 'jquery.bxslider', 'bootstrap.modal' ] );
        wp_register_script( 'bootstrap.modal', plugins_url( '/penneo-plugin/js/bootstrap.min.js' ), [], '3.3.7' );
        wp_register_script( 'jquery.bxslider', plugins_url( '/penneo-plugin/js/jquery.bxslider.min.js' ), [ 'jquery' ], '4.2.12' );
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