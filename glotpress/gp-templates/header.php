<?php
wp_enqueue_style( 'base' );
wp_enqueue_script( 'jquery' );
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
		<title><?php echo gp_title(); ?></title>
		<?php gp_head(); ?>
	</head>
	<body <?php body_class(); ?>>
	<script type="text/javascript">document.body.className = document.body.className.replace('no-js','js');</script>
		<div class="gp-content">
	    <div id="gp-js-message"></div>
		<h1>
			<a class="logo" href="<?php echo apply_filters( 'gp_logo_url', gp_url( '/' ) ); ?>" rel="home">
				<img alt="GlotPress" src="<?php echo apply_filters( 'gp_logo_img_url', gp_url_img( 'glotpress-logo.png' ) ); ?>" />
			</a>
			<?php echo gp_breadcrumb(); ?>
			<span id="hello">
			<?php
			if (GP::$user->logged_in()):
				$user = GP::$user->current();

				printf( __('Hi, %s.'), '<a href="'.gp_url( '/profile' ).'">'.$user->user_login.'</a>' );
				$logout_string = '<a href="' . gp_url('/logout') . '">' . __('Log out') . '</a>';
				echo apply_filters( 'gp_logout_link', $logout_string );
				?>
			<?php elseif( ! GP_INSTALLING ): 
				$login_string = '<strong><a href="' . gp_url_login() . '">' . __('Log in') . '</a></strong>';
				echo apply_filters( 'gp_login_link', $login_string );
			endif; ?>
			<?php do_action( 'after_hello' ); ?>
			</span>
			<div class="clearfix"></div>
		</h1>
		<div class="clear after-h1"></div>
		<?php if (gp_notice('error')): ?>
			<div class="error">
				<?php echo gp_notice( 'error' ); //TODO: run kses on notices ?>
			</div>
		<?php endif; ?>
		<?php if (gp_notice()): ?>
			<div class="notice">
				<?php echo gp_notice(); ?>
			</div>
		<?php endif; ?>
		<?php do_action( 'after_notices' ); ?>
