<?php
if (!defined('ABSPATH')) die('Silence is golden!');

if( current_user_can('activate_plugins') ){

	$WPHFE_backup_path = WP_CONTENT_URL.'/htaccess.backup';
	$WPHFE_orig_path = ABSPATH.'.htaccess';

?>
	<div class="wrap">
	<h2 class="wphfe-title"><?php _e( 'WP Htaccess File Editor' , 'wphfe' );?></h2>
	
	<?php
	/*
	 * Save Htaccess file
	 */
	if( ! empty( sanitize_text_field( $_POST['submit'] ) ) && ! empty( sanitize_text_field( $_POST['save_htaccess'] ) ) && check_admin_referer( 'wphfe_save', 'wphfe_save' ) ){
		
		$WPHFE_new_content = wp_kses_post( $_POST['ht_content'] );
		
		wphfe_delete_backup();
		
		if( wphfe_create_backup() ){

			if( wphfe_write_htaccess( $WPHFE_new_content ) ){

				
				?>
				
				<div class="notice notice-success" >

   					<p><strong> <?php _e('Htaccess file has been successfully updated!', 'wphfe'); ?> </strong> </p>

   					<p><?php _e('The original file has been  backed up in <code>wp-content</code> folder. Test your site thoroughly, If something is not working properly restore the original file from backup.', 'wphfe'); ?></p>
				
					<form method="post" action="admin.php?page=<?php echo $WPHFE_DIR; ?>">

						<?php wp_nonce_field('wphfe_delete','wphfe_delete'); ?>

						<input type="hidden" name="delete_backup" value="delete" />
						
						<input type="submit" class="button button-primary" name="submit" value="<?php _e('Remove Backup', 'wphfe');?>" />
						
						<a class="button button-primary" href="admin.php?page=<?php echo $WPHFE_DIR; ?>_backup"><?php _e('Restore Original','wphfe');?></a></p>
					
					</form>

				</div>
				
				<?php

			}else{

				echo'<div  class="notice notice-error"><p>'.__( 'The file could not be updated!', 'wphfe' ).'</p></div>';
				
			}

		}else{

			echo'<div class="notice notice-error"><p>'.__( 'Unable to create backup of the original file! <code>wp-content</code> folder is not writeable! Change the permissions of this folder and try again.', 'wphfe').'</strong></p></div>';
			
		}

		unset($WPHFE_new_content);

	/*
	 * Create a new Htaccess file 
	 */

	}elseif(! empty( sanitize_text_field( $_POST['submit'] ) ) && ! empty( sanitize_text_field( $_POST['create_htaccess'] ) ) && check_admin_referer( 'wphfe_create', 'wphfe_create' ) ){
		
		if( wphfe_write_htaccess( '# Created by WP Htaccess File Editor' ) === false){

			echo'<div  class="notice notice-error"><p>'.__('Unable to create new htaccess file.', 'wphfe').'</p></div>';
			
        }else{

			echo'<div  class="notice notice-success"><p>'.__('Htaccess file was successfully created.', 'wphfe').'</p></div>';
			
		 }

	/*
	 * Clear backup 
	 */

	}elseif( ! empty( sanitize_text_field( $_POST['submit'] ) ) && ! empty( sanitize_text_field( $_POST['delete_backup'] ) ) && check_admin_referer( 'wphfe_delete', 'wphfe_delete' ) ){
        
        if( wphfe_delete_backup() === false ){

           echo'<div class="notice notice-error"><p>'.__( 'Unable to remove backup file! <code>wp-content</code> folder is not writeable, Change the permissions of this folder and try again.', 'wphfe').'</p></div>';
        
        }else{

           echo'<div  class="notice notice-success"><p>'.__('Backup file has been successfully removed.', 'wphfe').'</p></div>';
        
        }

	/*
	 * Edit warning and form
	 */
	}else{

		?>
		<div class="notice notice-warning">

			<p><?php _e('<strong>WARNING:</strong> Any error in this file may cause malfunction of your site!', 'wphfe');?></p> 
			
			<p><?php _e('For more information, please visit', 'wphfe');?> 
				<a href="http://httpd.apache.org/docs/current/howto/htaccess.html" target="_blank">
					Apache Tutorial: .htaccess files
				</a> 
				 <?php _e('or','wphfe'); ?> 
				<a href="http://net.tutsplus.com/tutorials/other/the-ultimate-guide-to-htaccess-files/" target="_blank">
				The Ultimate Guide to .htaccess Files
				</a>. 
			</p>

		</div>

		<?php

		if( ! file_exists( $WPHFE_orig_path ) ){

			echo'<div class="notice notice-error">';
			echo'<p>'.__('Htaccess file does not exists!', 'wphfe').'</p>';
			echo'</div>';

			$success = false;

		}else{ 

			$success = true;

			if( !is_readable( $WPHFE_orig_path ) ){

				echo'<div class="notice notice-error">';
				echo'<p>'.__( 'Unable to read Htaccess file!', 'wphfe').'</p>';
				echo'</div>';
				$success = false;
			}

			if( $success == true ){

				@chmod( $WPHFE_orig_path, 0644 );

				$WPHFE_htaccess_content = @file_get_contents( $WPHFE_orig_path, false, NULL );

				if( $WPHFE_htaccess_content === false ){

					echo'<div class="notice notice-error">';

					echo'<p>'.__( 'Unable to read Htaccess file!', 'wphfe').'</p>';

					echo'</div>';

					$success = false;

				}else{

					$success = true;
				}
			}
		}

		if($success == true){
			?>
			<div class="postbox">

				<form method="post" action="admin.php?page=<?php echo $WPHFE_DIR; ?>">

					<input type="hidden" name="save_htaccess" value="save" />

					<?php wp_nonce_field('wphfe_save','wphfe_save'); ?>

					<h3 class="wphfe-title"><?php _e('Current Htaccess file', 'wphfe');?></h3>

					<textarea name="ht_content" class="wphfe-textarea widefat" wrap="off"><?php echo esc_html( $WPHFE_htaccess_content );?></textarea>
					
					<p class="submit"><input type="submit" class="button button-primary" name="submit" value="<?php _e('Update', 'wphfe');?>" /></p>
				
				</form>
			</div>

			<?php

			unset($WPHFE_htaccess_content);

		}else{

			?>
			<div class="postbox">

				<form method="post" action="admin.php?page=<?php echo $WPHFE_DIR; ?>">

					<input type="hidden" name="create_htaccess" value="create" />

					<?php wp_nonce_field('wphfe_create','wphfe_create'); ?>

					<p class="submit"><?php _e('Create new <code>.htaccess</code> file?', 'wphfe');?> 

						<input type="submit" class="button button-primary" name="submit" value="<?php _e('Create ', 'wphfe');?>" />
					
					</p>
				
				</form>
			</div>

			<?php
		}

		unset($success);
	}
	?>
	</div>

	<?php

	unset($WPHFE_orig_path);
	unset($WPHFE_backup_path);

}else{

	wp_die( __('You do not have permission to view this page','wphfe') );
}

