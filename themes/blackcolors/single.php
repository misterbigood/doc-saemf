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
     
     <?php if (have_posts()) :?>
         <?php while (have_posts()) : the_post(); ?>

          <article id="post-<?php the_ID(); ?>" <?php post_class( 'post-article h-entry'); ?>>

               <div class="top-widget">
                    <?php dynamic_sidebar('breadcrumbs'); ?>
                    <?php
                    if ( function_exists('yoast_breadcrumb') ) {
                        yoast_breadcrumb('<p id="breadcrumbs">','</p>');
                    }
                    ?>
                </div>
          
               <!-- Thumbnail & Title -->
               <div class="entry-header">
               <?php if(has_post_thumbnail()) :?>
                    <div id="post-imagen">
                         <?php the_post_thumbnail('full', array('class' => 'articulo-img-thumbnail')); ?>
                    </div>
                    <div class="post-title-image"> 
                         <h2 class="<?php echo $post_title_class; ?> p-name"><?php the_title(); ?></h2>
                         <div class="post-edit">
                              <?php echo edit_post_link('<i class="fa fa-pencil-square-o"></i>' . __("Edit this", "blackcolors")); ?>
                         </div>
                    </div>
               <?php else: ?>
                    <div class="post-title">
                        <h2 class="<?php echo $post_title_class; ?> p-name"><?php the_title(); ?></h2>
                         <div class="post-edit">
                         <?php echo edit_post_link('<i class="fa fa-pencil-square-o"></i>' . __("Edit this", "blackcolors")); ?>
                         </div>
                    </div>
               <?php endif; ?>
               </div>
               
               <?php wp_link_pages(); ?>

               <div class="entry-content e-content">
                    <?php the_content(); ?>
               </div>

               <!-- Post meta -->
               <div class="post-meta">
                    <span><i class="fa fa-user"></i>&nbsp;<?php echo get_the_author(); ?>  &nbsp; <i class="fa fa-clock-o"></i> <?php echo get_the_date(); ?> </span>
                    <span>
                         <?php the_tags('<i class="fa fa-tags"></i> ', ', ', '<br />'); ?> 
                    </span>
                    <span>
                    <?php 
                    $categories_list = get_the_category_list( __( ', ', 'blackcolors' ) );
                         if ( $categories_list ) {
                             printf( '<span class="cat-links">' . __( 'Posted in %1$s', 'blackcolors' ) . '</span>', $categories_list );
                         }
                    ?>
                    </span>
               </div>

               <?php blackcolors_the_post_navigation(); ?>

          </article>

          <?php comments_template(); ?>
             
         <?php endwhile; ?>
     <?php endif; ?>
</div>

    
<?php get_footer(); ?>