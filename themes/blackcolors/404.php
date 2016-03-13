<?php if(has_post_format('aside')) : ?>
     <?php get_header('gallery');?>
     <?php $post_title_class = "post-title-gallery"; ?>
     <div id="contenidos-gallery">
<?php elseif(has_post_format('gallery')) : ?> 
     <?php get_header('gallery');?>
     <?php $post_title_class = "post-title-gallery"; ?>
     <div id="contenidos-gallery">
<?php else : ?>
      <?php get_header();?>
      <?php $post_title_class = "post-title-normal"; ?>
      <div id="contenidos">
<?php endif; ?>

  <section class="error-404 not-found">
          
          <?php
               $message = get_theme_mod('blackcolors_404_message');
               if($message){
                    echo esc_textarea($message);
               }else{
                    echo "<h1>" . __( 'Oops! That page can&rsquo;t be found.', 'blackcolors' ) . "</h1>";
                    echo "<div>" . __( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'blackcolors' ) . "</div>";
               }
          ?>
         
          <?php
               $search404 = get_theme_mod('blackcolors_404_search');
               if($search404) : ?>
               <div id="search-404">
                   <?php get_search_form(); ?>
               </div>
          <?php endif; ?>

  </section>
</div>
    
<?php get_footer(); ?>