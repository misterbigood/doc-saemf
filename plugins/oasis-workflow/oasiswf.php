<?php
/*
 Plugin Name: Oasis Workflow
 Plugin URI: http://www.oasisworkflow.com
 Description: Automate your WordPress Editorial Workflow.
 Version: 1.9
 Author: Nugget Solutions Inc.
 Author URI: http://www.nuggetsolutions.com
 Text Domain: oasis-workflow
----------------------------------------------------------------------
Copyright 2011-2015 Nugget Solutions Inc.

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
*/


//Install, activate, deactivate and uninstall

define( 'OASISWF_VERSION' , '1.9' );
define( 'OASISWF_DB_VERSION','1.9');
define( 'OASISWF_PATH', plugin_dir_path(__FILE__) ); //use for include files to other files
define( 'OASISWF_ROOT' , dirname(__FILE__) );
define( 'OASISWF_FILE_PATH' , OASISWF_ROOT . '/' . basename(__FILE__) );
define( 'OASISWF_URL' , plugins_url( '/', __FILE__ ) );
define( 'OASISWF_SETTINGS_PAGE' , esc_url(add_query_arg( 'page', 'ef-settings', get_admin_url( null, 'admin.php' ) ) ) );
define( 'OASIS_PER_PAGE', '50' );
define( 'OASISWF_EDIT_DATE_FORMAT', 'm-M d, Y');

/**
 * FCInitialization Class
 *
 * This class will initialize the plugin
 *
 * @since 2.0
 */

class FCInitialization
{

   private static $current_screen_pointers = array();

	function  __construct()
	{
		//run on activation of plugin
		register_activation_hook( __FILE__, array('FCInitialization', 'oasiswf_activate') );

		//run on deactivation of plugin
		register_deactivation_hook( __FILE__, array('FCInitialization', 'oasiswf_deactivate') );

		//run on uninstall
		register_uninstall_hook(__FILE__, array('FCInitialization', 'oasiswf_uninstall') );

      // Load plugin text domain
      add_action( 'init', array( $this, 'load_oasis_workflow_textdomain') );

      // Show welcome message
      add_action( 'admin_enqueue_scripts', array( $this, 'show_welcome_message_pointers' ) );

	}

	static function oasiswf_activate( $networkwide )
	{
		global $wpdb;
		FCInitialization::run_on_activation();
		if (function_exists('is_multisite') && is_multisite())
		{
	        // check if it is a network activation - if so, run the activation function for each blog id
	        if ($networkwide)
	        {
	            // Get all blog ids
	            $blogids = $wpdb->get_col("SELECT blog_id FROM {$wpdb->base_prefix}blogs");
	            foreach ($blogids as $blog_id)
	            {
	            	switch_to_blog($blog_id);
	               FCInitialization::run_for_site();
	               restore_current_blog();
	            }
	            return;
	        }
    	}

    	// for non-network sites only
		FCInitialization::run_for_site();
	}

	static function oasiswf_deactivate($networkwide)
	{
	    global $wpdb;

	    if (function_exists('is_multisite') && is_multisite())
	    {
	        // check if it is a network activation - if so, run the activation function for each blog id
	        if ($networkwide)
	        {
	            // Get all blog ids
	            $blogids = $wpdb->get_col("SELECT blog_id FROM {$wpdb->base_prefix}blogs");
	            foreach ($blogids as $blog_id)
	            {
	                switch_to_blog($blog_id);
	                FCInitialization::run_on_deactivation();
	                restore_current_blog();
	            }
	            return;
	        }
	    }
	    FCInitialization::run_on_deactivation();
	}

	static function oasiswf_uninstall()
	{
		global $wpdb;
		FCInitialization::run_on_uninstall();
		if (function_exists('is_multisite') && is_multisite())
		{
			//Get all blog ids; foreach them and call the uninstall procedure on each of them
			$blog_ids = $wpdb->get_col("SELECT blog_id FROM {$wpdb->base_prefix}blogs");

			//Get all blog ids; foreach them and call the install procedure on each of them if the plugin table is found
			foreach ( $blog_ids as $blog_id )
			{
				switch_to_blog( $blog_id );
				if( $wpdb->query( "SHOW TABLES FROM ".$wpdb->dbname." LIKE '".$wpdb->prefix."fc_%'" ) )
				{
					FCInitialization::delete_for_site();
				}
				restore_current_blog();
			}
			return;
		}
		FCInitialization::delete_for_site();
	}

   public static function load_oasis_workflow_textdomain() {
      load_plugin_textdomain('oasisworkflow', false, basename( dirname( __FILE__ ) ) . '/languages' );
   }

   static function run_on_activation()
	{
		$pluginOptions = get_site_option('oasiswf_info');
		if ( false === $pluginOptions )
		{
			$oasiswf_info=array(
				'version'=>OASISWF_VERSION,
				'db_version'=>OASISWF_DB_VERSION
			);

			$oasiswf_process_info = array(
				'assignment' => OASISWF_URL . 'img/assignment.gif',
				'review' => OASISWF_URL . 'img/review.gif',
				'publish' => OASISWF_URL . 'img/publish.gif'
			);

			$oasiswf_path_info = array(
				'success' => array(__('Success','oasisworkflow'), 'blue'),
				'failure' => array(__('Failure','oasisworkflow'), 'red')
			);

			$oasiswf_status = array(
				'assignment' => __('In Progress', "oasisworkflow"),
				'review' => __('In Review', "oasisworkflow"),
				'publish' => __('Ready to Publish', "oasisworkflow")
			);

			$oasiswf_placeholders = array(
				'%first_name%' => __('first name', "oasisworkflow"),
				'%last_name%' => __('last name', "oasisworkflow"),
				'%post_title%' => __('post title', "oasisworkflow")
			);

			update_site_option('oasiswf_info', $oasiswf_info) ;
			update_site_option('oasiswf_process', $oasiswf_process_info) ;
			update_site_option('oasiswf_path', $oasiswf_path_info) ;
			update_site_option('oasiswf_status', $oasiswf_status) ;
			update_site_option('oasiswf_placeholders', $oasiswf_placeholders) ;

         $show_upgrade_notice = "yes";
         update_site_option("oasiswf_show_upgrade_notice", $show_upgrade_notice) ;

		}
		else if ( OASISWF_VERSION != $pluginOptions['version'] )
		{
		   FCInitialization::run_on_upgrade();
		}

		if ( !wp_next_scheduled('oasiswf_email_schedule') )
			wp_schedule_event(time(), 'daily', 'oasiswf_email_schedule');
	}

	static function run_for_site( )
	{
		global $wp_roles;
		
		$skip_workflow_roles = array("administrator");
		$show_wfsettings_on_post_types = array('post', 'page');

		if ( ! get_option( 'oasiswf_skip_workflow_roles' ) ) {
			update_option("oasiswf_skip_workflow_roles", $skip_workflow_roles) ;
		}

		if ( ! get_option( 'oasiswf_show_wfsettings_on_post_types' ) ) {
			update_option("oasiswf_show_wfsettings_on_post_types", $show_wfsettings_on_post_types) ;
		}

		$email_settings = array(
				'from_name' => '',
				'from_email_address' => '',
				'assignment_emails' => 'yes',
				'reminder_emails' => 'no',
				'post_publish_emails' => 'yes'
		);

		if ( ! get_option( 'oasiswf_email_settings' ) ) {
			update_option("oasiswf_email_settings", $email_settings) ;
		}
		
		// add edit_other_posts and pages to the author role
		if ( class_exists('WP_Roles') ) {
			if ( ! isset( $wp_roles ) ) {
				$wp_roles = new WP_Roles();
			}
		}
		 
		$wp_roles->add_cap( 'author', 'edit_others_posts' );
		$wp_roles->add_cap( 'author', 'edit_others_pages' );		

		FCInitialization::install_admin_database();
	   FCInitialization::install_site_database();
	}

	static function run_on_upgrade( )
	{
	   $pluginOptions = get_site_option('oasiswf_info');
		if ($pluginOptions['version'] == "1.0.5")
		{
			FCInitialization::upgrade_database_1012();
			FCInitialization::upgrade_database_1015();
			FCInitialization::upgrade_database_1016();
			FCInitialization::upgrade_database_1019();
			FCInitialization::upgrade_database_11();
			FCInitialization::upgrade_database_13();
			FCInitialization::upgrade_database_14();
			FCInitialization::upgrade_database_15();
			FCInitialization::upgrade_database_16();
			FCInitialization::upgrade_database_17();
			FCInitialization::upgrade_database_19();
		}
		else if ($pluginOptions['version'] == "1.0.6")
		{
			FCInitialization::upgrade_database_1012();
			FCInitialization::upgrade_database_1015();
			FCInitialization::upgrade_database_1016();
			FCInitialization::upgrade_database_1019();
			FCInitialization::upgrade_database_11();
			FCInitialization::upgrade_database_13();
			FCInitialization::upgrade_database_14();
			FCInitialization::upgrade_database_15();
			FCInitialization::upgrade_database_16();
			FCInitialization::upgrade_database_17();
			FCInitialization::upgrade_database_19();
		}
		else if ($pluginOptions['version'] == "1.0.7")
		{
			FCInitialization::upgrade_database_1012();
			FCInitialization::upgrade_database_1015();
			FCInitialization::upgrade_database_1016();
			FCInitialization::upgrade_database_1019();
			FCInitialization::upgrade_database_11();
			FCInitialization::upgrade_database_13();
			FCInitialization::upgrade_database_14();
			FCInitialization::upgrade_database_15();
			FCInitialization::upgrade_database_16();
			FCInitialization::upgrade_database_17();
			FCInitialization::upgrade_database_19();
		}
		else if ($pluginOptions['version'] == "1.0.8")
		{
			FCInitialization::upgrade_database_1012();
			FCInitialization::upgrade_database_1015();
			FCInitialization::upgrade_database_1016();
			FCInitialization::upgrade_database_1019();
			FCInitialization::upgrade_database_11();
			FCInitialization::upgrade_database_13();
			FCInitialization::upgrade_database_14();
			FCInitialization::upgrade_database_15();
			FCInitialization::upgrade_database_16();
			FCInitialization::upgrade_database_17();
			FCInitialization::upgrade_database_19();
		}
		else if ($pluginOptions['version'] == "1.0.9")
		{
			FCInitialization::upgrade_database_1012();
			FCInitialization::upgrade_database_1015();
			FCInitialization::upgrade_database_1016();
			FCInitialization::upgrade_database_1019();
			FCInitialization::upgrade_database_11();
			FCInitialization::upgrade_database_13();
			FCInitialization::upgrade_database_14();
			FCInitialization::upgrade_database_15();
			FCInitialization::upgrade_database_16();
			FCInitialization::upgrade_database_17();
			FCInitialization::upgrade_database_19();
		}
		else if ($pluginOptions['version'] == "1.0.10")
		{
			FCInitialization::upgrade_database_1012();
			FCInitialization::upgrade_database_1015();
			FCInitialization::upgrade_database_1016();
			FCInitialization::upgrade_database_1019();
			FCInitialization::upgrade_database_11();
			FCInitialization::upgrade_database_13();
			FCInitialization::upgrade_database_14();
			FCInitialization::upgrade_database_15();
			FCInitialization::upgrade_database_16();
			FCInitialization::upgrade_database_17();
			FCInitialization::upgrade_database_19();
		}
	   else if ($pluginOptions['version'] == "1.0.11")
		{
			FCInitialization::upgrade_database_1012();
			FCInitialization::upgrade_database_1015();
			FCInitialization::upgrade_database_1016();
			FCInitialization::upgrade_database_1019();
			FCInitialization::upgrade_database_11();
			FCInitialization::upgrade_database_13();
			FCInitialization::upgrade_database_14();
			FCInitialization::upgrade_database_15();
			FCInitialization::upgrade_database_16();
			FCInitialization::upgrade_database_17();
			FCInitialization::upgrade_database_19();
		}
		else if ($pluginOptions['version'] == "1.0.12")
		{
         FCInitialization::upgrade_database_1015();
		 	FCInitialization::upgrade_database_1016();
		 	FCInitialization::upgrade_database_1019();
		 	FCInitialization::upgrade_database_11();
		 	FCInitialization::upgrade_database_13();
		 	FCInitialization::upgrade_database_14();
		 	FCInitialization::upgrade_database_15();
		 	FCInitialization::upgrade_database_16();
		 	FCInitialization::upgrade_database_17();
		 	FCInitialization::upgrade_database_19();
		}
	   else if ($pluginOptions['version'] == "1.0.13")
		{
         FCInitialization::upgrade_database_1015();
		 	FCInitialization::upgrade_database_1016();
		 	FCInitialization::upgrade_database_1019();
		 	FCInitialization::upgrade_database_11();
		 	FCInitialization::upgrade_database_13();
		 	FCInitialization::upgrade_database_14();
		 	FCInitialization::upgrade_database_15();
		 	FCInitialization::upgrade_database_16();
		 	FCInitialization::upgrade_database_17();
		 	FCInitialization::upgrade_database_19();
		}
		else if ($pluginOptions['version'] == "1.0.14")
		{
         FCInitialization::upgrade_database_1015();
		 	FCInitialization::upgrade_database_1016();
		 	FCInitialization::upgrade_database_1019();
		 	FCInitialization::upgrade_database_11();
		 	FCInitialization::upgrade_database_13();
		 	FCInitialization::upgrade_database_14();
		 	FCInitialization::upgrade_database_15();
		 	FCInitialization::upgrade_database_16();
		 	FCInitialization::upgrade_database_17();
		 	FCInitialization::upgrade_database_19();
		}
		else if ($pluginOptions['version'] == "1.0.15")
		{
         FCInitialization::upgrade_database_1016();
         FCInitialization::upgrade_database_1019();
         FCInitialization::upgrade_database_11();
         FCInitialization::upgrade_database_13();
         FCInitialization::upgrade_database_14();
         FCInitialization::upgrade_database_15();
         FCInitialization::upgrade_database_16();
         FCInitialization::upgrade_database_17();
         FCInitialization::upgrade_database_19();
		}
		else if ($pluginOptions['version'] == "1.0.16")
		{
			FCInitialization::upgrade_database_1019();
			FCInitialization::upgrade_database_11();
			FCInitialization::upgrade_database_13();
			FCInitialization::upgrade_database_14();
			FCInitialization::upgrade_database_15();
			FCInitialization::upgrade_database_16();
			FCInitialization::upgrade_database_17();
			FCInitialization::upgrade_database_19();
		}
		else if ($pluginOptions['version'] == "1.0.17")
		{
			FCInitialization::upgrade_database_1019();
			FCInitialization::upgrade_database_11();
			FCInitialization::upgrade_database_13();
			FCInitialization::upgrade_database_14();
			FCInitialization::upgrade_database_15();
			FCInitialization::upgrade_database_16();
			FCInitialization::upgrade_database_17();
			FCInitialization::upgrade_database_19();
		}
		else if ($pluginOptions['version'] == "1.0.18")
		{
			FCInitialization::upgrade_database_1019();
			FCInitialization::upgrade_database_11();
			FCInitialization::upgrade_database_13();
			FCInitialization::upgrade_database_14();
			FCInitialization::upgrade_database_15();
			FCInitialization::upgrade_database_16();
			FCInitialization::upgrade_database_17();
			FCInitialization::upgrade_database_19();
		}
		else if ($pluginOptions['version'] == "1.0.20")
		{
			FCInitialization::upgrade_database_11();
			FCInitialization::upgrade_database_13();
			FCInitialization::upgrade_database_14();
			FCInitialization::upgrade_database_15();
			FCInitialization::upgrade_database_16();
			FCInitialization::upgrade_database_17();
			FCInitialization::upgrade_database_19();
		}
		else if ($pluginOptions['version'] == "1.1")
		{
			FCInitialization::upgrade_database_13();
			FCInitialization::upgrade_database_14();
			FCInitialization::upgrade_database_15();
			FCInitialization::upgrade_database_16();
			FCInitialization::upgrade_database_17();
			FCInitialization::upgrade_database_19();
		}
		else if ($pluginOptions['version'] == "1.2")
		{
			FCInitialization::upgrade_database_13();
			FCInitialization::upgrade_database_14();
			FCInitialization::upgrade_database_15();
			FCInitialization::upgrade_database_16();
			FCInitialization::upgrade_database_17();
			FCInitialization::upgrade_database_19();
		}
		else if ($pluginOptions['version'] == "1.3")
		{
			FCInitialization::upgrade_database_14();
			FCInitialization::upgrade_database_15();
			FCInitialization::upgrade_database_16();
			FCInitialization::upgrade_database_17();
			FCInitialization::upgrade_database_19();
		}
		else if ($pluginOptions['version'] == "1.4")
		{
			FCInitialization::upgrade_database_15();
			FCInitialization::upgrade_database_16();
			FCInitialization::upgrade_database_17();
			FCInitialization::upgrade_database_19();
		}
		else if ($pluginOptions['version'] == "1.5")
		{
			FCInitialization::upgrade_database_16();
			FCInitialization::upgrade_database_17();
			FCInitialization::upgrade_database_19();
		}
		else if ( $pluginOptions['version'] == "1.6" )
		{
			FCInitialization::upgrade_database_17();
			FCInitialization::upgrade_database_19();
		}
		else if ( $pluginOptions['version'] == "1.7" )
		{
			FCInitialization::upgrade_database_19();
		}	
		else if ( $pluginOptions['version'] == "1.8" )
		{
			FCInitialization::upgrade_database_19();
		}		

		// update the version value
		$oasiswf_info=array(
			'version'=>OASISWF_VERSION,
			'db_version'=>OASISWF_DB_VERSION
		);
		update_site_option('oasiswf_info', $oasiswf_info) ;
	}

	/**
	 * Runs on uninstall
	 *
	 * 1. delete site options
	 * 2. deletes dismissed_wp_pointers related to OW
	 */

	static function run_on_uninstall()
	{
		if( !defined( 'ABSPATH') && !defined('WP_UNINSTALL_PLUGIN') )
			exit();

		global $wpdb;	//required global declaration of WP variable

		delete_site_option('oasiswf_info');
		delete_site_option('oasiswf_process');
		delete_site_option('oasiswf_path');
		delete_site_option('oasiswf_status');
		delete_site_option('oasiswf_placeholders');
		delete_site_option('oasiswf_show_upgrade_notice');

		$wpdb->query("DELETE FROM {$wpdb->prefix}options WHERE option_name like 'workflow_%'") ;

		// delete the dismissed_wp_pointers entry for this plugin
		$blog_users = get_users( 'role=administrator' );
		foreach ( $blog_users as $user ) {
			$dismissed = explode( ',', (string) get_user_meta( $user->ID, 'dismissed_wp_pointers', true ) );
			if( ( $key = array_search( "owf_install_free", $dismissed ) ) !== false ) {
				unset( $dismissed[$key] );
			}

			$updated_dismissed = implode( ",", $dismissed );
			update_user_meta( $user->ID, "dismissed_wp_pointers", $updated_dismissed );
		}

	}

	static function delete_for_site( )
	{
	   global $wpdb, $wp_roles;

	   delete_option('oasiswf_activate_workflow');
   	delete_option('oasiswf_default_due_days');
   	delete_option('oasiswf_reminder_days');
   	delete_option('oasiswf_skip_workflow_roles');
   	delete_option('oasiswf_reminder_days_after');
   	delete_option('oasiswf_show_wfsettings_on_post_types');
   	delete_option('oasiswf_email_settings');
   	delete_option('oasiswf_hide_workflow_graphic');
   	delete_option('oasiswf_custom_workflow_terminology');
   	
   	// add edit_other_posts and pages to the author role
   	if ( class_exists('WP_Roles') ) {
   		if ( ! isset( $wp_roles ) ) {
   			$wp_roles = new WP_Roles();
   		}
   	}
   		
   	$wp_roles->remove_cap( 'author', 'edit_others_posts' );
   	$wp_roles->remove_cap( 'author', 'edit_others_pages' );   	

	   // drop tables
		$wpdb->query("DROP TABLE IF EXISTS " . FCUtility::get_emails_table_name());
		$wpdb->query("DROP TABLE IF EXISTS " . FCUtility::get_action_history_table_name());
		$wpdb->query("DROP TABLE IF EXISTS " . FCUtility::get_action_table_name());
		$wpdb->query("DROP TABLE IF EXISTS " . FCUtility::get_workflow_steps_table_name());
		$wpdb->query("DROP TABLE IF EXISTS " . FCUtility::get_workflows_table_name());
	}

	static function run_on_add_blog($blog_id, $user_id, $domain, $path, $site_id, $meta )
	{
	    global $wpdb;
	    if (is_plugin_active_for_network(basename( dirname( __FILE__ )) . '/oasiswf.php'))
	    {
	        $old_blog = $wpdb->blogid;
	        switch_to_blog($blog_id);
	        FCInitialization::run_for_site();
	        restore_current_blog();
	    }
	}

	static function run_on_delete_blog($blog_id, $drop )
	{
		global $wpdb;
      switch_to_blog($blog_id);
		FCInitialization::delete_for_site();
	   restore_current_blog();
	}

   static function add_plugin_row_meta( $input, $file ) {
   	if ( $file != 'oasis-workflow/oasiswf.php' )
   		return $input;

   	$links = array(
   		'<a href="https://www.oasisworkflow.com/pricing-purchase/" target="_blank">' . esc_html__( 'Get the Pro Version', 'oasisworkflow' ) . '</a>'
   	);

   	$input = array_merge( $input, $links );

   	return $input;
   }

	static function upgrade_database_101()
	{
		//rename table for multisite support
		global $wpdb;
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		$table_name = 'fc_workflows';
		$new_table_name = FCUtility::get_workflows_table_name();
		if ($wpdb->get_var( "SHOW TABLES LIKE '{$table_name}'") == $table_name)
		{
			$wpdb->query("RENAME TABLE {$table_name} to  {$new_table_name}");
		}

		$table_name = 'fc_workflow_steps';
		$new_table_name = FCUtility::get_workflow_steps_table_name();
		if ($wpdb->get_var( "SHOW TABLES LIKE '{$table_name}'") == $table_name)
		{
			$wpdb->query("RENAME TABLE {$table_name} to  {$new_table_name}");
		}

		$table_name = 'fc_emails';
		$new_table_name = FCUtility::get_emails_table_name();
		if ($wpdb->get_var( "SHOW TABLES LIKE '{$table_name}'") == $table_name)
		{
			$wpdb->query("RENAME TABLE {$table_name} to  {$new_table_name}");
		}

		$table_name = 'fc_action_history';
		$new_table_name = FCUtility::get_action_history_table_name();
		if ($wpdb->get_var( "SHOW TABLES LIKE '{$table_name}'") == $table_name)
		{
			$wpdb->query("RENAME TABLE {$table_name} to  {$new_table_name}");
		}

		$table_name = 'fc_action';
		$new_table_name = FCUtility::get_action_table_name();
		if ($wpdb->get_var( "SHOW TABLES LIKE '{$table_name}'") == $table_name)
		{
			$wpdb->query("RENAME TABLE {$table_name} to  {$new_table_name}");
		}
	}

	static function upgrade_database_103()
	{
		global $wpdb;
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

		// add reminder_date_after to the fc_action_history table
		$table_name = FCUtility::get_action_history_table_name();
		$wpdb->query("ALTER TABLE {$table_name} ADD COLUMN reminder_date_after date DEFAULT NULL");
	}

	static function upgrade_database_104()
	{
	   $skip_workflow_roles = array("administrator");
	   update_option("oasiswf_skip_workflow_roles", $skip_workflow_roles) ;

	   // modify option name to prefix with oasiswf
	   delete_option('activate_workflow');
	   update_option("oasiswf_activate_workflow", "active") ;
	}

	static function upgrade_database_1012()
	{
	   global $wpdb;

       //fc_workflows table alter
      $table_name = FCUtility::get_workflows_table_name();
      $wpdb->query("ALTER TABLE {$table_name} MODIFY start_date DATE");
      $wpdb->query("ALTER TABLE {$table_name} MODIFY end_date DATE");
      $wpdb->query("ALTER TABLE {$table_name} MODIFY wf_info longtext");

      //fc_workflow_steps table alter
      $table_name = FCUtility::get_workflow_steps_table_name();
      $wpdb->query("ALTER TABLE {$table_name} MODIFY create_datetime datetime");
      $wpdb->query("ALTER TABLE {$table_name} MODIFY update_datetime datetime");
	}

   static function upgrade_database_1015()
   {
      global $wpdb;

      //fc_workflows table alter, add new column "wf_additional_info" at end
      $table_name = FCUtility::get_workflows_table_name();
      $wpdb->query("ALTER TABLE {$table_name} ADD wf_additional_info mediumtext");

      // set default values to new added field
      $additional_info = stripcslashes( 'a:2:{s:16:"wf_for_new_posts";i:1;s:20:"wf_for_revised_posts";i:1;}' );
      $wpdb->query( "UPDATE {$table_name} SET wf_additional_info = '" . $additional_info . "'" );

	   // look through each of the blogs and upgrade the DB
      if (function_exists('is_multisite') && is_multisite())
      {
         //Get all blog ids; foreach them and call the uninstall procedure on each of them
         $blog_ids = $wpdb->get_col("SELECT blog_id FROM {$wpdb->base_prefix}blogs");

         //Get all blog ids; foreach them and call the install procedure on each of them if the plugin table is found
         foreach ( $blog_ids as $blog_id )
         {
            switch_to_blog( $blog_id );
            if( $wpdb->query( "SHOW TABLES FROM ".$wpdb->dbname." LIKE '".$wpdb->prefix."fc_%'" ) )
            {
               FCInitialization::upgrade_helper_1015();
            }
            restore_current_blog();
         }
         return;
      }
      FCInitialization::upgrade_helper_1015();
   }

   static private function upgrade_database_1016()
   {
   	global $wpdb;
   	$table_name = FCUtility::get_workflows_table_name();
   	$wpdb->query("ALTER TABLE {$table_name} CHANGE COLUMN auto_submit_keywords auto_submit_info mediumtext");
   }

   static function upgrade_helper_1015 () {
   	global $wpdb;
	   $action_table = FCUtility::get_action_table_name();
	   $action_history_table = FCUtility::get_action_history_table_name();

	   // get all the records for action_history_id, step_id and actor_id combination
		$multiple_records = $wpdb->get_results("select t1.id AS id,	t1.action_history_id, concat(t1.action_history_id, '-', t1.step_id, '-', t1.actor_id) AS historyid_stepid_actorid, t1.reassign_actor_id AS next_actors FROM {$action_table} AS t1 ORDER BY t1.action_history_id, t1.actor_id");

		// make historyid_stepid_actorid as key and get all the next assigned actors
		$multi_actors =array();
		$affected_action_history = array();
		foreach($multiple_records as $record)
		{
			$multi_actors[][$record->historyid_stepid_actorid] = $record;
		   if (!in_array($record->action_history_id, $affected_action_history))
         {
             $affected_action_history[] = $record->action_history_id;
         }
		}
		$ressigned_actors = array();
		foreach($multi_actors as $actor)
		{
			foreach($actor as $id => $a)
			{
				if(array_key_exists($id, $ressigned_actors))
				{
					$ressigned_actors[$id] .= ','.$a->next_actors;
				}
				else
				{
					$ressigned_actors[$id] = $a->next_actors;
				}
			}
		}
		foreach( $ressigned_actors as $k=>$v )
		{
			$ressigned_actors[$k] = explode(',', $v);
		}

		// we should now have all the next_assign_actors for a given history, step and actor combination
		// now add the new column to the database
		$new_col = $wpdb->query("ALTER TABLE {$action_table} add next_assign_actors text after reassign_actor_id");

		// lets assign the value to this new attribute
		foreach( $ressigned_actors as $k=>$v )
		{
		   $key = explode('-', $k);
		   $history_id = $key[0];
		   $step_id = $key[1];
		   $actor_id = $key[2];

			$result = $wpdb->update(
						$action_table,
						array(
							'next_assign_actors' => json_encode($v)
						),
						array(
						'action_history_id' => $history_id,
						'step_id' => $step_id,
						'actor_id' => $actor_id
						)
					);
		}



		// delete duplicate records
		$remove_dup_records = $wpdb->query("DELETE t1 FROM {$action_table} t1, {$action_table} t2
					WHERE t1.id > t2.id
					AND t1.action_history_id = t2.action_history_id
					AND t1.step_id = t2.step_id
					AND t1.actor_id = t2.actor_id");

		// remove the old column
		$remove_old_col = $wpdb->query("ALTER TABLE {$action_table} DROP reassign_actor_id");

   }

   private static function upgrade_database_1019()
   {
   	$show_wfsettings_on_post_types = array('post', 'page');
   	update_option("oasiswf_show_wfsettings_on_post_types", $show_wfsettings_on_post_types) ;
   }

   private static function upgrade_database_11()
   {
   	$delete_revision_on_copy = "yes";
   	update_option("oasiswf_delete_revision_on_copy", $delete_revision_on_copy) ;

   	$email_settings = array(
   			'from_name' => '',
   			'from_email_address' => '',
   			'assignment_emails' => 'yes',
   			'reminder_emails' => 'no',
   			'post_publish_emails' => 'yes'
   	);
   	update_option("oasiswf_email_settings", $email_settings) ;
   }

   private static function upgrade_database_13()
   {
   	$show_upgrade_notice = "yes";
   	update_site_option("oasiswf_show_upgrade_notice", $show_upgrade_notice) ;
   }

   private static function upgrade_database_14()
   {
   	global $wpdb;

   	// look through each of the blogs and upgrade the DB
   	if (function_exists('is_multisite') && is_multisite())
   	{
   		//Get all blog ids; foreach them and call the uninstall procedure on each of them
   		$blog_ids = $wpdb->get_col("SELECT blog_id FROM {$wpdb->base_prefix}blogs");

   		//Get all blog ids; foreach them and call the install procedure on each of them if the plugin table is found
   		foreach ( $blog_ids as $blog_id )
   		{
   			switch_to_blog( $blog_id );
   			if( $wpdb->query( "SHOW TABLES FROM ".$wpdb->dbname." LIKE '".$wpdb->prefix."fc_%'" ) )
   			{
   				FCInitialization::upgrade_helper_14();
   			}
   			restore_current_blog();
   		}
   		return;
   	}
   	FCInitialization::upgrade_helper_14();
   }

   private static function upgrade_helper_14() {
   	global $wpdb;
   	$action_history_table = FCUtility::get_action_history_table_name();

   	$result = $wpdb->update(
   		$action_history_table,
   		array(
   			'action_status' => 'abort_no_action_1'
   		),
   		array(
   			'action_status' => 'aborted'
   		)
   	);

   	$result = $wpdb->update(
   		$action_history_table,
   		array(
   			'action_status' => 'aborted'
   		),
   		array(
   			'action_status' => 'abort_no_action'
   		)
   	);

   	$result = $wpdb->update(
   		$action_history_table,
   		array(
   			'action_status' => 'abort_no_action'
   		),
   		array(
   			'action_status' => 'abort_no_action_1'
   		)
   	);
   }

   private static function upgrade_database_15()
   {
   	$oasiswf_custom_workflow_terminology = array(
   			'submitToWorkflowText' => __( 'Submit to Workflow', 'oasisworkflow' ),
   			'signOffText' => __( 'Sign Off', 'oasisworkflow' ),
   			'assignActorsText' => __( 'Assign Actor(s)', 'oasisworkflow' ),
   			'dueDateText' => __( 'Due Date', 'oasisworkflow' ),
   			'publishDateText' => __( 'Publish Date', 'oasisworkflow' ),
   			'abortWorkflowText' => __( 'Abort Workflow', 'oasisworkflow' ),
   			'workflowHistoryText' => __( 'Workflow History' )
   	);
   	update_option("oasiswf_custom_workflow_terminology", $oasiswf_custom_workflow_terminology) ;
   }

   private static function upgrade_database_16()
   {
   	global $wpdb;

   	// look through each of the blogs and upgrade the DB
   	if (function_exists('is_multisite') && is_multisite())
   	{
   		//Get all blog ids; foreach them and call the uninstall procedure on each of them
   		$blog_ids = $wpdb->get_col("SELECT blog_id FROM {$wpdb->base_prefix}blogs");

   		//Get all blog ids; foreach them and call the install procedure on each of them if the plugin table is found
   		foreach ( $blog_ids as $blog_id )
   		{
   			switch_to_blog( $blog_id );
   			if( $wpdb->query( "SHOW TABLES FROM ".$wpdb->dbname." LIKE '".$wpdb->prefix."fc_%'" ) )
   			{
   				FCInitialization::upgrade_helper_16();
   			}
   			restore_current_blog();
   		}

   		// delete the site_options since we have added the same to respective site
   		if (get_site_option('oasiswf_activate_workflow')) {
   			delete_site_option('oasiswf_activate_workflow' );
   		}

   		if (get_site_option('oasiswf_default_due_days')) {
   			delete_site_option('oasiswf_default_due_days' );
   		}

   		if (get_site_option('oasiswf_reminder_days')) {
   			delete_site_option('oasiswf_reminder_days' );
   		}

   		if (get_site_option('oasiswf_skip_workflow_roles')) {
   			delete_site_option('oasiswf_skip_workflow_roles' );
   		}

   		if (get_site_option('oasiswf_reminder_days_after')) {
   			delete_site_option('oasiswf_reminder_days_after' );
   		}

   		if (get_site_option('oasiswf_show_wfsettings_on_post_types')) {
   			delete_site_option('oasiswf_show_wfsettings_on_post_types' );
   		}

   		if (get_site_option('oasiswf_email_settings')) {
   			delete_site_option('oasiswf_email_settings' );
   		}

   		if (get_site_option('oasiswf_hide_workflow_graphic')) {
   			delete_site_option('oasiswf_hide_workflow_graphic' );
   		}

   		if (get_site_option('oasiswf_custom_workflow_terminology')) {
   			delete_site_option('oasiswf_custom_workflow_terminology' );
   		}
   		return;
   	}

   }

   private static function upgrade_helper_16()
   {
   	global $wpdb;

   	// add the wp_options to respective sites
   	if (get_site_option('oasiswf_activate_workflow')) {
   		update_option('oasiswf_activate_workflow', get_site_option('oasiswf_activate_workflow'));
   	}

   	if (get_site_option('oasiswf_default_due_days')) {
   		update_option('oasiswf_default_due_days', get_site_option('oasiswf_default_due_days'));
   	}

   	if (get_site_option('oasiswf_reminder_days')) {
   		update_option('oasiswf_reminder_days', get_site_option('oasiswf_reminder_days') );
   	}

   	if (get_site_option('oasiswf_skip_workflow_roles')) {
   		update_option('oasiswf_skip_workflow_roles', get_site_option('oasiswf_skip_workflow_roles'));
   	}

   	if (get_site_option('oasiswf_reminder_days_after')) {
   		update_option('oasiswf_reminder_days_after', get_site_option('oasiswf_reminder_days_after'));
   	}

   	if (get_site_option('oasiswf_show_wfsettings_on_post_types')) {
   		update_option('oasiswf_show_wfsettings_on_post_types', get_site_option('oasiswf_show_wfsettings_on_post_types'));
   	}

   	if (get_site_option('oasiswf_email_settings')) {
   		update_option('oasiswf_email_settings', get_site_option('oasiswf_email_settings'));
   	}

   	if (get_site_option('oasiswf_hide_workflow_graphic')) {
   		update_option('oasiswf_hide_workflow_graphic', get_site_option('oasiswf_hide_workflow_graphic'));
   	}

   	if (get_site_option('oasiswf_custom_workflow_terminology')) {
   		update_option('oasiswf_custom_workflow_terminology', get_site_option('oasiswf_custom_workflow_terminology'));
   	}

   	// create tables and data only if the workflow tables do not exist
   	if( $wpdb->query( "SHOW TABLES FROM ".$wpdb->dbname." LIKE '".$wpdb->prefix."fc_workflow%'" ) ) {
   		return;
   	}

		// first create the tables and put the default data
   	FCInitialization::install_admin_database();

   	// delete the default data
   	//fc_workflow_steps table
   	$wpdb->query( "DELETE FROM " . FCUtility::get_workflow_steps_table_name() );

   	//fc_workflows table
   	$wpdb->get_results( "DELETE FROM " . FCUtility::get_workflows_table_name() );

   	// now insert data from the original/main table into these new tables
   	$sql = "INSERT INTO " . FCUtility::get_workflows_table_name() . " SELECT * FROM " . $wpdb->base_prefix . "fc_workflows";
   	$wpdb->get_results( $sql );

   	$sql = "INSERT INTO " . FCUtility::get_workflow_steps_table_name() . " SELECT * FROM " . $wpdb->base_prefix . "fc_workflow_steps";
   	$wpdb->get_results( $sql );
   }

   private static function upgrade_database_17() {
   	// update the dismissed pointer/message for existing plugin users.
   	$blog_users = get_users( 'role=administrator' );
   	foreach ( $blog_users as $user ) {
   		$dismissed = (string) get_user_meta( $user->ID, 'dismissed_wp_pointers', true );
   		$dismissed = $dismissed . "," . "owf_install_free";
   		update_user_meta( $user->ID, "dismissed_wp_pointers", $dismissed );
   	}
   }
   
   /**
    * Upgrade function for upgrading to v1.9
    * Calls upgrade_helper_19()
    *
    * @since 3.5
    */
   static function upgrade_database_19() {
   	global $wpdb;
   
   	// look through each of the blogs and upgrade the DB
   	if (function_exists('is_multisite') && is_multisite())
   	{
   		//Get all blog ids; foreach them and call the uninstall procedure on each of them
   		$blog_ids = $wpdb->get_col("SELECT blog_id FROM {$wpdb->base_prefix}blogs");
   
   		//Get all blog ids; foreach them and call the install procedure on each of them if the plugin table is found
   		foreach ( $blog_ids as $blog_id )
   		{
   			switch_to_blog( $blog_id );
   			if( $wpdb->query( "SHOW TABLES FROM ".$wpdb->dbname." LIKE '".$wpdb->prefix."fc_%'" ) )
   			{
   				FCInitialization::upgrade_helper_19();
   			}
   			restore_current_blog();
   		}
   	}
   
   	FCInitialization::upgrade_helper_19();
   }
   
   /**
    * Upgrade Helper for v1.9
    *
    * Add new capabilities to author role
    *
    * @since 1.9
    */
  static private function upgrade_helper_19() {
   	global $wp_roles;
   
   	if ( class_exists('WP_Roles') ) {
   		if ( ! isset( $wp_roles ) ) {
   			$wp_roles = new WP_Roles();
   		}
   	}
   
   	$wp_roles->add_cap( 'author', 'edit_others_posts' );
   	$wp_roles->add_cap( 'author', 'edit_others_pages' );
   }
    
    

	static function install_admin_database()
	{
		global $wpdb;
		if (!empty ($wpdb->charset))
        	$charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
		if (!empty ($wpdb->collate))
        	$charset_collate .= " COLLATE {$wpdb->collate}";
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        //fc_workflows table
		$table_name = FCUtility::get_workflows_table_name();
		if ($wpdb->get_var( "SHOW TABLES LIKE '{$table_name}'") != $table_name)
		{
			$sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
			      ID int(11) NOT NULL AUTO_INCREMENT,
			      name varchar(200) NOT NULL,
			      description mediumtext,
			      wf_info longtext,
			      version int(3) NOT NULL default 1,
			      parent_id int(11) NOT NULL default 0,
			      start_date date DEFAULT NULL,
			      end_date date DEFAULT NULL,
			      is_auto_submit int(2) NOT NULL default 0,
			      auto_submit_info mediumtext,
			      is_valid int(2) NOT NULL default 0,
			      create_datetime datetime DEFAULT NULL,
			      update_datetime datetime DEFAULT NULL,
			      wf_additional_info mediumtext DEFAULT NULL,
			      PRIMARY KEY (ID)
	    		){$charset_collate};";
			dbDelta($sql);
		}
        //fc_workflow_steps table
		$table_name = FCUtility::get_workflow_steps_table_name();
		if ($wpdb->get_var( "SHOW TABLES LIKE '{$table_name}'") != $table_name)
		{
			$sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
			      ID int(11) NOT NULL AUTO_INCREMENT,
			      step_info text NOT NULL,
			      process_info longtext NOT NULL,
			      workflow_id int(11) NOT NULL,
			      create_datetime datetime DEFAULT NULL,
			      update_datetime datetime DEFAULT NULL,
			      PRIMARY KEY (ID),
			      KEY workflow_id (workflow_id)
	    		){$charset_collate};";
			dbDelta($sql);
		}

		FCInitialization::install_admin_data();
	}

	static function install_site_database()
	{
		global $wpdb;
		if (!empty ($wpdb->charset))
        	$charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
		if (!empty ($wpdb->collate))
        	$charset_collate .= " COLLATE {$wpdb->collate}";
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        //fc_emails table
		$table_name = FCUtility::get_emails_table_name();
		if ($wpdb->get_var( "SHOW TABLES LIKE '{$table_name}'") != $table_name)
		{
		   // action - 1 indicates not send, 0 indicates email sent
			$sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
			    ID int(11) NOT NULL AUTO_INCREMENT,
			    subject mediumtext,
			    message mediumtext,
			    from_user int(11),
			    to_user int(11),
			    action int(2) DEFAULT 1,
			    history_id int(11),
			    send_date date DEFAULT NULL,
			    create_datetime datetime DEFAULT NULL,
			    PRIMARY KEY (ID)
	    		){$charset_collate};";
			dbDelta($sql);
		}
        //fc_action_history table
		$table_name = FCUtility::get_action_history_table_name();
		if ($wpdb->get_var( "SHOW TABLES LIKE '{$table_name}'") != $table_name)
		{
			$sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
			    ID int(11) NOT NULL AUTO_INCREMENT,
			    action_status varchar(20) NOT NULL,
			    comment longtext DEFAULT NULL,
			    step_id int(11) NOT NULL,
			    assign_actor_id int(11) NOT NULL,
			    post_id int(11) NOT NULL,
			    from_id int(11) NOT NULL,
			    due_date date DEFAULT NULL,
			    reminder_date date DEFAULT NULL,
			    reminder_date_after date DEFAULT NULL,
			    create_datetime datetime NOT NULL,
			    PRIMARY KEY (ID)
	    		){$charset_collate};";
			dbDelta($sql);
		}
        //fc_action table
		$table_name = FCUtility::get_action_table_name();
		if ($wpdb->get_var( "SHOW TABLES LIKE '{$table_name}'") != $table_name)
		{
			$sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
			    ID int(11) NOT NULL AUTO_INCREMENT,
			    review_status varchar(20) NOT NULL,
			    actor_id int(11) NOT NULL,
			    next_assign_actors text NOT NULL,
			    step_id int(11) NOT NULL,
			    comments mediumtext,
			    due_date date DEFAULT NULL,
			    action_history_id int(11) NOT NULL,
			    update_datetime datetime NOT NULL,
			    PRIMARY KEY (ID)
	    		){$charset_collate};";
			dbDelta($sql);

		}

   }

	static function install_admin_data()
	{
	    global $wpdb;

	    // insert into workflow table
	    $table_name = FCUtility::get_workflows_table_name();
	    $row = $wpdb->get_row("SELECT max(ID) as maxid FROM $table_name");
	    if ( is_numeric( $row->maxid )) { //data already exists, do not insert another row.
	    	return;
	    }
	    $workflow_id = '';
       $workflow_info = stripcslashes('{"steps":{"step0":{"fc_addid":"step0","fc_label":"assignment","fc_dbid":"2","fc_process":"assignment","fc_position":["326px","568px"]},"step1":{"fc_addid":"step1","fc_label":"review","fc_dbid":"1","fc_process":"review","fc_position":["250px","358px"]},"step2":{"fc_addid":"step2","fc_label":"publish","fc_dbid":"3","fc_process":"publish","fc_position":["119px","622px"]}},"conns":{"0":{"sourceId":"step1","targetId":"step2","connset":{"connector":"StateMachine","paintStyle":{"lineWidth":3,"strokeStyle":"blue"}}},"1":{"sourceId":"step2","targetId":"step1","connset":{"connector":"StateMachine","paintStyle":{"lineWidth":3,"strokeStyle":"red"}}},"2":{"sourceId":"step1","targetId":"step0","connset":{"connector":"StateMachine","paintStyle":{"lineWidth":3,"strokeStyle":"red"}}},"3":{"sourceId":"step2","targetId":"step0","connset":{"connector":"StateMachine","paintStyle":{"lineWidth":3,"strokeStyle":"red"}}},"4":{"sourceId":"step0","targetId":"step1","connset":{"connector":"StateMachine","paintStyle":{"lineWidth":3,"strokeStyle":"blue"}}}},"first_step":["step1"]}');
       $additional_info = stripcslashes( 'a:2:{s:16:"wf_for_new_posts";i:1;s:20:"wf_for_revised_posts";i:1;}' );
		 $data = array(
					'name' => 'Sample Workflow',
					'description' => 'sample workflow',
					'wf_info' => $workflow_info,
					'start_date' => date("Y-m-d", current_time('timestamp')),
					'end_date' => date("Y-m-d", current_time('timestamp') + YEAR_IN_SECONDS),
					'is_valid' => 1,
					'create_datetime' => current_time('mysql'),
		 			'update_datetime' => current_time('mysql'),
		 		   'wf_additional_info' => $additional_info
				);
		 $result = $wpdb->insert($table_name, $data);
		 if( $result ){

			$row = $wpdb->get_row("SELECT max(ID) as maxid FROM $table_name");
			if($row)
				$workflow_id = $row->maxid ;
			else
				return false;
		 }else{
			return false;
		 }

		 // insert steps
		 $workflow_step_table = FCUtility::get_workflow_steps_table_name();

	    // step 1 - review
	    $review_step_info = '{"process":"review","step_name":"review","assignee":{"editor":"Editor"},"status":"pending","failure_status":"draft"}';
	    $review_process_info = '{"assign_subject":"","assign_content":"","reminder_subject":"","reminder_content":""}';
		 $result = $wpdb->insert(
					  $workflow_step_table,
					  array(
						 'step_info' => stripcslashes( $review_step_info ),
						 'process_info' => stripcslashes( $review_process_info ),
						 'create_datetime' => current_time('mysql'),
						 'update_datetime' => current_time('mysql'),
						 'workflow_id' => $workflow_id
					 )
			   );

	    // step 2 - assignment
	    $assignment_step_info = '{"process":"assignment","step_name":"assignment","assignee":{"author":"Author"},"status":"pending","failure_status":"draft"}';
	    $assignment_process_info = '{"assign_subject":"","assign_content":"","reminder_subject":"","reminder_content":""}';
		 $result = $wpdb->insert(
					  $workflow_step_table,
					  array(
						 'step_info' => stripcslashes( $assignment_step_info ),
						 'process_info' => stripcslashes( $assignment_process_info ),
						 'create_datetime' => current_time('mysql'),
					     'update_datetime' => current_time('mysql'),
						 'workflow_id' => $workflow_id
					 )
			   );

	    // step 3 - publish
	    $publish_step_info = '{"process":"publish","step_name":"publish","assignee":{"administrator":"Administrator"},"status":"publish","failure_status":"draft"}';
	    $publish_process_info = '{"assign_subject":"","assign_content":"","reminder_subject":"","reminder_content":""}';
		 $result = $wpdb->insert(
					  $workflow_step_table,
					  array(
						 'step_info' => stripcslashes( $publish_step_info ),
						 'process_info' => stripcslashes( $publish_process_info ),
						 'create_datetime' => current_time('mysql'),
					     'update_datetime' => current_time('mysql'),
						 'workflow_id' => $workflow_id
					 )
			   );
	}

	static function run_on_deactivation()
	{
		/*
		 * Mail schedule remove
		 */
		wp_clear_scheduled_hook( 'oasiswf_email_schedule' );
	}

   /**
	 * Show the pointers/messages for the current screen.
	 *
	 * Gets all the valid and non-dismissed pointers to display on the current
	 * screen.
	 *
	 * @since 3.2
	 */
	public static function show_welcome_message_pointers() {
		// Don't run on WP < 3.3
		if( get_bloginfo( 'version' ) < '3.3' ){
			return;
		}

		// only show this message to the users who can activate plugins
		if ( !current_user_can( 'activate_plugins' ) ){
			return;
		}

		$pointers = self::get_current_screen_pointers();

		// No pointers? Don't do anything
		if( empty( $pointers ) || ! is_array( $pointers ) )
			return;

		// Get dismissed pointers.
		// Note : dismissed pointers are stored by WP in the
		// "dismissed_wp_pointers" user meta.

		$dismissed = explode( ',', (string) get_user_meta( get_current_user_id(),
				'dismissed_wp_pointers', true ) );
		$valid_pointers = array();

		// Check pointers and remove dismissed ones.
		foreach( $pointers as $pointer_id => $pointer ) {
			// Sanity check
			if( in_array( $pointer_id, $dismissed ) || empty( $pointer )  || empty( $pointer_id ) || empty( $pointer['target'] ) || empty( $pointer['content'] ) )
				continue;

			// Add the pointer to $valid_pointers array
			$valid_pointers[$pointer_id] =  $pointer;
		}

		// No valid pointers? Stop here.
		if( empty( $valid_pointers ) )
			return;

		// Set our class variable $current_screen_pointers
		self::$current_screen_pointers = $valid_pointers;

		// Add our javascript to handle pointers
		add_action( 'admin_print_footer_scripts', array( __CLASS__, 'display_pointers' ) );

		// Add pointers style and javascript to queue.
		wp_enqueue_style( 'wp-pointer' );
		wp_enqueue_script( 'wp-pointer' );
	}

	/**
	 * Prints the javascript that'll make our pointers alive.
	 *
	 * @since 1.7 initial version
	 */
	public static function display_pointers(){
		if( !empty( self::$current_screen_pointers ) ):
		?>
	            <script type="text/javascript">// <![CDATA[
	                jQuery(document).ready(function($) {
	                    if(typeof(jQuery().pointer) != 'undefined') {
	                        <?php foreach( self::$current_screen_pointers as $pointer_id => $data): ?>
	                            $('<?php echo $data['target'] ?>').pointer({
	                                content: '<?php echo addslashes( $data['content'] ) ?>',
	                                position: {
	                                    edge: '<?php echo addslashes( $data['position']['edge'] ) ?>',
	                                    align: '<?php echo addslashes( $data['position']['align'] ) ?>'
	                                },
	                                close: function() {
	                                    $.post( ajaxurl, {
	                                        pointer: '<?php echo addslashes( $pointer_id ) ?>',
	                                        action: 'dismiss-wp-pointer'
	                                    });
	                                }
	                            }).pointer('open');
	                        <?php endforeach; ?>
	                    }
	                });
	            // ]]></script>
	            <?php
	        endif;
	    }

	/**
	 * Retrieves pointers for the current admin screen.
	 *
	 * Shows the welcome message after plugin install.
	 * Use the 'owf_admin_pointers' hook to add your own pointers.
	 *
	 * @return array Current screen pointers
	 * @since 1.7 initial version
	 */
	private static function get_current_screen_pointers(){
		$pointers = '';

		$screen = get_current_screen();
		$screen_id = $screen->id;

		// Format : array( 'screen_id' => array( 'pointer_id' => array([options : target, content, position...]) ) );

		$welcome_title = __( "Welcome to Oasis Workflow", "oasisworkflow" );
		$img_html = "<img src='" . OASISWF_URL . "img/small-arrow.gif" . "' style='border:0px;' />";
		$welcome_message_1 = __( "To get started with Oasis Workflow follow the steps listed below.", "oasisworkflow" );
		$welcome_message_1_multisite = __( "To get started with Oasis Workflow go to the individual site and follow the steps listed below.", "oasisworkflow" );
		$welcome_message_2 = sprintf( __( "1. Go to Workflows %s Edit Workflows.", "oasisworkflow" ), $img_html );
		$welcome_message_3 = __( "2. Modify the \"Sample Workflow\" to suit your needs.", "oasisworkflow" );
		$welcome_message_4 = sprintf( __( "3. Activate the workflow process from Workflows %s Settings, Workflow tab.", "oasisworkflow" ), $img_html );

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {
			$default_pointers = array(
				'plugins' => array(
					'owf_install_free' => array(
						'target' => '#toplevel_page_oasiswf-inbox',
						'content' => '<h3>'. $welcome_title .'</h3> <p>'.
						$welcome_message_1_multisite .'</p><p>'.
						$welcome_message_2 .'</p><p>'.
						$welcome_message_3 .'</p><p>'.
						$welcome_message_4 .'</p>',
						'position' => array( 'edge' => 'left', 'align' => 'center' ),
					)
				)
			);
		} else {
			$default_pointers = array(
					'plugins' => array(
							'owf_install_free' => array(
									'target' => '#toplevel_page_oasiswf-inbox',
									'content' => '<h3>'. $welcome_title .'</h3> <p>'.
									$welcome_message_1 .'</p><p>'.
									$welcome_message_2 .'</p><p>'.
									$welcome_message_3 .'</p><p>'.
									$welcome_message_4 .'</p>',
									'position' => array( 'edge' => 'left', 'align' => 'center' ),
							)
					)
			);
		}

		if( !empty( $default_pointers[$screen_id] ) )
			$pointers = $default_pointers[$screen_id];

		return apply_filters( 'owf_admin_pointers', $pointers, $screen_id );
	}

}

$initialization=new FCInitialization();
/**
 *
 * workflow create / edit
 *
 */
class FCLoadWorkflow
{
	function __construct()
	{
		//init: Runs after WordPress has finished loading but before any headers are send. It is used before sending data send to browser.
		add_action('init', array('FCLoadWorkflow', 'page_load_control'));
		add_action('admin_menu',  array('FCLoadWorkflow', 'register_menu_pages'));
	}

	static function page_load_control()
	{
	   FCInitialization::run_on_upgrade();
		require_once( OASISWF_PATH . "includes/workflow-base.php" ) ;
	}

	static function load_step_info()
	{
      require_once( OASISWF_PATH . "includes/pages/subpages/step-info-content.php" );
	}

	static function register_menu_pages()
	{
		$current_role = FCWorkflowBase::get_current_user_role() ;
		$position = FCWorkflowBase::get_menu_position(".6") ;

		$inbox_count = FCWorkflowBase::get_count_assigned_post() ;
		$count = ($inbox_count) ? '<span class="update-plugins count"><span class="plugin-count">' . $inbox_count . '</span></span>' : '' ;

		add_menu_page(__('Workflows', 'oasisworkflow'),
						__( 'Workflows', 'oasisworkflow' ) . $count,
						$current_role,
						'oasiswf-inbox',
						array('FCLoadWorkflow','workflow_inbox'),'', $position);

		add_submenu_page('oasiswf-inbox',
	    				__('Inbox', 'oasisworkflow'),
	    				__( 'Inbox', 'oasisworkflow' ) . $count,
	    				$current_role,
	    				'oasiswf-inbox',
	    				array('FCLoadWorkflow','workflow_inbox'));

		$workflow_terminology_options = get_option( 'oasiswf_custom_workflow_terminology' );
		$workflow_history_label = !empty( $workflow_terminology_options['workflowHistoryText'] ) ? $workflow_terminology_options['workflowHistoryText'] : __( 'Workflow History' );

		add_submenu_page('oasiswf-inbox',
						$workflow_history_label,
						$workflow_history_label,
						$current_role,
						'oasiswf-history',
						array('FCLoadWorkflow','workflow_history'));

		add_submenu_page( 'oasiswf-inbox',
				__( 'Reports', 'oasisworkflow' ),
				__( 'Reports', 'oasisworkflow' ),
				$current_role,
				'oasiswf-reports',
				array( 'FCLoadWorkflow', 'workflow_reports_page_content' ) );


      // Workflow Admin menu subpages
	    add_submenu_page('oasiswf-inbox',
	    				__('Edit Workflows', 'oasisworkflow'),
	    				__('Edit Workflows', 'oasisworkflow'),
	    				'edit_theme_options',
	    				'oasiswf-admin',
	    				array('FCLoadWorkflow','list_workflows'));

	    add_submenu_page('oasiswf-inbox',
	    				__('Settings', 'oasisworkflow'),
	    				__('Settings', 'oasisworkflow'),
	    				'edit_theme_options',
	    				'oasiswf-setting',
	    				array('FCLoadWorkflow','workflow_settings'));

	   add_action('admin_print_styles', array('FCLoadWorkflow', 'add_css_files'));
		add_action('admin_print_scripts', array('FCLoadWorkflow', 'add_js_files'));
		add_action('admin_footer', array('FCLoadWorkflow', 'load_js_files_footer'));
	}

	static function create_workflow()
	{
		include( OASISWF_PATH . "includes/pages/subpages/workflow-create-message.php" ) ;
	}

	static function edit_workflow()
	{
		$create_workflow = new FCWorkflowCRUD() ;
		include( OASISWF_PATH . "includes/pages/workflow-create.php" ) ;
	}

	static function list_workflows()
	{
		if(isset($_GET['wf_id']) && $_GET["wf_id"]){
			FCLoadWorkflow::edit_workflow() ;
		}else{
			$list_workflow = new FCWorkflowList() ;
			include( OASISWF_PATH . "includes/pages/workflow-list.php" ) ;
		}
	}

	static function workflow_inbox()
	{
		$inbox_workflow = new FCWorkflowInbox() ;
		include( OASISWF_PATH . "includes/pages/workflow-inbox.php" ) ;
	}

	static function workflow_history()
	{
		$history_workflow = new FCWorkflowHistory() ;
		include( OASISWF_PATH . "includes/pages/workflow-history.php" ) ;
		include( OASISWF_PATH . "includes/pages/subpages/delete-history.php" ) ;
	}

   /*
	 * Reports page.
	 * This method is called when "Reports" is clicked.
	 */
	static function workflow_reports_page_content() {
		include( OASISWF_PATH . "includes/pages/workflow-reports.php" );
	}

   static function workflow_settings()
	{
		include( OASISWF_PATH . "includes/pages/settings.php" ) ;
	}

	static function add_css_files($page)
	{
	   // ONLY load OWF scripts on OWF plugin pages
	   if ( is_admin() && preg_match_all('/page=oasiswf(.*)|post-new\.(.*)|post\.(.*)/', $_SERVER['REQUEST_URI'], $matches ) ) {
   	   wp_enqueue_style( 'owf-css',
      	                   OASISWF_URL. 'css/pages/context-menu.css',
      	                   false,
      	                   OASISWF_VERSION,
                            'all');

   	   wp_enqueue_style( 'owf-modal-css',
      	                   OASISWF_URL. 'css/lib/modal/simple-modal.css',
      	                   false,
      	                   OASISWF_VERSION,
                            'all');

   	   wp_enqueue_style( 'owf-calendar-css',
      	                   OASISWF_URL. 'css/lib/calendar/datepicker.css',
      	                   false,
      	                   OASISWF_VERSION,
                            'all');

   	   wp_enqueue_style( 'owf-oasis-workflow-css',
      	                   OASISWF_URL. 'css/pages/oasis-workflow.css',
      	                   false,
      	                   OASISWF_VERSION,
                            'all');
	   }
	}

	static function add_js_files()
	{
	   // ONLY load OWF scripts on OWF plugin pages
	   if ( is_admin() && preg_match_all('/page=oasiswf(.*)|post-new\.(.*)|post\.(.*)/', $_SERVER['REQUEST_URI'], $matches ) ) {
   		echo "<script type='text/javascript'>
   					var wf_structure_data = '' ;
   					var wfeditable = '' ;
   					var wfPluginUrl  = '" . OASISWF_URL . "' ;
   				</script>";
	   }

		if( is_admin() && isset($_GET['page']) && ($_GET["page"] == "oasiswf-inbox" ||
		      $_GET["page"] == "oasiswf-history" ))
		{
			wp_enqueue_script( 'owf-workflow-inbox',
					OASISWF_URL. 'js/pages/workflow-inbox.js',
			      array('jquery'),
			      OASISWF_VERSION);

			wp_enqueue_script( 'owf-workflow-history',
					OASISWF_URL. 'js/pages/workflow-history.js',
					array('jquery'),
					OASISWF_VERSION);

			wp_localize_script( 'owf-workflow-inbox', 'owf_workflow_inbox_vars', array(
				'dateFormat' => FCUtility::owf_date_format_to_jquery_ui_format( get_option( 'date_format' )),
				'editDateFormat' => FCUtility::owf_date_format_to_jquery_ui_format( OASISWF_EDIT_DATE_FORMAT ),
				'abortWorkflowConfirm' => __( 'Are you sure to abort the workflow?', 'oasisworkflow' )
			));

		}
	}

	static function load_js_files_footer()
	{
	   // ONLY load OWF scripts on OWF plugin pages
	   if ( is_admin() && preg_match_all('/page=oasiswf(.*)|post-new\.(.*)|post\.(.*)/', $_SERVER['REQUEST_URI'], $matches ) ) {
   		wp_enqueue_script( 'jquery-ui-core' ) ;
   		wp_enqueue_script( 'jquery-ui-widget' ) ;
   		wp_enqueue_script( 'jquery-ui-mouse' ) ;
   		wp_enqueue_script( 'jquery-ui-sortable' ) ;
   		wp_enqueue_script( 'jquery-ui-datepicker' ) ;
   		wp_enqueue_script( 'jquery-json',
   		                   OASISWF_URL. 'js/lib/jquery.json.js',
   		                   '',
   		                   '2.3',
   		                   true);

   		wp_enqueue_script( 'jquery-ui-draggable' ) ;
   		wp_enqueue_script( 'jquery-ui-droppable' ) ;
	   }

		if(is_admin() && ( isset($_GET['page']) && ($_GET["page"] == "oasiswf-admin"  || $_GET["page"] == "oasiswf-add")) ||
		   (isset($_GET['oasiswf']) && $_GET["oasiswf"] ))
		{
   		wp_enqueue_script( 'jsPlumb',
   		                   OASISWF_URL. 'js/lib/jquery.jsPlumb-all-min.js',
   		                   array('jquery-ui-core', 'jquery-ui-draggable', 'jquery-ui-droppable'),
   		                   '1.4.1',
   		                   true);
   		wp_enqueue_script( 'drag-drop-jsplumb',
   		                   OASISWF_URL. 'js/pages/drag-drop-jsplumb.js',
   		                   array('jsPlumb'),
   		                   OASISWF_VERSION,
   		                   true ) ;
         wp_localize_script( 'drag-drop-jsplumb', 'drag_drop_jsplumb_vars', array(
   						'clearAllSteps' => __( 'Do you really want to clear all the steps?', 'oasisworkflow' ),
         				'removeStep' => __( 'This step is already defined.Do you really want to remove this step?', 'oasisworkflow' ),
         				'pathBetween' => __( 'The path between', 'oasisworkflow' ),
         				'stepAnd' => __( 'step and', 'oasisworkflow' ),
         				'incorrect' => __( 'step is incorrect.', 'oasisworkflow' ),
         		      'stepHelp' => __( 'To edit/delete the step, right click on the step to access the step menu.', 'oasisworkflow' ),
         				'connectionHelp' => __( 'To connect to another step drag a line from the "dot" to the next step.', 'oasisworkflow' )
                 ));
		}

		if ( is_admin() && preg_match_all('/page=oasiswf(.*)|post-new\.(.*)|post\.(.*)/', $_SERVER['REQUEST_URI'], $matches ) ) {

   		wp_enqueue_script( 'owf-workflow-create',
   		                   OASISWF_URL. 'js/pages/workflow-create.js',
   		                   '',
   		                   OASISWF_VERSION,
   		                   true);
         wp_localize_script( 'owf-workflow-create', 'owf_workflow_create_vars', array(
   						'alreadyExistWorkflow' => __( 'There is an existing workflow with the same name. Please choose another name.', 'oasisworkflow' ),
         				'unsavedChanges' => __( 'You have unsaved changes.', 'oasisworkflow' ),
         				'dateFormat' => FCUtility::owf_date_format_to_jquery_ui_format( get_option( 'date_format' )),
         				'editDateFormat' => FCUtility::owf_date_format_to_jquery_ui_format( OASISWF_EDIT_DATE_FORMAT )
                 ));

   	   wp_enqueue_script( 'jquery-simplemodal',
   		                   OASISWF_URL. 'js/lib/modal/jquery.simplemodal.js',
   		                   '',
   		                   '1.4.5',
   		                   true);

   		wp_enqueue_script( 'owf-workflow-util',
   		                   OASISWF_URL. 'js/pages/workflow-util.js',
   		                   '',
   		                   OASISWF_VERSION,
   		                   true);
         wp_localize_script( 'owf-workflow-util', 'owf_workflow_util_vars', array(
   						'dueDateInPast' => __( 'Due date cannot be in the past.', 'oasisworkflow' )
                ));

         wp_enqueue_script( 'text-edit-whizzywig',
                         OASISWF_URL. 'js/lib/textedit/whizzywig63.js',
                         '',
                         '63',
                         true);

         wp_enqueue_script( 'owf-workflow-step-info',
                         OASISWF_URL. 'js/pages/subpages/step-info.js',
                         array('text-edit-whizzywig'),
                         OASISWF_VERSION,
                         true);
         wp_localize_script( 'owf-workflow-step-info', 'owf_workflow_step_info_vars', array(
   						'stepNameRequired' => __( 'Step name is required.', 'oasisworkflow' ),
         				'stepNameAlreadyExists' => __( 'Step name already exists. Please use a different name.', 'oasisworkflow' ),
         				'selectAssignees' => __( 'Please select assignee(s).', 'oasisworkflow' ),
                     'statusOnSuccess' => __( 'Please select status on success.', 'oasisworkflow' ),
                     'statusOnFailure' => __( 'Please select status on failure.', 'oasisworkflow' ),
         				'selectPlaceholder' => __('Please select a placeholder.', 'oasisworkflow' )
               ));
		}

	}
}

/* plugin activation whenenver a new blog is created */
add_action( 'wpmu_new_blog', array( 'FCInitialization', 'run_on_add_blog' ), 10, 6);
add_action( 'delete_blog', array( 'FCInitialization', 'run_on_delete_blog' ), 10, 2);
add_action( 'admin_init', array( 'FCInitialization', 'run_on_upgrade' ));
add_filter( 'plugin_row_meta', array( 'FCInitialization', 'add_plugin_row_meta' ), 10, 2 );

include( OASISWF_PATH . "oasiswf-utilities.php" ) ;
$fcLoadWorkflow = new FCLoadWorkflow();

include( OASISWF_PATH . "oasiswf-actions.php" ) ;
$fcWorkflowActions = new FCWorkflowActions();

/* ajax */
add_action('wp_ajax_create_new_workflow', array( 'FCWorkflowCRUD', 'create_new_workflow' ) );
add_action('wp_ajax_get_workflow_count', array( 'FCWorkflowCRUD', 'get_workflow_count' ) );
add_action('wp_ajax_step_save', array( 'FCWorkflowCRUD', 'workflow_step_save' ) );
add_action('wp_ajax_get_editinline_html', array( 'FCWorkflowInbox', 'get_editinline_html' ) );
add_action('wp_ajax_get_step_signoff_content', array( 'FCWorkflowInbox', 'get_step_signoff_content' ) );
add_action('wp_ajax_get_reassign_content', array( 'FCWorkflowInbox', 'get_reassign_content' ) );
add_action('wp_ajax_claim_process', array( 'FCWorkflowInbox', 'claim_process' ) );
add_action('wp_ajax_reset_assign_actor', array( 'FCWorkflowInbox', 'reset_assign_actor' ) );
add_action('wp_ajax_get_step_comment', array( 'FCWorkflowInbox', 'get_step_comment' ) );
add_action('wp_ajax_load_step_info', array( 'FCLoadWorkflow', 'load_step_info' ) );
add_action('wp_ajax_purge_workflow_history', array('FCWorkflowHistory', 'purge_history') );
add_action('wp_ajax_check_claim_ajax', array( 'FCWorkflowInbox', 'check_claim_ajax' ) );

/* workflow action hooks
 add_action('owf_submit_to_workflow', array( 'FCWorkflowActions', 'owf_submit_to_workflow_hook_test' ), 10, 2);
 add_action('owf_step_sign_off', array( 'FCWorkflowActions', 'owf_step_sign_off_hook_test' ), 10, 4);
 add_action('owf_workflow_complete', array( 'FCWorkflowActions', 'owf_workflow_complete_hook_test' ), 10, 2);
 */

?>