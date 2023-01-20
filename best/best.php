<?php
/**
 * Plugin Name:       Norrenberger
 * Description:       Example block scaffolded with Create Block tool.
 * Requires at least: 6.1
 * Requires PHP:      7.0
 * Version:           0.1.0
 * Author:            The WordPress Contributors
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       norrenberger
 *
 * @package           create-block
 */

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */


 // If this file is access directly, abort!!!
defined( 'ABSPATH') or die( 'Unauthorized Access');


function create_block_best_block_init() {
	register_block_type( __DIR__ . '/build' );
}
add_action( 'init', 'create_block_best_block_init' );




/**
 * Function that initializes the plugin.
 */
function norrenberger_get_stocks_data() { 
	
}


/**
 * Register a custom menu page to view the information queried.
 */
function norrenberger_register_custom_menu_page() {
	add_menu_page(
		__( 'Norrenberger API Settings', 'norrenberger' ),
		'Norrenberger Stocks',
		'manage_options',
		'norrenberger',
        'norrenberger_get_stocks_data',
        'dashicons-testimonial',
		16
	);
	
    //adding a submenu called API Settings
	add_submenu_page( 
        'norrenberger', 
        'api keys', 
        'API Settings', 
        'manage_options', 
        'api_creds', 
        'norrenberger_api_creds'
    ); 

}