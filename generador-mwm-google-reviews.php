<?php
/**
 * Plugin Name: mowomo Google Reviews
 * Description: mowomo Google Reviews is a plugin that allows you to display your Google business ratings on your website using
 * Version: 1.1
 * Author: mowomo
 * Url: mowomo.com
 * @package mwm-google-reviews
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Functionality that adheres all the necessary assets so that the plugin works correctly.
 * 
 * @package mwm-google-reviews
 * @version 1.0.0
 */
function mwm_google_reviews_file_gluing() {
    
    wp_register_script("mwm_google_reviews_scripts", plugins_url("/build/assets/scripts.js", __FILE__), array(), "1.0.0", true);
    wp_register_style("mwm_google_reviews_styles", plugins_url("/build/assets/styles.css", __FILE__), array(), "1.0.0");
    wp_register_style("font_awesome", "https://use.fontawesome.com/releases/v5.8.2/css/all.css", array(), "1.0.0" );

    wp_enqueue_script("mwm_google_reviews_scripts");
    wp_enqueue_style("mwm_google_reviews_styles");
    wp_enqueue_style("font_awesome");
}

add_action("init", "mwm_google_reviews_file_gluing");

class mwm_google_reviews {
    private static $instance;
    private $slug;
    private $blockNames;
    private $wordpressPackages;
    private $version;

    public static function mwm_google_reviews__init() {
        if (!self::$instance) {
            self::$instance = new mwm_google_reviews();
        } else {
            echo 'There is already a created instance of this class.';
        }
    }

    private function __construct() {
        // CONFIGURAR ESTAS VARIABLES -----------------------------------------------
        $this->slug              = 'mwm-google-reviews';
        $this->blockNames        = array('bloque-google-reviews');//Array
        $this->wordpressPackages = array('wp-blocks', 'wp-element', 'wp-editor', 'wp-i18n', 'wp-components');
        $this->version           = '1.1';
        //---------------------------------------------------------------------------

        add_filter( 'block_categories', function( $categories, $post ) {
            return array_merge(
                $categories,
                array(
                    array(
                        'slug' => 'mwm-google-reviews',
                        'title' => __( 'mowomo Google Reviews', 'mwm-google-reviews' ),
                    ),
                )
            );
        }, 10, 2 );
        add_action( 'init', array( $this, 'mwm_google_reviews_register_dynamic_editor_assets' ) );
    }

    public function mwm_google_reviews_register_dynamic_editor_assets() {
        $slug              = $this->slug;
        $blockNames        = $this->blockNames;
        $wordpressPackages = $this->wordpressPackages;
        $version           = $this->version;

        wp_register_script(
            $slug .'/editor-script',
            plugins_url('./build/block.build.js', __FILE__),
            $wordpressPackages,
            $version
        );
        wp_register_style(
            $slug .'/editor-style',
            plugins_url('./build/block.editor.build.css', __FILE__),
            array(),
            filemtime( plugin_dir_path( __FILE__ ) . './build/block.editor.build.css' )
        );
        wp_register_style(
            $slug .'/style',
            plugins_url('./build/block.style.build.css', __FILE__),
            array(),
            filemtime( plugin_dir_path( __FILE__ ) . './build/block.style.build.css' )
        );

        for ($i=0; $i < count($blockNames); $i++) {
            register_block_type(
                $slug .'/'. $blockNames[$i],
                array(
                    'editor_script' => $slug .'/editor-script',
                    'editor_style'  => $slug .'/editor-style',
                    'style'         => $slug .'/style'
                ) );
        }
    }
}

mwm_google_reviews::mwm_google_reviews__init();
