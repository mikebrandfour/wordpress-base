<?php
/*
Plugin Name: BrandFour Utilities
Description: Various Utilities for website functionality 
Author: BrandFour
Version: 1.0
Author URI: http://brandfour.com
*/



// Remove Wordpress logo in admin bar
add_action( 'admin_bar_menu', 'remove_wp_logo', 999 );

function remove_wp_logo( $wp_admin_bar ) {
	$wp_admin_bar->remove_node( 'wp-logo' );
}

//include admin stylesheet
function my_admin_theme_style() {
    wp_enqueue_style('my-admin-theme', plugins_url('admin.css', __FILE__));
}
add_action('admin_enqueue_scripts', 'my_admin_theme_style');
add_action('login_enqueue_scripts', 'my_admin_theme_style');

//footer admin logo
function wpse_edit_footer() {
    add_filter( 'admin_footer_text', 'wpse_edit_text', 11 );
}

//footer admin logo
function wpse_edit_text($content) {
    return '<a href="http://brandfour.com"><img src="http://brandfour.com/assets/front/img/header-logo.svg" alt="brandfour.com" height="30"></a>';
}

add_action( 'admin_init', 'wpse_edit_footer' );


 //Add application widget to the dashboard.
function B4Widget() {
    wp_add_dashboard_widget(
        'submitted_applications',         
        '<a href="http://brandfour.com" target="_blank"><img src="http://brandfour.com/assets/front/img/header-logo.svg" height="30px"></a>',        
        'BrandFour_Latest' 
    );  
}
add_action( 'wp_dashboard_setup', 'B4Widget' );

//widget content
function BrandFour_Latest() {

    echo "<p><strong>Email:</strong><a href='mailto:hello@brandfour.com'>hello@brandfour.com</a></p>";
    echo "<p><strong>Tel:</strong>+44 (0)1522 700 080</p>";
    }
    
//login image
function change_my_wp_login_image() {
    echo "
<style>
body.login #login h1 a {
background: url('http://brandfour.com/assets/front/img/header-logo.svg') 8px 0 no-repeat transparent;
height:25px!important;
width:320px; }
</style>
";
}
add_action("login_head", "change_my_wp_login_image");



// Enforce strong passwords
function slt_strongPasswords( $errors ) {
    $enforce = true;
    $args = func_get_args();
    $userID = $args[2]->ID;
    if ( $userID ) {
        $userInfo = get_userdata( $userID );
        if ( $userInfo->user_level < 5 ) {
            $enforce = false;
        }
    } else {
        if ( in_array( $_POST["role"], array( "subscriber", "author", "contributor", "administrator" ) ) ) {
            $enforce = false;
        }
    }
    if ( $enforce && !$errors->get_error_data("pass") && $_POST["pass1"] && slt_passwordStrength( $_POST["pass1"], $_POST["user_login"] ) != 4 ) {
            $errors->add( 'pass', __( '<strong>ERROR</strong>: Please make the password a strong one.' ) );
    }
    return $errors;
}
add_action( 'user_profile_update_errors', 'slt_strongPasswords', 0, 3 );
 
function slt_passwordStrength( $i, $f ) {
    $h = 1; $e = 2; $b = 3; $a = 4; $d = 0; $g = null; $c = null;
    if ( strlen( $i ) < 4 )
        return $h;
    if ( strtolower( $i ) == strtolower( $f ) )
        return $e;
    if ( preg_match( "/[0-9]/", $i ) )
        $d += 10;
    if ( preg_match( "/[a-z]/", $i ) )
        $d += 26;
    if ( preg_match( "/[A-Z]/", $i ) )
        $d += 26;
    if ( preg_match( "/[^a-zA-Z0-9]/", $i ) )
        $d += 31;
    $g = log( pow( $d, strlen( $i ) ) );
    $c = $g / log( 2 );
    if ( $c < 40 )
        return $e;
    if ( $c < 56 )
        return $b;
    return $a;
}

// Customizer logo
function themeslug_theme_customizer( $wp_customize ) {
$wp_customize->add_section( 'themeslug_logo_section' , array(
    'title'       => __( 'Logo', 'themeslug' ),
    'priority'    => 30,
    'description' => 'Upload a logo to replace the default site name and description in the header',
) );
    
$wp_customize->add_setting( 'themeslug_logo' );
    
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'themeslug_logo', array(
    'label'    => __( 'Logo', 'themeslug' ),
    'section'  => 'themeslug_logo_section',
    'settings' => 'themeslug_logo',
) ) );
}
add_action( 'customize_register', 'themeslug_theme_customizer' );

?>