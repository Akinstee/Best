<?php
/**
 * Plugin Name:       Norrenberger Stocks Display
 * Description:       This plugin helps display all the investment stocks information the on pages via shortcode.
 * Requires at least: 6.1
 * Requires PHP:      7.0
 * Version:           0.1.0
 * Author:            @Akinstee @kingdanie
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       best
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


add_shortcode('Best', 'norrenberger_fund_1');

function norrenberger_fund_1 () {
    ?>
        <div class="display" >
		<table id="fund-1-table" width="100%">
  <thead>
    <tr role="row">
        <th>Id</th>
        <th>SchemeId</th>
        <th>ReportDate</th>
        <th>Value</th>
    </tr>

  </thead>
 
</table>
</div>
    <?php
    // Write AJAX to show the infomation in the shortcode.
    wp_enqueue_script( 'best-ajax-scripts', plugins_url( 'assets/js/best-ajax.js', __FILE__ ), ['jquery'], '0.1.0', true );
    
}


// Create new endpoint to provide data.
add_action( 'rest_api_init', 'best_rest_ajax_endpoint' );

function best_rest_ajax_endpoint() {
    register_rest_route(
        'best',
        'rest-ajax',
        [
            'methods'             => 'GET',
            'permission_callback' => '__return_true',
            'callback'            => 'best_rest_ajax_callback',
        ]
    );
}


// REST Endpoint information.
function best_rest_ajax_callback() {

    $url = 'http://ffpro.norrenberger.com/WebResourcesAPI/api/GetFundPrices?lastUpdatedDate=2022-10-21&fundId=73';
    $id_token = '0yMfJUVun5dcWFp0Q4hAecWzNsveBlFAGSE8kds5ylJE_mPoc50oU2lQqNXN1c2jxc1Wyv02Gd4BxIJgLow_QhSaT8W1_7POsxpvDCsV59_evualPwPH0bHd5KgTzUuVpMqP7PuSymGfeoULmYu4rTMjs94LhnipEoUIECCmaFZLg9YTMwmhPuPNYJ5RTnBhEClMOg17LKn2q07Tqi970cGM-q-IUOodqGcVykd7wlh4jCqjnh83FLm_U-YJ4ySlqffL22rewahPBH8aHJw2Upkrq9OVEPtZegLar-b-jXZG9ctfDDsKahMip1hCpJVPthxVE3gl74v-IjXGn4x7cYdp7YFYaw_C-PoqO3yqmtKpRJHunU-h1RSGVqPlzBVpDiI153qGhXHGdv1jU8bUS523qMd3xXuqwkTjFN-oxHOtcPWZMGcImDn5fj6eIzb3vozV8JYZlfEK0okofxfCxoBdaAUXOspiPFuWxAi8cAEe7wUAqiTNidmVOa_Fw3_4';
    $args = array(
        'headers' => array(
            'Authorization' => 'Bearer ' . $id_token,
        )
    );
    $response = wp_remote_get( $url, $args );
    $body     = wp_remote_retrieve_body( $response );
    $Wot = $body;

return $body;

}



/**
 * Function that initializes the plugin.
 */
function best_get_stocks_data() { 
    $url = 'http://ffpro.norrenberger.com/WebResourcesAPI/api/GetFundPrices?lastUpdatedDate=2022-10-21&fundId=73';
    $id_token = '0yMfJUVun5dcWFp0Q4hAecWzNsveBlFAGSE8kds5ylJE_mPoc50oU2lQqNXN1c2jxc1Wyv02Gd4BxIJgLow_QhSaT8W1_7POsxpvDCsV59_evualPwPH0bHd5KgTzUuVpMqP7PuSymGfeoULmYu4rTMjs94LhnipEoUIECCmaFZLg9YTMwmhPuPNYJ5RTnBhEClMOg17LKn2q07Tqi970cGM-q-IUOodqGcVykd7wlh4jCqjnh83FLm_U-YJ4ySlqffL22rewahPBH8aHJw2Upkrq9OVEPtZegLar-b-jXZG9ctfDDsKahMip1hCpJVPthxVE3gl74v-IjXGn4x7cYdp7YFYaw_C-PoqO3yqmtKpRJHunU-h1RSGVqPlzBVpDiI153qGhXHGdv1jU8bUS523qMd3xXuqwkTjFN-oxHOtcPWZMGcImDn5fj6eIzb3vozV8JYZlfEK0okofxfCxoBdaAUXOspiPFuWxAi8cAEe7wUAqiTNidmVOa_Fw3_4';
    $args = array(
        'headers' => array(
            'Authorization' => 'Bearer ' . $id_token,
        )
    );
    $response = wp_remote_get( $url, $args );
    $body     = wp_remote_retrieve_body( $response )
	?>
        <h1 style="padding: 10px">Structure of Fund 3 Investment Portfolio<h1>
        <table id="table" width="100%">
          <thead>
            <tr role="row">
              <th>ID</th>
              <th>SchemeId</th>
              <th>ReportDate</th>
              <th>Value</th>
            </tr>
          </thead>
          <tbody>
        
        </tbody>
        </table>
        <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css" />
        <script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript">
            var investment_data = <? echo $body ?>;
            var abc = new Request('<? $body ?>');
            
        jQuery(document).ready(function($){
            console.log(investment_data);
                $('#table').DataTable({
                data: investment_data,
                    columns: [
                    { data: 'Id' },
                    { data: 'SchemeId' },
                    { data: 'ReportDate' },
                    { data: 'Value' },
                    
                    ]
            });
        });
        </script>
    <?php
}


/**
 * Register a custom menu page to view the information queried.
 */
function best_register_custom_menu_page() {
	add_menu_page(
		__( 'Best API Settings', 'best' ),
		'Investment Funds',
		'manage_options',
		'investment-options',
        'best_get_stocks_data',
        'dashicons-chart-bar',
		16
	);
	
    // //adding a submenu called API Settings
	// add_submenu_page( 
    //     'best', 
    //     'api keys', 
    //     'API Settings', 
    //     'manage_options', 
    //     'api_creds', 
    //     'best_api_creds'
    // ); 

}


add_action( 'admin_menu', 'best_register_custom_menu_page' );


/**
* Load custom CSS and JavaScript.
*/
add_action( 'wp_enqueue_scripts', 'wpdocs_my_enqueue_scripts' );
function wpdocs_my_enqueue_scripts() : void {
    // Enqueue my styles.
    wp_enqueue_style( 'datatables-style', 'https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css' );
     
    // Enqueue my scripts.
    wp_enqueue_script( 'datatables', 'https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js', ['jquery'], null, true );
     wp_localize_script( 'datatables', 'datatablesajax', array('url' => admin_url('admin-ajax.php')) );

}