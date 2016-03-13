<?php
if (is_admin()) {
    wp_enqueue_script('jquery-ui-sortable');
}
?>
<div class="wrap">
    <h2>Simple Flow settings</h2>
 
    <form method="POST" action="">
        <table class="form-table">
            <tr valign="top">
                <th scope="row">
                    <label for="num_elements">
                        Number of elements on a row:
                    </label>
                </th>
                <td>
                    <input type="text" name="num_elements" value="<?php echo $num_elements;?>" size="25" />
                </td>
            </tr>                
        </table>
 
        <h3>Groupes et contacts</h3>
 
        <ul id="featured-posts-list">
            <?php $element_counter = 0; foreach ($front_page_elements as $element) : ?>
                <li class="front-page-element" id="front-page-element-<?php echo $element_counter; ?>">
                    <label for="element-page-id-<?php echo $element_counter; ?>">Featured post:</label>
                    <select name="element-page-id-<?php echo $element_counter; ?>">

                    <?php foreach ($posts as $post) : ?>
                        <?php $selected = ($post->ID == $element) ? "selected" : ""; ?>
                        <option value="<?php echo $post->ID; ?>" <?php echo $selected; ?>>
                            <?php echo $post->post_title; ?>
                        </option>
                    <?php endforeach; ?>

                    </select>

                    <a href="#" onclick="removeElement(jQuery(this).closest('.front-page-element'));">Remove</a>
                </li>

            <?php $element_counter++; endforeach; ?>
        </ul>
       
        <input type="hidden" name="element-max-id" value="<?php echo $element_counter; ?>" />
        <input type="hidden" name="update_settings" value="Y" />
 
        <a href="#" id="add-featured-post">Add featured post</a>
        <p>
            <input type="submit" value="Save settings" class="button-primary"/>
        </p>
    </form>
     
    <li class="front-page-element" id="front-page-element-placeholder" style="display:none">
        <label for="element-page-id">Featured post:</label>
        <select name="element-page-id">
            <?php foreach ($posts as $post) : ?>
                <option value="<?php echo $post->ID; ?>">
                    <?php echo $post->post_title; ?>
                </option>
            <?php endforeach; ?>
        </select>
        <a href="#">Remove</a>
    </li>
 
</div>

