<?php
/**
 * Landing point for GlotPress installation
 */

define('GP_INSTALLING', true);
require_once( 'gp-load.php' );
require_once( BACKPRESS_PATH . 'class.bp-sql-schema-parser.php' );
require_once( GP_PATH . GP_INC . 'install-upgrade.php' );
require_once( GP_PATH . GP_INC . 'schema.php' );

$show_htaccess_instructions = false;
$action = 'upgrade';

/**
 * Whether the server software is Apache or something else
 * @global bool $is_apache
 */
$is_apache = (strpos($_SERVER['SERVER_SOFTWARE'], 'Apache') !== false || strpos($_SERVER['SERVER_SOFTWARE'], 'LiteSpeed') !== false);

/**
 * Whether the server software is IIS or something else
 * @global bool $is_IIS
 */
$is_IIS = !$is_apache && (strpos($_SERVER['SERVER_SOFTWARE'], 'Microsoft-IIS') !== false || strpos($_SERVER['SERVER_SOFTWARE'], 'ExpressionDevServer') !== false);

/**
 * Whether the server software is IIS 7.X or greater
 * @global bool $is_iis7
 */
$is_iis7 = $is_IIS && intval( substr( $_SERVER['SERVER_SOFTWARE'], strpos( $_SERVER['SERVER_SOFTWARE'], 'Microsoft-IIS/' ) + 14 ) ) >= 7;

if ( gp_get_option( 'gp_db_version' ) <= gp_get_option_from_db( 'gp_db_version' ) && ! isset( $_GET['force'] ) ) {
	$success_message = __( 'You already have the latest version, no need to upgrade!' );
}
else if ( gp_is_installed() ) {
	$success_message = __( 'GlotPress was successully upgraded!' );
	$errors = gp_upgrade();

	if( $is_iis7 ) {
		$show_webconfig_instructions = ! gp_set_webconfig() && empty( $errors );
	}
	else {
		$show_htaccess_instructions = ! gp_set_htaccess() && empty( $errors );
	}
}
else if ( defined('CUSTOM_USER_TABLE') ) {
	$errors = gp_install();

	$success_message = __( 'GlotPress was successully installed!' );

	if ( ! $errors ) {
		gp_create_initial_contents();
	}

	if( $is_iis7 ) {
		$show_webconfig_instructions = ! gp_set_webconfig() && empty( $errors );
	}
	else {
		$show_htaccess_instructions = ! gp_set_htaccess() && empty( $errors );
	}

	$action = 'installed';
}
else if( isset( $_POST['user_name'], $_POST['user_name'], $_POST['admin_password'], $_POST['admin_password2'], $_POST['admin_email'] ) ) {
	$user_name            = trim( stripslashes_deep( $_POST['user_name'] ) );
	$admin_password       = stripslashes_deep( $_POST['admin_password'] );
	$admin_password_check = stripslashes_deep( $_POST['admin_password2'] );
	$admin_email          = trim( stripslashes_deep( $_POST['admin_email'] ) );

	$errors = array();

	if ( empty( $user_name ) ) {
		$errors[] = __( 'Please provide a valid username.' );
	} elseif ( $user_name != sanitize_user( $user_name, true ) ) {
		$errors[] = __( 'The username you provided has invalid characters.' );
	} elseif ( empty( $admin_password ) ) {
		$errors[] = __( 'Please specify a password.' );
	} elseif ( $admin_password != $admin_password_check ) {
		$errors[] = __( 'Your passwords do not match. Please try again.' );
	} else if ( empty( $admin_email ) ) {
		$errors[] = __( 'You must provide an email address.' );
	} elseif ( ! is_email( $admin_email ) ) {
		$errors[] = __( 'You have an invalid email Address.' );
	}

	if( ! $errors ) {
		$errors = gp_install();

		$success_message  = __( 'GlotPress was successully installed!' );
		$success_message .= ' <a href="' . gp_url_login() . '">' . __('Log in') . '</a>';

		if ( ! $errors ) {
			gp_create_initial_contents( $user_name, $admin_password, $admin_email );
		}

		if( $is_iis7 ) {
			$show_webconfig_instructions = ! gp_set_webconfig() && empty( $errors );
		}
		else {
			$show_htaccess_instructions = ! gp_set_htaccess() && empty( $errors );
		}

		$action = 'installed';
	} else {
		$action = 'install';
	}
}
else {
	$action = 'install';
	$user_name = $admin_email = '';
}

gp_tmpl_load( 'install',  get_defined_vars() );