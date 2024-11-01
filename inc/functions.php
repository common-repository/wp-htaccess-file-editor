<?php
if ( ! defined( 'ABSPATH' ) ) die( 'Silence is golden!' );

/*
 *Create a htaccess file in the wp-content folder 
 */
function wphfe_secure_wp_content(){
	
	$wphfe_secure_path = ABSPATH.'wp-content/.htaccess';
	$wphfe_secure_text = 
	'# WP Htaccess File Editor - Secure backups
	<files htaccess.backup>
	order allow,deny
	deny from all
	</files>';

	if(is_readable(ABSPATH.'wp-content/.htaccess')){

		$wphfe_secure_content = @file_get_contents(ABSPATH.'wp-content/.htaccess');

		if($wphfe_secure_content !== false){

			if(strpos($wphfe_secure_content, 'Secure backups') === false){

				unset($wphfe_secure_content);
				$wphfe_create_sec = @file_put_contents(ABSPATH.'wp-content/.htaccess', $wphfe_secure_text, FILE_APPEND|LOCK_EX);
				
				if($wphfe_create_sec !== false){

					unset($wphfe_secure_text);
					unset($wphfe_create_sec);
					return true;

				}else{

					unset($wphfe_secure_text);
					unset($wphfe_create_sec);
					return false;
				}

			}else{

				unset($wphfe_secure_content);
				return true;
			}

		}else{

			unset($wphfe_secure_content);
			return false;
		}

	}else{

		if(file_exists(ABSPATH.'wp-content/.htaccess')){

			return false;

		}else{

			$wphfe_create_sec = @file_put_contents(ABSPATH.'wp-content/.htaccess', $wphfe_secure_text, LOCK_EX);

			if($wphfe_create_sec !== false){

				return true;

			}else{

				return false;
			}
		}
	}
}


/*
 * Create a backup of the original htaccess file 
 */
function wphfe_create_backup(){

	$WPHFE_backup_path = ABSPATH.'wp-content/htaccess.backup';
	$WPHFE_orig_path = ABSPATH.'.htaccess';
	@clearstatcache();

	wphfe_secure_wp_content();

	if(file_exists($WPHFE_backup_path)){

		wphfe_delete_backup();

		if(file_exists(ABSPATH.'.htaccess')){

			$htaccess_content_orig = @file_get_contents($WPHFE_orig_path, false, NULL);
			$htaccess_content_orig = trim($htaccess_content_orig);
			$htaccess_content_orig = str_replace('\\\\', '\\', $htaccess_content_orig);
			$htaccess_content_orig = str_replace('\"', '"', $htaccess_content_orig);
			@chmod($WPHFE_backup_path, 0666);
			$WPHFE_success = @file_put_contents($WPHFE_backup_path, $htaccess_content_orig, LOCK_EX);

			if($WPHFE_success === false){

				unset($WPHFE_backup_path);
				unset($WPHFE_orig_path);
				unset($htaccess_content_orig);
				unset($WPHFE_success);
				return false;

			}else{

				unset($WPHFE_backup_path);
				unset($WPHFE_orig_path);
				unset($htaccess_content_orig);
				unset($WPHFE_success);
				return true;

			}
			@chmod($WPHFE_backup_path, 0644);

		}else{

			unset($WPHFE_backup_path);
			unset($WPHFE_orig_path);
			return false;
		}

	}else{

		if(file_exists(ABSPATH.'.htaccess')){

			$htaccess_content_orig = @file_get_contents($WPHFE_orig_path, false, NULL);
			$htaccess_content_orig = trim($htaccess_content_orig);
			$htaccess_content_orig = str_replace('\\\\', '\\', $htaccess_content_orig);
			$htaccess_content_orig = str_replace('\"', '"', $htaccess_content_orig);
			@chmod($WPHFE_backup_path, 0666);
			$WPHFE_success = @file_put_contents($WPHFE_backup_path, $htaccess_content_orig, LOCK_EX);

			if($WPHFE_success === false){

				unset($WPHFE_backup_path);
				unset($WPHFE_orig_path);
				unset($htaccess_content_orig);
				unset($WPHFE_success);
				return false;

			}else{

				unset($WPHFE_backup_path);
				unset($WPHFE_orig_path);
				unset($htaccess_content_orig);
				unset($WPHFE_success);
				return true;

			}

			@chmod($WPHFE_backup_path, 0644);

		}else{

			unset($WPHFE_backup_path);
			unset($WPHFE_orig_path);
			return false;
		}
	}
}


/*
 *Restore backup 
 */
function wphfe_restore_backup(){

	$wphfe_backup_path = ABSPATH.'wp-content/htaccess.backup';
	$WPHFE_orig_path = ABSPATH.'.htaccess';

	@clearstatcache();

	if(!file_exists($wphfe_backup_path)){

		unset($wphfe_backup_path);
		unset($WPHFE_orig_path);
		return false;

	}else{

		if(file_exists($WPHFE_orig_path)){

			if(is_writable($WPHFE_orig_path)){

				@unlink($WPHFE_orig_path);

			}else{

				@chmod($WPHFE_orig_path, 0666);
				@unlink($WPHFE_orig_path);
			}
		}

		$wphfe_htaccess_content_backup = @file_get_contents($wphfe_backup_path, false, NULL);

		if(wphfe_write_htaccess($wphfe_htaccess_content_backup) === false){

			unset($wphfe_success);
			unset($WPHFE_orig_path);
			unset($wphfe_backup_path);
			return $wphfe_htaccess_content_backup;

		}else{

			wphfe_delete_backup();
			unset($wphfe_success);
			unset($wphfe_htaccess_content_backup);
			unset($WPHFE_orig_path);
			unset($wphfe_backup_path);
			return true;
		}
	}
}



/*
 * Delete backup file
 */
function wphfe_delete_backup(){

	$wphfe_backup_path = ABSPATH.'wp-content/htaccess.backup';

	@clearstatcache();

	if(file_exists($wphfe_backup_path)){

		if(is_writable($wphfe_backup_path)){

			@unlink($wphfe_backup_path);

		}else{

			@chmod($wphfe_backup_path, 0666);
			@unlink($wphfe_backup_path);
		}

		@clearstatcache();

		if(file_exists($wphfe_backup_path)){

			unset($wphfe_backup_path);

			return false;

		}else{

			unset($wphfe_backup_path);
			return true;
		}

	}else{

		unset($wphfe_backup_path);
		return true;
	}
}



/* 
 * Create a new htaccess file 
 */
function wphfe_write_htaccess($WPHFE_new_content){

	$WPHFE_orig_path = ABSPATH.'.htaccess';

	@clearstatcache();

	if(file_exists($WPHFE_orig_path)){

		if(is_writable($WPHFE_orig_path)){

			@unlink($WPHFE_orig_path);

		}else{

			@chmod($WPHFE_orig_path, 0666);
			@unlink($WPHFE_orig_path);
		}
	}

	$WPHFE_new_content = trim($WPHFE_new_content);
	$WPHFE_new_content = str_replace('\\\\', '\\', $WPHFE_new_content);
	$WPHFE_new_content = str_replace('\"', '"', $WPHFE_new_content);
	$WPHFE_write_success = @file_put_contents($WPHFE_orig_path, $WPHFE_new_content, LOCK_EX);
	@clearstatcache();

	if(!file_exists($WPHFE_orig_path) && $WPHFE_write_success === false){

		unset($WPHFE_orig_path);
		unset($WPHFE_new_content);
		unset($WPHFE_write_success);
		return false;

	}else{

		unset($WPHFE_orig_path);
		unset($WPHFE_new_content);
		unset($WPHFE_write_success);
		return true;
	}
}
