=== WP Htaccess File Editor ===
Contributors: Bellathemes
Author URI: https://bellathemes.com
Tags: htaccess,file,editor,backup,admin
Version: 1.0.1
Requires at least: 3.0
Tested up to: 5.0
Stable tag: 1.0.0
License: GPLv2 or later

Safe & simple WordPress htaccess file editor with automatic backup.

== Description ==

WP Htaccess File Editor provides a simple, safe and fast way to edit the WordPress site’s .htaccess file from  admin panel. It automatically creates backups every time you make a change to the htaccess file. Backups can be restored directly from the plugin, or via FTP.

Access WP Htaccess File Editor via the “Htaccess” menu in WP admin.


AUTOMATIC BACKUPS AND RESTORE

WP Htaccess File Editor makes automatic backups of htaccess file every time you make a change to it. You can restore backup  just by clicking 'Restore Backup' button. Backups are located in /wp-content/ folder so you can easily find the htaccess backup and restore it manually.

== Installation ==

1. Extract the zip into the *wp-content/plugins* directory
2. Activate the plugin through the Admin panel of your WordPress
3. Go to Menu -> Htaccess


== Screenshots ==

1. Editing htaccess file
2. Automatic backup and restore htaccess file
3. Create new backup file

== Frequently Asked Questions ==

= Cannot create or edit the htaccess file =
Sorry, we can’t change the file access privileges set by your server. You’ll have to edit the file via FTP.


= I edited my htaccess file and now my site does not work =
You probably have a syntax error in the file or on the server you are not allowed any of the settings in your htaccess file.
Try to restore the original backup file. If restoration is not possible with this plugin, restore the backup file from the folder "wp-content" manually.


= How do i get support? =
Please go to our <a target="_blank" href="https://wordpress.org/support/plugin/wp-htaccess-file-editor">support forums</a>. We’ll gladly help you.

== Changelog ==

= 1.0.1 =
  * readme.txt updated

= 1.0.0 =
* Initial release

