<?php
/* Chargement des feuilles de style du thème */
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {
    wp_enqueue_style( 'pacify', get_template_directory_uri() . '/style.css' );

}

/* Redirection de l'utilisateur quand il n'est pas connecté
 */
add_action( 'template_redirect', 'not_logged_in_redirection' );
function not_logged_in_redirection() {
    if( !is_user_logged_in() && is_home() ) {
        wp_redirect( site_url( '/bienvenue/' ) );
        exit();
    }
}

/* Redirection de l'utilisateur vers la page d'articles lorsqu'il se connecte
*/
add_filter("login_redirect", "user_login_redirect", 10, 3);
function user_login_redirect($redirect_to, $request, $user) {
   return home_url();
}

/* Fonctions de filtre pour afficher les posts privés lorsqu'un utilisateur
 * connecté
 */
add_filter( 'widget_categories_args', 'show_private_post_in_widget' );
function show_private_post_in_widget( $cat_args ) {

  if( current_user_can( 'read_private_posts' ) ) {
	$cat_args['hide_empty'] = 0;
  }

  return $cat_args;
}

add_filter('widget_posts_args', 'show_private_post_in_recent_widget');
function show_private_post_in_recent_widget($post_args) {
    
    if( current_user_can( 'read_private_posts' ) ) {
        $post_args['post_status'] = array('publish','private');
    }
    
    return $post_args;
}

/* Fonction de passage automatique des posts en privé pour tous les utilisateurs
 * sauf admin( qui conserve la possibilité de publier des posts publics)
 */
if ( !function_exists('cdsea_post_to_private') ) {
    function cdsea_post_to_private($data) {
        if( !is_admin() ):
                $data['post_status'] = 'private';
        endif;
	return $data;
    }
}
add_filter('wp_insert_post_data','cdsea_post_to_private');

/* Retrait des préfixes "privés" dans les titres des posts privés */
add_filter('private_title_format', 'removePrivatePrefix');  
add_filter('protected_title_format', 'removePrivatePrefix');

function removePrivatePrefix($format)  
{
    return '%s';
}
