<?php
/**
 * Plugin Name:  My common functions
 * Plugin URI:   https://www.mrcarllister.co.uk/
 * Description:  Common functions I use on most sites that disable features I don't use, clean up WP and add functions I use
 * Version:      1.0.0
 * Author:       Carl Lister
 * Author URI:   https://www.mrcarllister.co.uk/
 */

 /**
  * Returns a print of array, prettified
  *
  * @param [type] $array
  * @return void
  */
function dd($array,$die = false)
{
    echo '<pre>';
    print_r($array);
    echo '</pre>';
    if($die)
        die();
}



 /**
  * Gets wp nav items as an array or returns false if does not exist
  *
  * @param string $menu_name
  * @return mixed
  */
function ee__get_menu_as_array(string $menu_name){
    if ( ( $locations = get_nav_menu_locations() ) && isset( $locations[ $menu_name ] ) ) {
        
        $menu = wp_get_nav_menu_object( $locations[ $menu_name ] );
        return wp_get_nav_menu_items($menu->term_id);    
    }
    return false; 
}

function ee__file_types_to_uploads($file_types)
{
    $new_filetypes = array();
    $new_filetypes['svg'] = 'image/svg+xml';
    $file_types = array_merge($file_types, $new_filetypes);
    return $file_types;
}
add_action('upload_mimes', 'ee__file_types_to_uploads');



/**
 * Load an inline SVG.
 *
 * @param string $filename The filename of the SVG you want to load.
 *
 * @return string The content of the SVG you want to load.
 */
function ee__load_inline_svg($path)
{
    if (file_exists($path)) {

        // Load and return the contents of the file
        return file_get_contents($path);
    }

    // Return a blank string if we can't find the file.
    return '';
}

function ee__display_the_admin_navbar()
{
    if(defined('SHOW_NAV') && is_user_logged_in()){

        $nav = (SHOW_NAV) ? SHOW_NAV : false;
        return $nav;
    }
}
add_filter('show_admin_bar', 'ee__display_the_admin_navbar');

add_action( 'wp_print_styles', 'wps_deregister_styles', 100 );
function wps_deregister_styles() {
    wp_dequeue_style( 'wp-block-library' );
}

// add_filter( 'block_editor_settings' , 'remove_guten_wrapper_styles' );
 function remove_guten_wrapper_styles( $settings ) {

    // dd($settings['styles']);
    // die();
    unset($settings['styles'][0]);
    unset($settings['styles'][1]);

    return $settings;
}

function ee__remove_menus(){
    // get current login user's role
    $roles = wp_get_current_user()->roles;
     
    // test role
    if( !in_array('editor',$roles)){
    return;
    }
     
    //remove menu from site backend.
    remove_menu_page( 'index.php' ); //Dashboard
    remove_menu_page( 'tools.php' ); //Tools


    }
    add_action( 'admin_menu', 'ee__remove_menus' , 100 );


/**
 * WordPress function for redirecting users on login based on user role
 */
function ee__login_redirect( $url, $request, $user ){
    if( $user && is_object( $user ) && is_a( $user, 'WP_User' ) ) {
        if( $user->has_cap( 'administrator' ) ) {
            $url = admin_url();
        } else {
            $url = home_url('/wp-admin/profile.php');
        }
    }
    return $url;
}

add_filter('login_redirect', 'ee__login_redirect', 10, 3 );