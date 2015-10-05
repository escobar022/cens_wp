<?php
/**
 * Plugin Name: Census API plugin
 * Plugin URI: andres.codes
 * Description: This plugin adds custom views for api calls.
 * Version: 1.0.0
 * Author: Andres Escobar
 * Author URI:
 */


/**
 * Include required core file
 *
 */

include 'src/censusapp.php'; // Class File

/**
 * Main call_censusApp Instance
 *
 * @return censusApp - Main instance
 */

function call_censusApp() {
    return new censusApp();
}

/**
 * Check if user is admin (general settings for admin only)
 */

if ( is_admin() ){
    add_action( 'init', 'call_censusApp' );
}

// Helper function
if ( !function_exists( 'pp' ) ) {
    function pp() {
        return plugin_dir_url( __FILE__ );
    }
}