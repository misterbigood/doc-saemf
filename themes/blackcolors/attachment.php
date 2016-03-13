<?php get_header('gallery');?>
<div id="contenidos-gallery">

     <?php if (have_posts()) :?>
         <?php while (have_posts()) : the_post(); ?>
          <div class="post-article">
                <div class="post-title"> 
                    <h2><?php the_title(); ?></h2>
                        <div class="post-edit">
                              <?php echo edit_post_link('<i class="fa fa-pencil-square-o"></i>' . __("Edit this", "blackcolors")); ?>
                         </div>
                </div>
               <div class="post-meta">
                    <i class="fa fa-user"></i> <?php echo get_the_author(); ?>
                    <?php echo get_the_date(); ?>
                    <div class="tags">
                         <?php the_tags('<i class="fa fa-tags"></i> ', ', ', '<br />'); ?> 
                    </div>
               </div>
                    <?php wp_link_pages(); ?>
                    <div class="entry-content">
                          <?php
                         if(wp_attachment_is_image()) {
                             $idpost = get_the_ID();
                             $imagen = wp_get_attachment_image_src($idpost, 'full');
                             echo "<img class='imagenadjunta' src='$imagen[0]'>";
                         }else{
                             the_content(); 
                         }
                         ?>
                    </div>
               <?php blackcolors_the_post_navigation(); ?>
          </div>
             <?php comments_template(); ?> 
         <?php endwhile; ?>
     <?php endif; ?>
</div>

    
<?php get_footer(); ?>