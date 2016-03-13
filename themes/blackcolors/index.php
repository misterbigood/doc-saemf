<?php
/**
 * The main template file.
 *
 * @package 
 */
get_header(); ?>

<div id="contenidos">
  
    <?php if (have_posts()) :?>

        <div class="top-widget">
            <?php dynamic_sidebar('breadcrumbs'); ?>
            <?php
            if ( function_exists('yoast_breadcrumb') ) {
                yoast_breadcrumb('<p id="breadcrumbs">','</p>');
            }
            ?>
        </div>
        
        <div id="masonry-container" >
        <?php while (have_posts()) : the_post(); ?>
    
        <!-- Post -->
        <article id="post-<?php the_ID(); ?>" <?php post_class( 'post-index h-entry'); ?>>

            <!-- Thumbnail & Title -->
            <div class="post-thumbnail entry-header">
                <?php if(has_post_thumbnail()) :?>
                    <?php
                    echo "<a href='"; the_permalink(); echo "'>";
                    the_post_thumbnail(array(230,230), array('class' => 'post-index-thumbnail'));
                    echo "</a>"; ?>
                    <div class="post-title-image"> 
                        <h2><a href="<?php the_permalink()?>" class="p-name"><?php the_title(); ?></a></h2>
                    </div>
                <?php else: ?>
                    <div class="post-title">
                        <h2><a href="<?php the_permalink()?>" class="p-name"><?php the_title(); ?></a></h2>
                    </div>
                <?php endif; ?>
            </div>

            <div class="post-meta">
               <i class="fa fa-user"></i>&nbsp;<?php echo get_the_author(); ?>
            </div>
            
            <div class="entry-content p-sumary">
                <?php the_excerpt(); ?>
            </div>

            <!-- Content -->
            <div class="post-mas">
                <div class="post-mas-der">
                <div><a href="<?php the_permalink()?>"><?php echo __('See more', 'blackcolors'); ?></a></div>
                </div>
            </div>

            <!-- Entry footer -->
            <div class="post-mas entry-footer">
                <div class="tags">
                    <?php the_tags('<i class="fa fa-tags"></i> ', ', ', '<br />'); ?>
                </div>
                <div class="post-mas-izq">
                    <?php echo get_the_date(); ?>
                </div>
                <div class="post-mas-der">
                    <?php comments_number('0 <i class="fa fa-weixin"></i>', '1 <i class="fa fa-weixin"></i>', '% <i class="fa fa-weixin"></i>'); ?>
                </div>
            </div>

        </article>
        <?php endwhile; ?>
        <!-- End of The Loop -->
        </div><!-- #masonry-container -->
       
    <?php endif; ?>

</div><!-- #contenidos -->

    <!-- Navigation -->
    <div id="end-contenidos">
        <?php previous_posts_link("<div class='button-pag'><i class='fa fa-arrow-circle-left'></i> " . __('Newer entries', 'balckcolors') . "</div>"); ?>
        <?php next_posts_link("<div class='button-pag'>" . __('Older entries', 'balckcolors') . " <i class='fa fa-arrow-circle-right'></i></div>"); ?>
    </div>
   
<?php get_footer(); ?>