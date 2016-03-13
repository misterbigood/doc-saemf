<?php
/**
 * Plugin Name: Simple Flow
 * Plugin URI: www.marquedefabrique.net/simple-flow
 * Description: Lets you assign each user to a group; 
 * Version: 0.9
 * Author: Christophe VIOLEAU
 * Author URI: www.marquedefabrique.net
 * License: http://creativecommons.org/licenses/by-nc-sa/3.0/fr/
 */
define( 'SIMPLE_FLOW_VERSION' , '0.9' );
define( 'SIMPLE_FLOW_INC' , dirname(__FILE__).'/inc' );
define( 'SIMPLE_FLOW_JAVASCRIPT' , dirname(__FILE__).'/js' );

/*function simple_flow_activation() {
}
register_activation_hook(__FILE__, 'simple_flow_activation');

function simple_flow_deactivation() {
}
register_deactivation_hook(__FILE__, 'simple_flow_deactivation');*/

add_action('admin_menu', 'simple_flow_plugin_settings');
 
function simple_flow_plugin_settings() {
 
    add_menu_page('Simple Flow Settings', 'Simple Flow Settings', 'administrator', 'simple_flow_groups', 'simple_flow_display_groups');
    //add_submenu_page('simple_flow_settings', 'Settings', 'Settings', 'administrator', 'simple_flow_settings', 'simple_flow_display_settings' );
    add_submenu_page('simple_flow_settings', 'Groups', 'Groups', 'administrator', 'simple_flow_groups', 'simple_flow_display_groups' );
}

function simple_flow_display_groups() {
    if (isset($_POST["update_settings"])) {
        $groups_elements = array();
        $max_id = esc_attr($_POST["element-max-id"]);
        for ($i = 0; $i < $max_id; $i ++) {
            $group_name = "group-id-" . $i;
            $contact_name = "contact-id-" . $i;
            if (isset($_POST[$group_name]) && isset($_POST[$contact_name])) {
                $groups_elements[] = array('id'=>$i, 'group'=>esc_attr($_POST[$group_name]), 'contact'=>esc_attr($_POST[$contact_name]));
            }
        }
        update_option("simple_flow_groups", $groups_elements);
        ?>
            <div id="message" class="updated">Settings saved</div>
        <?php
    }    

    $groups_elements = get_option("simple_flow_groups");
    $args1 = array(
        'role' => 'editor',
        'orderby' => 'user_nicename',
        'order' => 'ASC'
        );
    $args2 = array(
        'role' => 'administrator',
        'orderby' => 'user_nicename',
        'order' => 'ASC'
        );
    $users_elements = array_merge(get_users($args1), get_users($args2));
    require_once( SIMPLE_FLOW_JAVASCRIPT . '/group-page.js' );
    require_once( SIMPLE_FLOW_INC . '/group-page.php' );
}


/*
function simple_flow_display_modele() {
        
    if (isset($_POST["update_settings"])) {
        $num_elements = esc_attr($_POST["num_elements"]);   
        update_option("theme_name_num_elements", $num_elements);
        
        $front_page_elements = array();
        $max_id = esc_attr($_POST["element-max-id"]);
        for ($i = 0; $i < $max_id; $i ++) {
            $field_name = "element-page-id-" . $i;
            if (isset($_POST[$field_name])) {
                $front_page_elements[] = esc_attr($_POST[$field_name]);
            }
        }
        update_option("theme_name_front_page_elements", $front_page_elements);
        ?>
            <div id="message" class="updated">Settings saved</div>
        <?php
    }
    
    
    $num_elements = get_option("theme_name_num_elements");
    $front_page_elements = get_option("theme_name_front_page_elements");
    $posts = get_posts();
    
    require_once( SIMPLE_FLOW_JAVASCRIPT . '/setting-page.js' );
    require_once( SIMPLE_FLOW_INC . '/setting-page.php' );
}*/

add_action( 'show_user_profile', 'simple_flow_add_profile_field' );
add_action( 'edit_user_profile', 'simple_flow_add_profile_field' );
function simple_flow_add_profile_field($user) {

  wp_nonce_field( 'simple_flow_field_update', 'simple_flow_field_update', false );//à placer avant notre champ personnalisé
  $groups_elements = get_option('simple_flow_groups');
    ?>
  <h3><?php _e("Pick up a group");?></h3>
  <table class="form-table">
  <tbody>
  <tr>
  <th><label for="group"><?php _e("Pick up a group"); ?></label></th>
  <td>
    <select id="group" name="group">
        <?php
        $user_group = get_user_meta($user->ID,'_user_group',true);
        foreach($groups_elements as $element):
            $reviewer = get_user_by('id', $element['contact']);
        ?>
        <option value="<?php echo $element['id']; ?>" <?php if($user_group == $element['id']) echo 'selected'; ?>><?php echo $element['group']." [".$reviewer->user_email."]";?></option>
        <?php endforeach; ?>
    </select>
  </tr>
  </tbody>
  </table><?php 
}

add_action( 'personal_options_update', 'simple_flow_save_extra_user_profile_field');
add_action( 'edit_user_profile_update', 'simple_flow_save_extra_user_profile_field');

function simple_flow_save_extra_user_profile_field( $user_id ) {
  if( !current_user_can( 'edit_user', $user_id ) || ! isset( $_POST['simple_flow_field_update'] ) || ! wp_verify_nonce( $_POST['simple_flow_field_update'], 'simple_flow_field_update' ) ) { 
    return false; 
  }
  $group = wp_filter_nohtml_kses($_POST['group']);
  update_user_meta( $user_id, '_user_group', $group );
}

add_action('pending_post', 'simple_flow_notification');
function simple_flow_notification($post_id) {
    $post = get_post($post_id);
    $author = get_userdata($post->post_author);
    $user_group = get_user_meta($author->ID,'_user_group',true);
    $groups_elements = get_option('simple_flow_groups');  
    
    foreach($groups_elements as $element):
        if($user_group == $element['id']) $reviewer = get_userdata($element['contact']);
    endforeach;  

    $message = "Bonjour ".$reviewer->user_nicename.", ";
    $message.= "l'article ".$post->post_title." vient d'être mis en relecture par ".$author->user_nicename."./n";
    $message.= "Merci de le vérifier avant publication.";

    wp_mail($reviewer->user_email, 'Un article a été mis en relecture', $message);
}

add_filter('manage_users_columns', 'pippin_add_user_id_column');
function pippin_add_user_id_column($columns) {
    $columns['groupe'] = 'Groupe';
    return $columns;
}
 
add_action('manage_users_custom_column',  'pippin_show_user_id_column_content', 10, 3);
function pippin_show_user_id_column_content($value, $column_name, $user_id) {
    $user_group = get_user_meta($user_id,'_user_group',true);
    $groups_elements = get_option('simple_flow_groups');
	if ( 'groupe' == $column_name )
		return $groups_elements[$user_group]['group'];
    return $value;
}
