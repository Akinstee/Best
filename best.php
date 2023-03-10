<?php
/**
 * Plugin Name:       Norrenberger Stocks Display
 * Description:       This plugin helps display all the investment stocks information the on pages via shortcode.
 * Requires at least: 6.1
 * Requires PHP:      7.0
 * Version:           0.1.3
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


global $norrenberger_db_version;
$norrenberger_db_version = '1.0';

//create new table upon activation of plugin
function db_setup() {
	global $wpdb;
	global $norrenberger_db_version;

	$table_name = $wpdb->prefix . 'investment_funds';
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id int(11) NOT NULL AUTO_INCREMENT,
		report_date varchar(255) NOT NULL,
		fund1_equities decimal(6,2)	DEFAULT 0.00,
        fund1_infrastructure_funds decimal(6,2)	DEFAULT 0.00,
        fund1_total_fgn_securities decimal(6,2)	DEFAULT 0.00,
        fund1_state_gov_bonds decimal(6,2)	DEFAULT 0.00,
        fund1_money_market decimal(6,2)	DEFAULT 0.00,
        fund1_uninvested_cash decimal(6,2)	DEFAULT 0.00,
        fund2_equities decimal(6,2)	DEFAULT 0.00,
        fund2_infrastructure_funds decimal(6,2)	DEFAULT 0.00,
        fund2_total_fgn_securities decimal(6,2)	DEFAULT 0.00,
        fund2_corporate_bonds decimal(6,2)	DEFAULT 0.00,
        fund2_infrastructure_bonds decimal(6,2)	DEFAULT 0.00,
        fund2_state_gov_bonds decimal(6,2)	DEFAULT 0.00,
        fund2_money_market decimal(6,2)	DEFAULT 0.00,
        fund2_uninvested_cash decimal(6,2) DEFAULT 0.00,
        fund3_equities decimal(6,2)	DEFAULT 0.00,
        fund3_corporate_bonds decimal(6,2) DEFAULT 0.00,
        fund3_infrastructure_bonds decimal(6,2) DEFAULT 0.00,
        fund3_total_fgn_securities decimal(6,2) DEFAULT 0.00,
        fund3_state_gov_bonds decimal(6,2) DEFAULT 0.00,
        fund3_money_market decimal(6,2)	DEFAULT 0.00,
        fund3_uninvested_cash decimal(6,2)	DEFAULT 0.00,
        fund4_equities decimal(6,2) DEFAULT 0.00,
        fund4_total_fgn_securities decimal(6,2) DEFAULT 0.00,
        fund4_corporate_bonds decimal(6,2) DEFAULT 0.00,
        fund4_state_gov_bonds decimal(6,2) DEFAULT 0.00,
        fund4_money_market decimal(6,2)	DEFAULT 0.00,
        fund4_uninvested_cash decimal(6,2) DEFAULT 0.00,
        fund5_total_fgn_securities decimal(6,2) DEFAULT 0.00,
        fund5_money_market decimal(6,2) DEFAULT 0.00,
        fund5_uninvested_cash decimal(6,2) DEFAULT 0.00,
        fund6_total_fgn_securities decimal(6,2) DEFAULT 0.00,
        fund6_uninvested_cash decimal(6,2)	DEFAULT 0.00,
		created_at timestamp DEFAULT CURRENT_TIMESTAMP,
		updated_at timestamp DEFAULT CURRENT_TIMESTAMP,
		PRIMARY KEY (id)
	) $charset_collate;";
	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta( $sql );
	add_option( 'norrenberger_db_version', $norrenberger_db_version );
}

function db_setup_data() {
	 global $wpdb;
	$table_name = $wpdb->prefix . 'investment_funds';
    // pull data from external API
    $url = 'http://ffpro.ieianchorpensions.com.ng/WebResourcesAPI/api/GetInvestmentFundValuations?lastUpdatedDate=2023-01-22&fundId=73';
    $id_token = 'nFBvloIUXW1tUS2IUQuudu62G1hA8oTt80b_hbOAakBZZMNAaaIeDZqtw726iUK-753krrbPbT8ABQlYefHbXMXgqDE9E2OnNZtH1FCl1_9kW6_azwLquTsV609YYtG481dLCLb6-yb6SBlS3Zobmab92iR8OWIKOZRND1Syezm1K_EG3k3dTg-gHUaGd4k5Ewh9zebqS1qO7MrVBqa-CzQnBQCkaQD8yON8-kRmr26x5EAbdXPBBBfgQsxoHUQ4-0hRRcTDUdlMFqcbKEVGKVkdxzGqGPulInqYYFA6uJvytRj23RaT4kZOV_elSD9OSyh_n4e3CB_hG-jR091vHn4reLXNPL5YtyQM2m7-XVu8zj1OFfOIltFSGcve0RTNtsVVwgzox3ApUZKMcNQ1vkdbsNHZJPJyRyCZkXeEnoYPpMHLwAE3CzzsalqwqRWQu9Mf6bOqz1gjsUt9bd4xRfXeIdPjVMJ2Pb2bW2rB2LeXpgtrJ_mtiZZqgwKMNc4hr8yS5wwkikzmMZSGuKzTaQ';
    $args = array(
        'headers' => array(
            'Authorization' => 'Bearer ' . $id_token,
        )
    );
    $response = wp_remote_get( $url, $args );
    $body     = wp_remote_retrieve_body( $response );
    $api_adata = json_decode($body, true);
	foreach($api_adata as $api_data ){
		$wpdb->insert( 
		$table_name, 
		array( 
            'report_date' => $api_data['ReportDate'], 
            'fund1_equities' => $api_data['Fund1Equities'],
            'fund1_infrastructure_funds' => $api_data['Fund1InfrastructureFunds'],
            'fund1_total_fgn_securities' => $api_data['Fund1TotalFGNSecurities'],
            'fund1_state_gov_bonds'      => $api_data['Fund1StateGovBonds'],
            'fund1_money_market'         => $api_data['Fund1MoneyMarket'],
            'fund1_uninvested_cash'      => $api_data['Fund1UnInvestedCash'],
            'fund2_equities'             => $api_data['Fund2Equities'],
            'fund2_total_fgn_securities' => $api_data['Fund2TotalFGNSecurities'],
            'fund2_corporate_bonds'      => $api_data['Fund2CorporateBonds'],
            'fund2_infrastructure_bonds' => $api_data['Fund2InfrastructureBonds'],
            'fund2_state_gov_bonds'      => $api_data['Fund2StateGovBonds'],
            'fund2_money_market'         => $api_data['Fund2MoneyMarket'],
            'fund2_uninvested_cash'      => $api_data['Fund2UnInvestedCash'],
            'fund3_equities'             => $api_data['Fund3Equities'],
            'fund3_corporate_bonds'      => $api_data['Fund3CorporateBonds'],
            'fund3_infrastructure_bonds' => $api_data['Fund3InfrastructureBonds'],
            'fund3_total_fgn_securities' => $api_data['Fund3TotalFGNSecurities'],
            'fund3_state_gov_bonds'      => $api_data['Fund3StateGovBonds'],
            'fund3_money_market'         => $api_data['Fund3MoneyMarket'],
            'fund3_uninvested_cash'      => $api_data['Fund3UnInvestedCash'],
            'fund4_equities'             => $api_data['Fund4Equities'],
            'fund4_total_fgn_securities' => $api_data['Fund4TotalFGNSecurities'],
            'fund4_corporate_bonds'      => $api_data['Fund4CorporateBonds'],
            'fund4_state_gov_bonds'      => $api_data['Fund4StateGovBonds'],
            'fund4_money_market'         => $api_data['Fund4MoneyMarket'],
            'fund4_uninvested_cash'      => $api_data['Fund4UnInvestedCash'],
            'fund5_total_fgn_securities' => $api_data['Fund5TotalFGNSecurities'],
            'fund5_money_market'         => $api_data['Fund5MoneyMarket'],
            'fund5_uninvested_cash'      => $api_data['Fund5UnInvestedCash'],
            'fund6_total_fgn_securities' => $api_data['Fund6TotalFGNSecurities'],
            'fund6_uninvested_cash'      => $api_data['Fund6UnInvestedCash']
		)
	);   
	}
}

register_activation_hook( __FILE__, 'db_setup' );
register_activation_hook( __FILE__, 'db_setup_data' );

register_deactivation_hook( __FILE__, 'remove_investment_funds_database' );

// remove investment_funds table once plugin is deactivated.
function remove_investment_funds_database() {
     global $wpdb;
     $table_name = $wpdb->prefix . 'investment_funds';
     $sql = "DROP TABLE IF EXISTS $table_name";
     $wpdb->query($sql);
     delete_option("norrenberger_db_version");
} 

 function getMaxDate()
    {
        global $wpdb;
        $currentMaxDate = date("Y-m-d", strtotime("2020-10-07"));

        $maxDate = $wpdb->get_col( 'report_date');

        if ($maxDate != null)
            $currentMaxDate = $maxDate;

            return Date("Y-m-d", strtotime($currentMaxDate));
    }

add_shortcode('Best', 'norrenberger_fund_1');

function norrenberger_fund_1 () {
    ?>
        <div class="" >
            <table id="fund_table" width="100%">
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
// function fetch_investment_valuations() {
//     $todays_date = date("Y-m-d");
//     $url = 'http://ffpro.ieianchorpensions.com.ng/WebResourcesAPI/api/GetInvestmentFundValuations?lastUpdatedDate=2022-10-21&fundId=73';
//     $id_token = '0yMfJUVun5dcWFp0Q4hAecWzNsveBlFAGSE8kds5ylJE_mPoc50oU2lQqNXN1c2jxc1Wyv02Gd4BxIJgLow_QhSaT8W1_7POsxpvDCsV59_evualPwPH0bHd5KgTzUuVpMqP7PuSymGfeoULmYu4rTMjs94LhnipEoUIECCmaFZLg9YTMwmhPuPNYJ5RTnBhEClMOg17LKn2q07Tqi970cGM-q-IUOodqGcVykd7wlh4jCqjnh83FLm_U-YJ4ySlqffL22rewahPBH8aHJw2Upkrq9OVEPtZegLar-b-jXZG9ctfDDsKahMip1hCpJVPthxVE3gl74v-IjXGn4x7cYdp7YFYaw_C-PoqO3yqmtKpRJHunU-h1RSGVqPlzBVpDiI153qGhXHGdv1jU8bUS523qMd3xXuqwkTjFN-oxHOtcPWZMGcImDn5fj6eIzb3vozV8JYZlfEK0okofxfCxoBdaAUXOspiPFuWxAi8cAEe7wUAqiTNidmVOa_Fw3_4';
//     $args = array(
//         'headers' => array(
//             'Authorization' => 'Bearer ' . $id_token,
//         )
//     );
//     $response = wp_remote_get( $url, $args );
//     $body     = wp_remote_retrieve_body( $response );
//     // $encoded_data = json_decode($body);
//     $encoded_data = $body;

// return $encoded_data;
// }

// REST Endpoint information.
function best_rest_ajax_callback() {
    $todays_date = date("Y-m-d");
    $url = 'http://ffpro.norrenberger.com/WebResourcesAPI/api/GetFundPrices?lastUpdatedDate=2022-10-21&fundId=73';
    $id_token = 't1QACVn2imq1e0OJWfHA6hiL9sU5-Ow0jK91Lh4VuQr6O5ML-F5OEd7xyPlLTtb5jdTok27pTI9L6A9h5GUAt2CLf4hkFLUBEzkcTmg88lWPzfhQ4zsdPbVyNP16zOiZMHrsfEKtjj5ji0Rg-Lq9wJSs8TsMIUmKUPvdnMUHlo_kRA74l3kz0Q2z5MiDo1XVaJliSY55IYxj5GSifDGCmnJ1ILRbIH6N8ac5jIN-zgoQ-vsZJGmoVoXV4OTFy_ALkcxUwpVMNXOJJxGQ4irFSZh4MWXz14wlRRYggyOWdh9Jc26zfMwN30n6IjwXTg9vSqmDagM0yp5e5sVqa-w12vo4zwvD6f6GF7iQR9q704gbZHA64lrBjWGgYddJSmz287q6fFdCAGGc3M2dLrn0tRBggvsOz7hSlg8vaT3B-vCjGomnX-r8mvhHQ1p6qMJBOBJmildLyq5XYBsJZBoyaOj4yNgek0VK_w-4wHWGiD81KFI_7NW2hjHrcUyR_UXNo5zSWuocdrIVxPOrBB0B3g';
    $args = array(
        'headers' => array(
            'Authorization' => 'Bearer ' . $id_token,
        )
    );
    $response = wp_remote_get( $url, $args );
    $body     = wp_remote_retrieve_body( $response );
    $encoded_data = json_decode($body);

return $encoded_data;

}




add_shortcode('fund_valuations', 'get_funds_valuations');

function get_funds_valuations () {
    ?>
				<?php 
				   global $wpdb;
					$table_name = $wpdb->prefix . 'investment_funds';
				   $results = $wpdb->get_results ( "SELECT * FROM $table_name ORDER BY report_date DESC" );
					//var_dump($results);	   
				?>
	<div>
		<h4>Daily Units</h4>
		<table id="table-fund1" width="100%" style="font-size: 12px">
			<tbody>
			  <tr style="background: #000; color: #fff">
					<th>Asset Classes</th>
					<?php foreach ($results as $row) : ?>
						<td><?= $row->report_date ?></td>
					<?php endforeach; ?>
				</tr>
				<tr>
					<th>Equities</th>
					<?php foreach ($results as $row) : ?>
						<td><?= $row->fund1_equities ?></td>
					<?php endforeach; ?>
				</tr>
				<tr style="background: #ededed;">
					<th>Infrastructure Funds</th>
					<?php foreach ($results as $row) : ?>
						<td><?= $row->fund1_infrastructure_funds ?></td>
					<?php endforeach; ?>
				</tr>	
				<tr>
					<th>Total FGN Securities</th>
					<?php foreach ($results as $row) : ?>
					   <td><?= $row->fund1_total_fgn_securities ?></td>
					<?php endforeach; ?>
				</tr>	
				<tr style="background: #ededed;">
					<th>State Gov Bonds</th>
					<?php foreach ($results as $row) : ?>
					   <td><?= $row->fund1_state_gov_bonds ?></td>
					<?php endforeach; ?>
				</tr>	
				<tr>
					<th>Money Market</th>
					<?php foreach ($results as $row) : ?>
						<td><?= $row->fund1_money_market ?></td>
					<?php endforeach; ?>
				</tr>	
				<tr style="background: #ededed;">
					<th>Uninvested Cash</th>
					<?php foreach ($results as $row) : ?>
						<td><?= $row->fund1_uninvested_cash ?></td>
					<?php endforeach; ?>
				</tr>
					<tr>
					<th>Total</th>
					<?php foreach ($results as $row) : ?>
						<td>100%</td>
					<?php endforeach; ?>
				</tr>
			</tbody>
		</table>
	</div>
	<div>
		<h4>Daily Units</h4>
		<table id="table-fund2" width="100%" style="font-size: 12px">
			<tbody>
			  <tr style="background: #000; color: #fff">
					<th>Asset Classes</th>
					<?php foreach ($results as $row) : ?>
						<td><?= $row->report_date ?></td>
					<?php endforeach; ?>
				</tr>
				<tr>
					<th>Equities</th>
					<?php foreach ($results as $row) : ?>
						<td><?= $row->fund2_equities ?></td>
					<?php endforeach; ?>
				</tr>
				<tr style="background: #ededed;">
					<th>Corporate Bonds</th>
					<?php foreach ($results as $row) : ?>
						<td><?= $row->fund2_corporate_bonds ?></td>
					<?php endforeach; ?>
				</tr>	
				<tr>
					<th>Total FGN Securities</th>
					<?php foreach ($results as $row) : ?>
					   <td><?= $row->fund2_total_fgn_securities ?></td>
					<?php endforeach; ?>
				</tr>	
				<tr style="background: #ededed;">
					<th>State Gov Bonds</th>
					<?php foreach ($results as $row) : ?>
					   <td><?= $row->fund2_state_gov_bonds ?></td>
					<?php endforeach; ?>
				</tr>	
				<tr>
					<th>Money Market</th>
					<?php foreach ($results as $row) : ?>
						<td><?= $row->fund2_money_market ?></td>
					<?php endforeach; ?>
				</tr>	
				<tr style="background: #ededed;">
					<th>Uninvested Cash</th>
					<?php foreach ($results as $row) : ?>
						<td><?= $row->fund2_uninvested_cash ?></td>
					<?php endforeach; ?>
				</tr>
					<tr>
					<th>Total</th>
					<?php foreach ($results as $row) : ?>
						<td>100%</td>
					<?php endforeach; ?>
				</tr>
			</tbody>
		</table>
	</div>
<div>
		<h4>Daily Units</h4>
		<table id="table-fund3" width="100%" style="font-size: 12px">
			<tbody>
			  <tr style="background: #000; color: #fff">
					<th>Asset Classes</th>
					<?php foreach ($results as $row) : ?>
						<td><?= $row->report_date ?></td>
					<?php endforeach; ?>
				</tr>
				<tr>
					<th>Equities</th>
					<?php foreach ($results as $row) : ?>
						<td><?= $row->fund3_equities ?></td>
					<?php endforeach; ?>
				</tr>
				<tr style="background: #ededed;">
					<th>Corporate Bonds</th>
					<?php foreach ($results as $row) : ?>
						<td><?= $row->fund3_corporate_bonds ?></td>
					<?php endforeach; ?>
				</tr>	
				<tr>
					<th>Total FGN Securities</th>
					<?php foreach ($results as $row) : ?>
					   <td><?= $row->fund3_total_fgn_securities ?></td>
					<?php endforeach; ?>
				</tr>	
				<tr style="background: #ededed;">
					<th>State Gov Bonds</th>
					<?php foreach ($results as $row) : ?>
					   <td><?= $row->fund3_state_gov_bonds ?></td>
					<?php endforeach; ?>
				</tr>	
				<tr>
					<th>Money Market</th>
					<?php foreach ($results as $row) : ?>
						<td><?= $row->fund3_money_market ?></td>
					<?php endforeach; ?>
				</tr>	
				<tr style="background: #ededed;">
					<th>Uninvested Cash</th>
					<?php foreach ($results as $row) : ?>
						<td><?= $row->fund3_uninvested_cash ?></td>
					<?php endforeach; ?>
				</tr>
					<tr>
					<th>Total</th>
					<?php foreach ($results as $row) : ?>
						<td>100%</td>
					<?php endforeach; ?>
				</tr>
			</tbody>
		</table>
	</div>

	<div>
		<h4>Fund 4</h4>
		<table id="table-fund3" width="100%" style="font-size: 12px">
			<tbody>
			  <tr style="background: #000; color: #fff">
					<th>Asset Classes</th>
					<?php foreach ($results as $row) : ?>
						<td><?= date('Y-m-d', strtotime(substr($row->report_date, 0,10))) ?></td>
					<?php endforeach; ?>
				</tr>
				<tr>
					<th>Equities</th>
					<?php foreach ($results as $row) : ?>
						<td><?= $row->fund4_equities ?></td>
					<?php endforeach; ?>
				</tr>
				<tr style="background: #ededed;">
					<th>Corporate Bonds</th>
					<?php foreach ($results as $row) : ?>
						<td><?= $row->fund4_corporate_bonds ?></td>
					<?php endforeach; ?>
				</tr>	
				<tr>
					<th>Total FGN Securities</th>
					<?php foreach ($results as $row) : ?>
					   <td><?= $row->fund4_total_fgn_securities ?></td>
					<?php endforeach; ?>
				</tr>	
				<tr style="background: #ededed;">
					<th>State Gov Bonds</th>
					<?php foreach ($results as $row) : ?>
					   <td><?= $row->fund4_state_gov_bonds ?></td>
					<?php endforeach; ?>
				</tr>	
				<tr>
					<th>Money Market</th>
					<?php foreach ($results as $row) : ?>
						<td><?= $row->fund4_money_market ?></td>
					<?php endforeach; ?>
				</tr>	
				<tr style="background: #ededed;">
					<th>Uninvested Cash</th>
					<?php foreach ($results as $row) : ?>
						<td><?= $row->fund4_uninvested_cash ?></td>
					<?php endforeach; ?>
				</tr>
					<tr>
					<th>Total</th>
					<?php foreach ($results as $row) : ?>
						<td>100%</td>
					<?php endforeach; ?>
				</tr>
			</tbody>
		</table>
	</div>
    <?php  
}


// Create new endpoint to provide data.
// add_action( 'rest_api_init', 'best2_rest_ajax_endpoint' );

// function best2_rest_ajax_endpoint() {
//     register_rest_route(
//         'best',
//         'rest-ajax',
//         [
//             'methods'             => 'GET',
//             'permission_callback' => '__return_true',
//             'callback'            => 'best2_rest_ajax_callback',
//         ]
//     );
// }


// REST Endpoint information.
function best2_rest_ajax_callback() {
    $todays_date = date("Y-m-d");
    $url = 'http://ffpro.ieianchorpensions.com.ng/WebResourcesAPI/api/GetInvestmentFundValuations?lastUpdatedDate=2022-10-21&fundId=73';
    $id_token = 't1QACVn2imq1e0OJWfHA6hiL9sU5-Ow0jK91Lh4VuQr6O5ML-F5OEd7xyPlLTtb5jdTok27pTI9L6A9h5GUAt2CLf4hkFLUBEzkcTmg88lWPzfhQ4zsdPbVyNP16zOiZMHrsfEKtjj5ji0Rg-Lq9wJSs8TsMIUmKUPvdnMUHlo_kRA74l3kz0Q2z5MiDo1XVaJliSY55IYxj5GSifDGCmnJ1ILRbIH6N8ac5jIN-zgoQ-vsZJGmoVoXV4OTFy_ALkcxUwpVMNXOJJxGQ4irFSZh4MWXz14wlRRYggyOWdh9Jc26zfMwN30n6IjwXTg9vSqmDagM0yp5e5sVqa-w12vo4zwvD6f6GF7iQR9q704gbZHA64lrBjWGgYddJSmz287q6fFdCAGGc3M2dLrn0tRBggvsOz7hSlg8vaT3B-vCjGomnX-r8mvhHQ1p6qMJBOBJmildLyq5XYBsJZBoyaOj4yNgek0VK_w-4wHWGiD81KFI_7NW2hjHrcUyR_UXNo5zSWuocdrIVxPOrBB0B3g';
    $args = array(
        'headers' => array(
            'Authorization' => 'Bearer ' . $id_token,
        )
    );
    $response = wp_remote_get( $url, $args );
    $body     = wp_remote_retrieve_body( $response );
    $encoded_data = json_decode($body);

return $encoded_data;

}





/**
 * Function that initializes the plugin.
 */
function best_get_stocks_data() { 
    $url = 'http://ffpro.norrenberger.com/WebResourcesAPI/api/GetFundPrices?lastUpdatedDate=2022-10-21&fundId=73';
    $id_token = 't1QACVn2imq1e0OJWfHA6hiL9sU5-Ow0jK91Lh4VuQr6O5ML-F5OEd7xyPlLTtb5jdTok27pTI9L6A9h5GUAt2CLf4hkFLUBEzkcTmg88lWPzfhQ4zsdPbVyNP16zOiZMHrsfEKtjj5ji0Rg-Lq9wJSs8TsMIUmKUPvdnMUHlo_kRA74l3kz0Q2z5MiDo1XVaJliSY55IYxj5GSifDGCmnJ1ILRbIH6N8ac5jIN-zgoQ-vsZJGmoVoXV4OTFy_ALkcxUwpVMNXOJJxGQ4irFSZh4MWXz14wlRRYggyOWdh9Jc26zfMwN30n6IjwXTg9vSqmDagM0yp5e5sVqa-w12vo4zwvD6f6GF7iQR9q704gbZHA64lrBjWGgYddJSmz287q6fFdCAGGc3M2dLrn0tRBggvsOz7hSlg8vaT3B-vCjGomnX-r8mvhHQ1p6qMJBOBJmildLyq5XYBsJZBoyaOj4yNgek0VK_w-4wHWGiD81KFI_7NW2hjHrcUyR_UXNo5zSWuocdrIVxPOrBB0B3g';
    $args = array(
        'headers' => array(
            'Authorization' => 'Bearer ' . $id_token,
        )
    );
    $response = wp_remote_get( $url, $args );
    $body     = wp_remote_retrieve_body( $response );
    $t_date = date("Y-m-d");
	?>
        <h1 style="padding: 10px">Structure of Fund 3 Investment Portfolio For <?php echo "" .  $t_date  ?><h1>
        <table id="table" width="100%">
          <thead>
            <tr role="row">
              <th>Id</th>
              <th>Date</th>
              <th>Fund Name</th>
              <th>Rate</th>
            </tr>
          </thead>
          <tbody>
        
        </tbody>
        </table>
        <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css" />
        <script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
        <style>
            table.dataTable thead th {text-align: start; padding: 10px}
            h1 { font-size: 1.2em;}
        </style>
        <script type="text/javascript">
            var investment_data = <? echo $body ?>;
            var abc = new Request('<? $body ?>');
            
        jQuery(document).ready(function($){
            console.log(investment_data);
                $('#table').DataTable({
                data: investment_data,
                    columns: [
                    { data: 'Id' },
                    { data: 'ReportDate' },
                    { data: 'SchemeId' },
                    { data: 'Value' },
                    
                    ]
            });
        });
        </script>
    <?php
}


function get_fund_valuations() { 
    $url = 'http://ffpro.ieianchorpensions.com.ng/WebResourcesAPI/api/GetInvestmentFundValuations?lastUpdatedDate=2022-10-22&fundId=73';
    $id_token = 't1QACVn2imq1e0OJWfHA6hiL9sU5-Ow0jK91Lh4VuQr6O5ML-F5OEd7xyPlLTtb5jdTok27pTI9L6A9h5GUAt2CLf4hkFLUBEzkcTmg88lWPzfhQ4zsdPbVyNP16zOiZMHrsfEKtjj5ji0Rg-Lq9wJSs8TsMIUmKUPvdnMUHlo_kRA74l3kz0Q2z5MiDo1XVaJliSY55IYxj5GSifDGCmnJ1ILRbIH6N8ac5jIN-zgoQ-vsZJGmoVoXV4OTFy_ALkcxUwpVMNXOJJxGQ4irFSZh4MWXz14wlRRYggyOWdh9Jc26zfMwN30n6IjwXTg9vSqmDagM0yp5e5sVqa-w12vo4zwvD6f6GF7iQR9q704gbZHA64lrBjWGgYddJSmz287q6fFdCAGGc3M2dLrn0tRBggvsOz7hSlg8vaT3B-vCjGomnX-r8mvhHQ1p6qMJBOBJmildLyq5XYBsJZBoyaOj4yNgek0VK_w-4wHWGiD81KFI_7NW2hjHrcUyR_UXNo5zSWuocdrIVxPOrBB0B3g';
    $args = array(
        'headers' => array(
            'Authorization' => 'Bearer ' . $id_token,
        )
    );
    $response = wp_remote_get( $url, $args );
    $body     = wp_remote_retrieve_body( $response );
	// var_dump(json_decode($body, true));
    $t_date = date("Y-m-d");
	?>
        <h1 style="padding: 10px">All Investment Funds Valuation <?php echo "(" .  $t_date . ")"  ?><h1>
        <table id="table-valuation" width="100%">
          <thead>
            <tr role="row">
                <th>Report Date</th>
                <th>Equities ( Fund 1 )</th>
                <th>InfrastructureFunds ( Fund 1 )</th>
                <th>MoneyMarket ( Fund 1 )</th>
                <th>StateGovBonds ( Fund 1 )</th>
                <th>TotalFGNSecurities ( Fund 1 )</th>
                <th>UnInvestedCash ( Fund 1 )</th>
                <th>CorporateBonds ( Fund 2 )</th>
                <th>Equities ( Fund 2 )</th>
                <th>InfrastructureBonds ( Fund 2 )</th>
                <th>InfrastructureFunds ( Fund 2 )</th>
                <th>MoneyMarket ( Fund 2 )</th>
                <th>StateGovBonds ( Fund 2 )</th>
                <th>TotalFGNSecurities ( Fund 2 )</th>
                <th>UnInvestedCash ( Fund 2 )</th>
                <th>CorporateBonds ( Fund 3 )</th>
                <th>Equities ( Fund 3 )</th>
                <th>InfrastructureBonds ( Fund 3 )</th>
                <th>MoneyMarket ( Fund 3 )</th>
                <th>StateGovBonds ( Fund 3 )</th>
                <th>TotalFGNSecurities ( Fund 3 )</th>
                <th>UnInvestedCash ( Fund 3 )</th>
                <th>CorporateBonds ( Fund 4 )</th>
                <th>Equities ( Fund 4 )</th>
                <th>MoneyMarket ( Fund 4 )</th>
                <th>StateGovBonds ( Fund 4 )</th>
                <th>TotalFGNSecurities ( Fund 4 )</th>
                <th>UnInvestedCash ( Fund 4 )</th>
                <th>MoneyMarket ( Fund 5 )</th>
                <th>TotalFGNSecurities ( Fund 5 )</th>
                <th>UnInvestedCash ( Fund 5 )</th>
                <th>TotalFGNSecurities ( Fund 6 )</th>
                <th>UnInvestedCash ( Fun 6 )</th>
            </tr>
          </thead>
          <tbody>
        
        </tbody>
        </table>
        <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css" />
        <style>
            table { border-collapse: collapse; }
            tr ,thead{ display: block; float: left; }
            th, td { display: block; border: 1px solid black; }
            tbody{display: block;}
            table.dataTable tbody td {padding: 10px;}
            h1 { font-size: 1.0em;}
        </style>
        <script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript">
            var investment_data = <? echo $body ?>;
            var abc = new Request('<? $body ?>');
            
        jQuery(document).ready(function($){
            console.log(investment_data);
                $('#table-valuation').DataTable({
                data: investment_data,
                    columns: [
                        // {data: 'Id'},
                        {data: "ReportDate"},
                        { data: 'Fund1Equities' },
                        { data: 'Fund1InfrastructureFunds' },
                        { data: 'Fund1MoneyMarket' },
                        { data: 'Fund1StateGovBonds' },
                        { data: 'Fund1TotalFGNSecurities' },
                        { data: 'Fund1UnInvestedCash'},
                        { data: 'Fund2CorporateBonds'},
                        { data: 'Fund2Equities' },
                        { data: 'Fund2InfrastructureBonds' },
                        { data: 'Fund2InfrastructureFunds' },
                        { data: 'Fund2MoneyMarket' },
                        { data: 'Fund2StateGovBonds' },
                        { data: 'Fund2TotalFGNSecurities' },
                        { data: 'Fund2UnInvestedCash'},
                        { data: 'Fund3CorporateBonds' },
                        { data: 'Fund3Equities' },
                        { data: 'Fund3InfrastructureBonds' },
                        { data: 'Fund3MoneyMarket' },
                        { data: 'Fund3StateGovBonds' },
                        { data: 'Fund3TotalFGNSecurities' },
                        { data: 'Fund3UnInvestedCash'},
                        { data: 'Fund4CorporateBonds' },
                        { data: 'Fund4Equities' },
                        { data: 'Fund4MoneyMarket' },
                        { data: 'Fund4StateGovBonds' },
                        { data: 'Fund4TotalFGNSecurities' },
                        { data: 'Fund4UnInvestedCash'},
                        { data: 'Fund5MoneyMarket' },
                        { data: 'Fund5TotalFGNSecurities' },
                        { data: 'Fund5UnInvestedCash'},
                        { data: 'Fund6TotalFGNSecurities' },
                        { data: 'Fund6UnInvestedCash'}
                    
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
	
    //adding a submenu called API Settings
	add_submenu_page( 
        'investment-options', 
        'fund valuations', 
        'Fund Valuations', 
        'manage_options', 
        'fund_valuations', 
        'get_fund_valuations'
    ); 

}


add_action( 'admin_menu', 'best_register_custom_menu_page' );





/**
* Load custom CSS and JavaScript.
*/
add_action( 'wp_enqueue_scripts', 'wpdocs_my_enqueue_scripts' );
function wpdocs_my_enqueue_scripts() : void {
    // Enqueue my styles.
    wp_enqueue_style( 'datatables-style', 'https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css' );
     
    // Enqueue my scripts.
    wp_enqueue_script( 'datatables', 'https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js', ['jquery'], null, true );
     wp_localize_script( 'datatables', 'datatablesajax', array('url' => admin_url('admin-ajax.php')) );

}