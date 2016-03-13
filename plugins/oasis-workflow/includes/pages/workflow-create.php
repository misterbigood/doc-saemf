<?php
global $workflow_message;
$wfid = "";
$workflow = "";
$wfeditable = true;
if( isset($_GET['wf_id']) && $_GET["wf_id"] ) {
	$wfid = intval( sanitize_text_field( $_GET["wf_id"] )) ;
	$workflow = $create_workflow->get_workflow_by_id( $wfid  );
	$wfeditable = $create_workflow->is_wf_editable( $wfid ) ; // check editable.

	if( isset($_POST['save_action']) && !$_POST["save_action"] ){
		$workflow_message = FCWorkflowValidate::check_workflow_validate( $wfid )	;
	}
}
$workflow_info = "";
if (is_object($workflow)) {
	$workflow_info = addslashes( $workflow->wf_info );
}
echo "<script type='text/javascript'>
		wf_structure_data = '{$workflow_info}';
		wfeditable = '{$wfeditable}' ;
	</script>";
?>
<div class="wrap">
	<div id="workflow-edit-icon" class="icon32"><br></div>
	<?php
	if (is_object($workflow)){?>
		<h2><label id="page_top_lbl"><?php echo $workflow->name . " (" . $workflow->version .")" ;?></label></h2>
	<?php }?>
	<form id="wf-form" method="post" action="<?php echo admin_url('admin.php?page=oasiswf-admin');?>" >
		<div style="margin-bottom:10px;">
			<div id='fc_message' <?php echo  ($workflow_message) ? "class='updated fc_error_message'" : "";?> >
				<p><?php echo $workflow_message ; ?></p>
			</div>
			<span class="description">
            <?php add_thickbox(); ?>
				<?php echo __( 'If you want to know more about creating/modifying workflows ', "oasisworkflow") ;?>
            <!-- TODO: if this looks great then lets delete the code from js part .help-popup -->
				<a href="http://www.youtube.com/embed/TLWrjTvsTRs?TB_iframe=true&width=800&height=600" class="thickbox">
					<?php echo __( 'watch this video.', "oasisworkflow") ;?>
				</a>
				<?php echo __( ' You can also look up for more tutorial videos about Oasis Workflow on YouTube.', "oasisworkflow") ;?>
			</span>
			<br class="clear" />
		</div>
		<div class="fc_action">
			<div id="workflow-info-area">
				<div class="postbox-container"  id="process-info-div">
					<div class="postbox" >
						<div class="handlediv" title="Click to toggle"><br></div>
						<h3 style="padding:7px;">
							<span class="process-lbl">
								<?php echo __('Processes', "oasisworkflow");?>
                       	<a href="#" title="<?php echo __('Drag and Drop the processes into the Workflow Design Canvas to create new workflow steps.', "oasisworkflow") ;?>" class="tooltip">
                        	<span title="">
                        	<img src="<?php echo OASISWF_URL . '/img/help.png'; ?>" class="help-icon"/></span>
                        </a>
							</span>
						</h3>
						<div class="move-div">
							<?php
								if($wfeditable){
									echo '<ul id="wfsortable">';
									$fw_process = get_site_option('oasiswf_process');
									foreach ($fw_process as $k => $v) {
										echo "<li class='widget'>
												<div class='widget-wf-process'>" . __($k, "oasisworkflow") . "</div>
											 </li>";
									}
									echo '</ul>';
								}else{
									echo "<ul class='wfeditable'><li class='widget wfmessage'><p>";
									echo __("Processes are not available, since there are items (post/pages) in the workflow.&nbsp;&nbsp;&nbsp; If you want to edit the workflow,&nbsp;&nbsp; please ", "oasisworkflow") . "&nbsp;<a href='#' id='save_as_link'>" . __("save it as a new version", "oasisworkflow");
									echo "</a></p></li><ul>";
								}
							?>
						</div>
					</div>
				</div>
				<div class="postbox-container">
					<div class="postbox" >
						<div class="handlediv" title="Click to toggle"><br></div>
						<h3 style="padding:7px;">
							<span class="workflow-lbl"><?php echo  __("Workflow Info", "oasisworkflow");?></span>
						</h3>
						<?php
							$title = "";
							$dec = "";
							$startdate = "";
							$enddate = "";
							$auto_submit = 0;
							$auto_submit_keywords = "";
							if($workflow){
								$title = $workflow->name;
								$dec = $workflow->description;
								$startdate = $create_workflow->format_date_for_display_and_edit( $workflow->start_date );
								$enddate = $create_workflow->format_date_for_display_and_edit( $workflow->end_date );
							}
						?>
						<div class="move-div" id="workflow-define-div">
							<table>
								<tr>
									<td>
										<label>
											<?php echo  __("Title : ", "oasisworkflow");?>
										</label>
									</td>
								</tr>
								<tr>
									<td>
										<input type="text"  id="define-workflow-title" name="define-workflow-title" style="width:100%;"  value="<?php echo $title;?>"  />
									</td>
								</tr>
								<tr height="20px;"><td>&nbsp;</td><td>&nbsp;</td></tr>
								<tr>
									<td style="vertical-align: top;">
										<label><?php echo  __("Description : ", "oasisworkflow");?></label>
									</td>
								</tr>
								<tr>
									<td>
										<textarea id="define-workflow-description" name="define-workflow-description" class="define-workflow-textarea"
                                        cols="20" rows="10"><?php echo esc_textarea($dec);?></textarea>
									</td>
								</tr>
							</table>
							<div class="div-line"></div>
							<table>
								<tr>
									<td>
										<label>
											<?php echo  __("Start Date :", "oasisworkflow");?>
											<span class="required-color">*</span>
                                 <a href="#" title="<?php echo __('Specify a date from which this workflow will become available for use.', "oasisworkflow") ;?>" class="tooltip">
                                 	<span title="">
                                    <img src="<?php echo OASISWF_URL . '/img/help.png'; ?>" class="help-icon"/></span>
                                	</a>
										</label>
									</td>
								</tr>
								<tr>
									<td>
										<input class="date_input" id="start-date" name="start-date" readonly value="<?php echo esc_attr( $startdate ); ?>" />
										<?php if($wfeditable):?>
											<button class="date-clear"><?php echo __("clear", "oasisworkflow") ;?></button>
										<?php endif;?>
									</td>
								</tr>
								<tr height="10px;"><td>&nbsp;</td><td>&nbsp;</td></tr>
								<tr>
									<td>
										<label>
											<?php echo  __("End date :", "oasisworkflow");?>
                                 <a href="#" title="<?php echo __('End date is not required. If not specified, the workflow is valid for ever. Specify an end date, if you want to retire the workflow.', "oasisworkflow") ;?>" class="tooltip">
                                 	<span title="">
                                    <img src="<?php echo OASISWF_URL . '/img/help.png'; ?>" class="help-icon"/></span>
                               	</a>
										</label>
									</td>
								</tr>
								<tr>
									<td>
                              <input class="date_input" id="end-date" name="end-date" readonly value="<?php echo esc_attr( $enddate ); ?>" />
										<button class="date-clear"><?php echo __("clear", "oasisworkflow") ;?></button>
									</td>
								</tr>
							</table>
						</div>
					</div>
				</div>
			</div>
			<div class="widget-holder dropable-area" id="workflow-area" style="position:relative;">
				<span id="workflow-design-area"><?php echo __("Workflow Design Canvas", "oasisworkflow") ;?></span>
			</div>
			<br class="clear">
		</div>
		<div class="save-action-div">
			<?php if($wfeditable){?>
				<input type="button" value="<?php echo __("Save", "oasisworkflow") ?>" class="button-primary workflow-save-bt" >
				<span class="save_loading">&nbsp;</span>
				<a href="#" id="delete-form"><?php echo __("Clear Workflow", "oasisworkflow") ?></a>
			<?php }else{?>
				<input type="button" value="<?php echo __("Save as new version", "oasisworkflow") ?>" class="button-primary workflow-assave-bt" >
				<input type="button" value="<?php echo __("Save", "oasisworkflow") ?>" class="button-primary workflow-save-bt" >
				<span class="save_loading">&nbsp;</span>
			<?php }?>
		</div>
		<br class="clear" />
		<input type="hidden" id="wf_graphic_data_hi" name="wf_graphic_data_hi" />
		<input type="hidden" id="wf_id" name="wf_id" value='<?php echo esc_attr( $wfid ); ?>' />
		<input type="hidden" id="deleted_step_ids" name="deleted_step_ids" />
		<input type="hidden" id="first_step" name="first_step" value="" />
		<input type="hidden" id="wf_validate_result" name="wf_validate_result" value="active" />
		<input type="hidden" id="save_action" name="save_action" value="workflow_save" />
	</form>
</div>
<?php
echo "<div id='connection-setting'>{$create_workflow->connection_setting_html()}</div>" ;?>
<ul id="connectionMenu" class="contextMenu">
	<div><?php _e('Conn Menu', 'oasisworkflow'); ?></div>
	<li class="edit" id="connEdit" ><a href="#edit"><?php echo __("Edit", "oasisworkflow") ?></a></li>
	<li class="delete" id="connDelete"><a href="#delete"><?php echo __("Delete", "oasisworkflow") ?></a></li>
	<li class="quit separator" id="connQuit"><a href="#quit"><?php echo __("Quit", "oasisworkflow") ?></a></li>
</ul>
<ul id="stepMenu" class="contextMenu">
	<div><?php _e( 'Step Menu', 'oasisworkflow' ); ?></div>
	<li class="edit" id="stepEdit">
		<a><?php echo __("Edit", "oasisworkflow") ?></a></li>
	<?php if($wfeditable):?>
		<li class="delete" id="stepDelete"><a href="#delete"><?php echo __("Delete", "oasisworkflow") ?></a></li>
	<?php endif;?>
	<li class="quit separator" id="stepQuit"><a href="#quit"><?php echo __("Quit", "oasisworkflow") ?></a></li>
</ul>
<div id="step-info-update" class="owf-hidden"></div>
<script type="text/javascript">
	//-------------------------------------
	jQuery("#wpbody").css({"position":"inherit"});
	function call_modal(param){
		jQuery('.contextMenu').hide();
		jQuery('#'+param).owfmodal();
	}
</script>