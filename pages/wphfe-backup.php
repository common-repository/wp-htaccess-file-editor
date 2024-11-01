<?php
if (! defined('ABSPATH')) die('Silence is golden!');

if( current_user_can( 'activate_plugins' ) ){

?>

	<div class="wrap">

	<h2 class="wphfe-title"><?php _e( 'WP Htaccess File Editor' , 'wphfe' );?> - <?php _e('Backup', 'wphfe'); ?></h2>

	<?php

	/*
	 *Restore Backup 
	 */

	if( ! empty( sanitize_text_field( $_POST['submit'] ) ) && ! empty( sanitize_text_field( $_POST['restore_backup'] ) ) && check_admin_referer( 'wphfe_restoreb', 'wphfe_restoreb' ) ){

		$wphfe_restore_result = wphfe_restore_backup();
		
		if($wphfe_restore_result === false){

			echo'<div class="notice notice-error"><p>'.__('Unable to restore Htaccess file! Please check file permissions and try again.', 'wphfe').'</p></div>';
		
		}elseif( $wphfe_restore_result === true ){

			echo'<div class="notice notice-success"><p>'.__('Htaccess file has been successfully restored!', 'wphfe').'</p></div>';
			
		}else{

			echo'<div class="notice notice-error"><p><strong>'.__('Unable to restore Htaccess file!', 'wphfe').'</strong></p></div>';
			echo'<div class="postbox">';
			echo'<p>'.__('Please update your Htaccess file manually with following original content. ','wphfe').':</p>';
			echo'<textarea class="wphfe-textarea widefat">'. esc_html( $wphfe_restore_result ).'</textarea>';
			echo'</div>';
		}


	/* 
	 * Create Backup
	 */

	}elseif( !empty( sanitize_text_field( $_POST['submit'] ) ) && !empty( sanitize_text_field( $_POST['create_backup'] ) ) && check_admin_referer('wphfe_createb', 'wphfe_createb')){
		
		if(wphfe_create_backup()){

			echo'<div class="notice notice-success"><p>'.__('Backup file was created successfully. The backup file is located in the <code>wp-content</code> folder', 'wphfe').'</p></div>';

		}else{

			echo'<div  class="notice notice-error"><p>'.__('Unable to create backup! <code>wp-content</code> folder is not writeable! Change the permissions and try again.', 'wphfe').'</p></div>';
			
		}


	/*
	 * Delete Backup
	 */

	}elseif( !empty( sanitize_text_field( $_POST['submit'] ) ) && !empty( sanitize_text_field( $_POST['delete_backup'] ) ) && check_admin_referer('wphfe_deleteb', 'wphfe_deleteb')){

		if( wphfe_delete_backup() ){

			echo'<div  class="notice notice-success"><p>'.__('Backup file has been successfully removed.', 'wphfe').'</p></div>';
		
		}else{

			echo'<div id="message" class="notice notice-error"><p>'.__('Unable to remove backup file! Please check file permissions and try again.','wphfe').'</p></div>';
			
		}

	 /*
	  * Backup defaul page
	  */

	}else{

		if( file_exists( ABSPATH.'wp-content/htaccess.backup' ) ){

			?> 
			<div class="postbox">

				<form method="post" action="admin.php?page=<?php echo $WPHFE_DIR; ?>_backup">

					<?php wp_nonce_field('wphfe_restoreb','wphfe_restoreb'); ?>

					<input type="hidden" name="restore_backup" value="restore" />

					<p class="submit"><?php _e('Do you want to restore the backup file?', 'wphfe'); ?> 

						<input type="submit" class="button button-primary" name="submit" value="<?php _e('Restore Backup', 'wphfe'); ?>" />

					</p>
				
				</form>

			</div>

			
			<div class="postbox">

				<form method="post" action="admin.php?page=<?php echo $WPHFE_DIR; ?>_backup">

					<?php wp_nonce_field('wphfe_deleteb','wphfe_deleteb'); ?>

					<input type="hidden" name="delete_backup" value="delete" />

					<p class="submit"><?php _e('Do you want to delete a backup file?', 'wphfe'); ?> 

						<input type="submit" class="button button-primary" name="submit" value="<?php _e('Remove Backup', 'wphfe'); ?>" />

					</p>

				</form>

			</div>

			<?php
			
		}else{
			
			echo '<div class="notice notice-error"><p>'.__('Backup file not found!','wphfe').'</p></div>';
			
			?>

			<div class="postbox">

				<form method="post" action="admin.php?page=<?php echo $WPHFE_DIR; ?>_backup">

					<?php wp_nonce_field('wphfe_createb','wphfe_createb'); ?>

					<input type="hidden" name="create_backup" value="create" />

					<p class="submit"><?php _e('Do you want to create a new backup file?', 'wphfe'); ?> <input type="submit" class="button button-primary" name="submit" value="<?php _e('Create New', 'wphfe'); ?>" /></p>
				
				</form>

		   </div>

			<?php
			
		}
	}
	?>
	
	</div>
	<?php

}else{

	wp_die( __( 'You do not have permission to view this page','wphfe' ) );
}
