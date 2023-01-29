<?php
/**
*Plugin Name: Sritoni Payments
*Plugin URI:
*Description: SriToni e-commerce plugin  ussing remote WooCommerce
*Version: 2023012700
*Author: Madhu Avasarala
*Author URI: http://sritoni.org
*Text Domain: sritoni_payments
*Domain Path:
*/
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if ( is_admin() )
{ 
	
  	add_action('admin_menu', 'add_menu_page');

    add_action('admin_menu', 'add_submenu_page');

}

$hook_suffix_menu_page_madhu_custom_code =
add_menu_page(  
            'madhu custom code',                    // $page_title, 
            'madhu custom code',                    // $menu_title,
            'manage_options',                       // $capability,
            'madhu-custom-code-main',                    // $menu_slug
            'madhu_custom_code1_submenu_page_render');      // callable function

// by keeping the parent slug and the menu slug the same, we avoid the duplicate submenu
// clicking on the main menu or this submenu gives same results
$hook_suffix_submenu_page_madhu_custom_code1 = 
add_submenu_page( 
            'madhu-custom-code',	                            // string $parent_slug
            'madhu custom code 1',	                        // string $page_title
            'custom code 1',                                            // string $menu_title	
            'manage_options',                                   // string $capability	
            'madhu-custom-code-1',                          // string $menu_slug		
            'madhu_custom_code1_submenu_page_render' );   // callable $function = ''

function madhu_custom_code1_submenu_page_render()
{
    // this is for rendering the API test onto the sritoni_tools page
    ?>
        <h1> Click on button to test corresponding Server connection and API</h1>
        <form action="" method="post" id="form1">
            <input type="submit" name="button" 	value="ticket_details"/>
            <input type="submit" name="button" 	value="ticket_statuses"/>
            <input type="submit" name="button" 	value="create_new_ticket"/>
        </form>

        
    <?php

    $button = sanitize_text_field( $_POST['button'] );

    switch ($button) 
    {
        case 'ticket_details':
            ticket_details();
            break;

        case 'ticket_statuses':
            ticket_statuses();
            break;

        case 'create_new_ticket':
            create_new_ticket();
            break;	
        
        default:
            // do nothing
            break;
    }
}

function ticket_details()
{
    $ticket_id = 2;

    $ticket = new WPSC_Ticket( $ticket_id );

    print_r($ticket);

    return $ticket;

}

function ticket_statuses()
{

}

function create_new_ticket()
{

}

