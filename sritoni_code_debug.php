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
            <input type="submit" name="button" 	value="custom_fields"/>
            <input type="submit" name="button" 	value="statuses"/>
            <input type="submit" name="button" 	value="categories"/>
            <input type="submit" name="button" 	value="create_new_ticket"/>
            <input type="submit" name="button" 	value="change_ticket_status"/>
            <input type="submit" name="button" 	value="Get_filtered_Ticket_list"/>
            <input type="number" id="ticket_id" name="ticket_id" min="1" max="10000">
        </form>

        
    <?php

    $button = sanitize_text_field( $_POST['button'] );
    $ticket_id = sanitize_text_field( $_POST['ticket_id'] );

    switch ($button) 
    {
        case 'ticket_details':
            ticket_details($ticket_id);
            break;

        case 'custom_fields':
            custom_fields();
            break;

        case 'statuses':
            statuses();
            break;

        case 'categories':
            categories();
            break;

        case 'create_new_ticket':
            create_new_ticket();
            break;
            
        case 'change_ticket_status':
            change_ticket_status();
            break;

        case 'Get_filtered_Ticket_list':
            change_ticket_status();
            break;
        
        default:
            // do nothing
            break;
    }
}

function ticket_details($ticket_id)
{
    $ticket = new WPSC_Ticket( $ticket_id );

    echo '<pre>';
    print_r($ticket);
    echo  '</pre>';

    $status_id = $ticket->status;

    echo '<pre>';
    
    print_r($status_id);
    echo  '</pre>';
    
    // get customer object based on customer's registered email
    $email = 'sritoni1@headstart.edu.in';
    $customer = WPSC_Customer::get_by_email( $email );

    echo nl2br("Customer details fetched using email: " . $email . " \n");
    echo '<pre>';
    print_r($customer->user->ID);
    echo  '</pre>';


}

function custom_fields()
{
    // extract custom field data.
    foreach ( WPSC_Custom_Field::$custom_fields as $cf ) {

        if ( ! in_array( $cf->field, array( 'ticket', 'agentonly' ) )  ) {
            continue;
        
        }
        echo '<pre>';
        print_r($cf);
        echo  '</pre>';
    }

    unset ($cf);

    // to find 
    $slug = 'status';
    $cf = WPSC_Custom_Field::get_cf_by_slug( $slug );

    echo '<pre>';
    print ("custom field object corresponding to slug: " . $slug);
    print_r($cf);
    echo  '</pre>';
    
}


function statuses()
{
    // Get all the statuses
    $statuses = WPSC_Status::find( array( 'items_per_page' => 0 ) )['results'];
    foreach ( $statuses as $status ) {
        echo '<pre>';
        print_r($status);
        echo  '</pre>';
    }
    
}


function categories()
{
    // Get all the statuses
    $categories = WPSC_Category::find( array( 'items_per_page' => 0 ) )['results'];
    foreach ( $categories as $category ) {
        echo '<pre>';
        print_r($category);
        echo  '</pre>';
    }
    
}




function create_new_ticket()
{
    // ticket data.
	$data = array();

	// customer name. Not used currently
	$name = 'Sritoni1 Moodle1';
    $email = 'sritoni1@headstart.edu.in';

    $customer = WPSC_Customer::get_by_email( $email );

    // customer's WP ID
    $data['customer'] = $customer->user->ID;

    // extract custom field data.
    foreach ( WPSC_Custom_Field::$custom_fields as $cf ) {

        if ( ! in_array( $cf->field, array( 'ticket', 'agentonly' ) )  ) {
            continue;
        
        }

        if ( method_exists( $cf->type, 'get_default_value' ) ) {
            $data[ $cf->slug ] = $cf->type::get_default_value( $cf );
        }
    }

    $description_attachments = '';

    $description = "This is a test ticket created using plugin php code";
    $$data['subject'] = "This is a test for a ticket creation using plugin code";
    $data['last_reply_on'] = ( new DateTime() )->format( 'Y-m-d H:i:s' );
    $data['date_closed'] = '0000-00-00 00:00:00';


    $data['source']     = 'MA_plugin_code';
	// $data['ip_address'] = WPSC_DF_IP_Address::get_current_user_ip();
	// $data['browser']    = WPSC_DF_Browser::get_user_browser();
	// $data['os']         = WPSC_DF_OS::get_user_platform();

    $data['is_active'] = 1;

    unset($data['description']);
    unset($data['attachments']);

    $ticket = WPSC_Ticket::insert( $data );

    if ( ! $ticket ) {
        echo '<pre>';
        print('Ticket could not be created');
        echo  '</pre>';
       
    }
    else
    {
        echo '<pre>';
        print_r($ticket);
        echo  '</pre>';
    }

    // Create report thread.
    $thread = WPSC_Thread::insert(
                                    array(
                                        'ticket'      => $ticket->id,
                                        'customer'    => $ticket->customer->id,
                                        'type'        => 'report',
                                        'body'        => $description,
                                        'attachments' => $description_attachments,
                                        'source'      => 'MA_plugin_code',
                                    )
                            );

}


function change_ticket_status()
{

    $ticket_id = 2;

    $ticket = new WPSC_Ticket( $ticket_id );

    $existing_status_obj_of_ticket = $ticket->status;

    echo '<pre>';
    print_r($existing_status_obj_of_ticket);
    echo  '</pre>';

    $ticket->status = 5;

    $ticket->date_updated = new DateTime();

    $ticket->save();
}

function Get_filtered_Ticket_list()
{

}
