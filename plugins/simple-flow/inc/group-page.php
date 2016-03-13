<div class="wrap">
    <h2>Simple Flow Groups</h2>
 
    <form method="POST" action="">
        <h3>Groups</h3>
 
        <ul id="groups-list">
            <?php $element_counter = 0; foreach ($groups_elements as $element) : ?>
                <li class="group-element" id="group-element-<?php echo $element_counter; ?>">
                    <label for="group-id-<?php echo $element_counter; ?>">Group name:</label>
                    <input type="text" name="group-id-<?php echo $element_counter; ?>" value="<?php echo $element['group'];?>">
                    <select name="contact-id-<?php echo $element_counter; ?>">
                        <?php foreach ($users_elements as $user) : ?>
                            <option value="<?php echo $user->ID; ?>" <?php if($element['contact'] == $user->ID) echo 'selected';?>>
                                <?php echo $user->user_nicename." [".$user->user_email."]"; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <a href="#" onclick="removeElement(jQuery(this).closest('.group-element'));">Remove</a>
                </li>

            <?php $element_counter++; endforeach; ?>
        </ul>
       
        <input type="hidden" name="element-max-id" value="<?php echo $element_counter; ?>" />
        <input type="hidden" name="update_settings" value="Y" />
 
        <a href="#" id="add-group">Add a group</a>
        <p>
            <input type="submit" value="Save settings" class="button-primary"/>
        </p>
    </form>
     
    <li class="group-element" id="group-element-placeholder" style="display:none">
        <label for="group-id">Group name:</label>
        <input type="text" name="group-id">
        <select name="contact-id">
            <?php foreach ($users_elements as $user) : ?>
                <option value="<?php echo $user->ID; ?>">
                    <?php echo $user->user_nicename." [".$user->user_email."]"; ?>
                </option>
            <?php endforeach; ?>
        </select>
        <a href="#">Remove</a>
    </li>
 
</div>

