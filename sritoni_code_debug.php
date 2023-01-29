<?php
/**
*Plugin Name: Sritoni Custom Code debug
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
    add_action('admin_menu', 'add_submenu_page_callback');
  	
}



// by keeping the parent slug and the menu slug the same, we avoid the duplicate submenu
// clicking on the main menu or this submenu gives same results


function add_submenu_page_callback()
{
    /*
    add_submenu_page( string $parent_slug, string $page_title, string $menu_title, string $capability, string $menu_slug, callable $function = '' )
    *					        parent slug		 newsubmenupage	 submenu title  	  capability			new submenu slug		callback for display page
    */
    $hook_suffix_menu_page_madhu_custom_code = 
        add_submenu_page(   'users.php',                                // parent menu slug	
                            'my-code-test',                             // page title	
                            'my-code-test',                             // sub menu title	
                            'manage_options',                           // capability
                            'my-code-test',                             // sub menu slug		
                            'madhu_custom_code_submenu_page_render'     // callback function 
                        );
    return;
}


function madhu_custom_code_submenu_page_render()
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

