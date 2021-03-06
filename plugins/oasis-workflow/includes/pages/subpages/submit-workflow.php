<?php
$workflow = FCProcessFlow::get_workflow_by_validity( 1 ) ;
$reminder_days = get_option('oasiswf_reminder_days');
$reminder_days_after = get_option('oasiswf_reminder_days_after');
$default_due_days = get_option('oasiswf_default_due_days') ;
$default_date = '';
if ( !empty( $default_due_days )) {
	$default_date = date(OASISWF_EDIT_DATE_FORMAT, current_time('timestamp') + DAY_IN_SECONDS * $default_due_days);
}
$publish_date = current_time(OASISWF_EDIT_DATE_FORMAT);
$publish_time_array = explode("-", current_time("H-i"));

$workflow_terminology_options = get_option( 'oasiswf_custom_workflow_terminology' );
$assign_actors_label = !empty( $workflow_terminology_options['assignActorsText'] ) ? $workflow_terminology_options['assignActorsText'] : __( 'Assign Actor(s)', 'oasisworkflow' );
$due_date_label = !empty( $workflow_terminology_options['dueDateText'] ) ? $workflow_terminology_options['dueDateText'] : __( 'Due Date', 'oasisworkflow' );
$publish_date_label = !empty( $workflow_terminology_options['publishDateText'] ) ? $workflow_terminology_options['publishDateText'] : __( 'Publish Date', 'oasisworkflow' );

?>
<div class="info-setting" id="new-workflow-submit-div">
	<div class="dialog-title"><strong><?php echo __("Submit", "oasisworkflow") ;?></strong></div>
	<div>
		<div class="select-part">
			<label><?php echo __("Workflow : ", "oasisworkflow") ;?></label>
			<select id="workflow-select" style="width:200px;">
				<?php
				$count = count($workflow);
				if( $workflow ){
					$ary_sel = array();
					foreach ($workflow as $row) {
						if( FCProcessFlow::check_submit_wf_editable( $row->ID ) ){
							$ary_sel[] = $row->ID;
							if( $row->version== 1 )
								echo "<option value='".esc_attr( $row->ID )."'>" . $row->name . "</option>" ;
							else
								echo "<option value='".esc_attr( $row->ID )."'>" . $row->name . " (" . $row->version . ")" . "</option>" ;
						}
					}
				}
				?>
			</select>
			<br class="clear">
		</div>
       <?php
       if(count($ary_sel) == 1) {
          echo <<<TRIGGER_EVENT
            <script>
               jQuery(document).ready(function() {
                  jQuery("#workflow-select option[value='$ary_sel[0]']").prop("selected", true);
               });
            </script>
TRIGGER_EVENT;
       } else {
          echo <<<ADD_BLANK_OPTION
            <script>
               jQuery(document).ready(function() {
                  jQuery('#workflow-select').prepend('<option selected="selected"></option>');
               });
            </script>
ADD_BLANK_OPTION;
       }
       ?>		
		<div class="select-info">
			<label><?php echo __("Step : ", "oasisworkflow") ;?></label>
			<select id="step-select" name="step-select" style="width:150px;" real="step-loading-span" disabled="true"></select>
			<span id="step-loading-span"></span>
			<br class="clear">
		</div>

		<div id="one-actors-div" class="select-info">
			<label><?php echo __("Assign actor : ", "oasisworkflow") ;?></label>
			<select id="actor-one-select" name="actor-one-select" style="width:150px;" real="assign-loading-span"></select>
			<span class="assign-loading-span">&nbsp;</span>
			<br class="clear">
		</div>

		<div id="multiple-actors-div" class="select-info" style="height:140px;">
			<label><?php echo $assign_actors_label ." :" ; ?></label>
			<div class="select-actors-div">
				<div class="select-actors-list" >
					<label><?php echo __("Available", "oasisworkflow") ;?></label>
					<span class="assign-loading-span" style="float:right;">&nbsp;</span><br>

					<p>
						<select id="actors-list-select" name="actors-list-select" multiple="multiple" size=10></select>
					</p>
				</div>
				<div class="select-actors-div-point">
					<a href="#" id="assignee-set-point"><img src="<?php echo OASISWF_URL . "img/role-set.png";?>" style="border:0px;" /></a><br><br>
					<a href="#" id="assignee-unset-point"><img src="<?php echo OASISWF_URL . "img/role-unset.png";?>" style="border:0px;" /></a>
				</div>
				<div class="select-actors-list">
					<label><?php echo __("Assigned", "oasisworkflow") ;?></label><br>
					<p>
						<select id="actors-set-select" name="actors-set-select" multiple="multiple" size=10></select>
					</p>
				</div>
			</div>
			<br class="clear">
		</div>
		<?php if ($default_due_days != '' || $reminder_days != '' || $reminder_days_after != ''):?>
		<div class="text-info left">
			<div class="left">
				<label><?php echo $due_date_label . " : ";?></label>
			</div>
			<div class="left">
				<input class="date_input" name="due-date" id="due-date"  value="<?php echo esc_attr( $default_date ); ?>"/>
		        <button class="date-clear" ><?php echo __("clear", "oasisworkflow") ;?></button>
			</div>
			<br class="clear">
		</div>
		<?php endif;?>
		<!-- Added publish date box for user to choose future publish date. -->
         <div class="text-info left">
			<div class="left">
				<label><?php echo $publish_date_label . " : " ;?></label>
			</div>
			<div class="left">
				<input name="publish-date" id="publish-date" class="date_input" type="text" real="publish-date-loading-span" value="<?php echo esc_attr( $publish_date ); ?>">@
				<input type="text" name="publish-hour" id="publish-hour" class="date_input wf-time" placeholder="hour" maxlength="2" value="<?php echo esc_attr( $publish_time_array[0] ); ?>">:
				<input type="text" name="publish-min" id="publish-min" class="date_input wf-time" placeholder="min"  maxlength="2" value="<?php echo esc_attr( $publish_time_array[1] ); ?>">
				<button class="date-clear" ><?php echo __("clear", "oasisworkflow") ;?></button>
				<span class="publish-date-loading-span">&nbsp;</span>
			</div>
			<br class="clear">
		</div>
		<div class="text-info left" id="comments-div">
			<div class="left">
				<label><?php echo __("Comments : ", "oasisworkflow") ;?></label>
			</div>
			<div class="left">
				<textarea id="comments" style="height:100px;width:400px;margin-top:10px;" ></textarea>
			</div>
			<br class="clear">
		</div>
		<div class="changed-data-set">
			<input type="button" id="submitSave" class="button-primary" value="<?php echo __("Submit", "oasisworkflow") ;?>" />
			<span>&nbsp;</span>
			<a href="#" id="submitCancel"><?php echo __("Cancel", "oasisworkflow") ;?></a>
		</div>
		<br class="clear">
	</div>
</div>