<?php
$selected_user = isset( $_GET['user'] ) ? intval( sanitize_text_field( $_GET["user"] )) : get_current_user_id();
$wfactions = $inbox_workflow->get_assigned_post( null, $selected_user ) ;
$count_posts = count($wfactions);
$pagenum = (isset($_GET['paged']) && $_GET["paged"]) ? intval( sanitize_text_field( $_GET["paged"] )) : 1;
$per_page = 10;
$posteditable = current_user_can('edit_others_posts') ;
$current_user_role = FCProcessFlow::get_current_user_role() ;
$current_user_id = get_current_user_id();

$workflow_terminology_options = get_option( 'oasiswf_custom_workflow_terminology' );
$sign_off_label = !empty( $workflow_terminology_options['signOffText'] ) ? $workflow_terminology_options['signOffText'] : __( 'Sign Off', 'oasisworkflow' );
$abort_workflow_label = !empty( $workflow_terminology_options['abortWorkflowText'] ) ? $workflow_terminology_options['abortWorkflowText'] : __( 'Abort Workflow', 'oasisworkflow' );

?>
<div class="wrap">
	<div id="icon-edit" class="icon32 icon32-posts-post"><br></div>
	<h2><?php echo __("Inbox", "oasisworkflow"); ?></h2>
	<div id="workflow-inbox">
		<div class="tablenav">
		<?php if ( $current_user_role == "administrator" ){?>
			<div class="alignleft actions">
				<select id="inbox_filter">
				<option value=<?php echo $current_user_id;?> selected="selected"><?php echo __("View inbox of ", "oasisworkflow")?></option>
					<?php
					$assigned_users = $inbox_workflow->get_assigned_users();
					if( $assigned_users )
					{
						foreach ($assigned_users as $assigned_user) {
							if( (isset( $_GET['user'] ) && intval( sanitize_text_field( $_GET["user"] )) == $assigned_user->ID) )
								echo "<option value='".esc_attr( $assigned_user->ID )."' selected>{$assigned_user->display_name}</option>" ;
							else
								echo "<option value='".esc_attr( $assigned_user->ID )."'>{$assigned_user->display_name}</option>" ;

						}
					}
					?>
				</select>

				<a href="javascript:window.open('<?php echo admin_url('admin.php?page=oasiswf-inbox&user=')?>' + jQuery('#inbox_filter').val(), '_self')">
					<input type="button" class="button-secondary action" value="<?php echo __("Show", "oasisworkflow"); ?>" />
				</a>
			</div>
		<?php }?>
			<ul class="subsubsub"></ul>
			<div class="tablenav-pages">
				<?php $inbox_workflow->get_page_link($count_posts,$pagenum, $per_page);?>
			</div>
		</div>
		<table class="wp-list-table widefat fixed posts" cellspacing="0" border=0>
			<thead>
				<?php $inbox_workflow->get_table_header();?>
			</thead>
			<tfoot>
				<?php $inbox_workflow->get_table_header();?>
			</tfoot>
			<tbody id="coupon-list">
				<?php
					$wfstatus = get_site_option( "oasiswf_status" ) ;
					$sspace = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" ;
					if($wfactions):
						$count = 0;
						$start = ($pagenum - 1) * $per_page;
						$end = $start + $per_page;
						foreach ($wfactions as $wfaction){
							if ( $count >= $end )
								break;
							if ( $count >= $start )
							{
								$post = get_post($wfaction->post_id);
								$user = get_userdata( $post->post_author ) ;
								$stepId = $wfaction->step_id;
								if ($stepId <= 0 || $stepId == "" ) {
									$stepId = $wfaction->review_step_id;
								}
								$step = $inbox_workflow->get_step_by_id( $stepId ) ;
								$workflow = $inbox_workflow->get_workflow_by_id( $step->workflow_id );

								$chk_claim = $inbox_workflow->check_claim($wfaction->ID) ;

								$current_date = Date( " F j, Y " );
								$due_date = $inbox_workflow->format_date_for_display( $wfaction->due_date );
								$past_due_date_row_class = '';
								$past_due_date_field_class = '';
								if( $due_date != "" && strtotime( $due_date ) < strtotime( $current_date ) ) {
									$past_due_date_row_class = 'past-due-date-row';
									$past_due_date_field_class = 'past-due-date-field';

								}

								echo "<tr id='post-{$wfaction->post_id}' class='post-{$wfaction->post_id} post type-post $past_due_date_row_class
									status-pending format-standard hentry category-uncategorized alternate iedit author-other'> " ;
								echo "<th scope='row' class='check-column'><input type='checkbox' name='post[]' value=".esc_attr( $wfaction->post_id )."></th>" ;

								echo "<td><strong>{$post->post_title}";
											 _post_states( $post ) ;
										echo "</strong>" ;
										if( $chk_claim ){
											echo "<div class='row-actions'>
													<span>
														<a href='#' class='claim' actionid='".esc_attr( $wfaction->ID )."'>" . __("Claim", "oasisworkflow") . "</a>
														<span class='loading'>$sspace</span>
													</span>
												</div>" ;
										}else{
											echo "<div class='row-actions'>" ;
											if($posteditable || ($user->ID == $current_user_id )){
												echo "<span><a href='post.php?post={$wfaction->post_id}&action=edit&oasiswf={$wfaction->ID}&user={$selected_user}' class='edit' real='".esc_attr( $wfaction->post_id)."'>" . __("Edit", "oasisworkflow"). "</a></span>&nbsp;|&nbsp;" ;
											}

												echo "<span><a target='_blank' href='" . get_permalink($wfaction->post_id) . "'>" . __("View", "oasisworkflow") . "</a></span>&nbsp;|&nbsp;";
											if($posteditable || ($user->ID == $current_user_id )){
												echo "<span>
														<a href='#' wfid='".esc_attr( $wfaction->ID )."' postid='".esc_attr( $wfaction->post_id )."' class='quick_sign_off'>" . $sign_off_label . "</a>
														<span class='loading'>$sspace</span>
													</span>&nbsp;|&nbsp;" ;
											}
											if ( $current_user_role == "administrator" ){
												echo "<span>
												<a href='#' actionid='".esc_attr( $wfaction->ID )."' class='owf_abort'>" . $abort_workflow_label . "</a>
														<span class='loading'>$sspace</span>
														</span>&nbsp;|&nbsp;" ;
											}
												echo "<span>
														<a href='#' wfid='".esc_attr( $wfaction->ID )."' class='reassign'>" . __("Reassign", "oasisworkflow") . "</a>
														<span class='loading'>$sspace</span>
													</span>&nbsp;|&nbsp;";
												echo "<span><a href='admin.php?page=oasiswf-history&post=$wfaction->post_id'> " . __("View History", "oasisworkflow") . "</a></span>";
												echo "</div>";
												get_inline_data($post);
										}
								echo "</td>";
								echo "<td>{$post->post_type}</td>" ;
								echo "<td>{$inbox_workflow->get_user_name($user->ID)}</td>" ;
								$workflow_name = $workflow->name;
								if (!empty( $workflow->version )) {
									$workflow_name .= " (" . $workflow->version . ")";
								}
								echo "<td>{$workflow_name}</td>" ;
								echo "<td>" . FCProcessFlow::get_gpid_dbid( $workflow->ID, $stepId, 'lbl' ) . "</td>" ;
								echo "<td>". $wfstatus[FCProcessFlow::get_gpid_dbid( $workflow->ID, $stepId, 'process' )] ."</td>" ;
								echo "<td><span class=' . $past_due_date_field_class . '>" . $inbox_workflow->format_date_for_display($wfaction->due_date) . "</span></td>" ;
								echo "<td class='comments column-comments'>
										<div class='post-com-count-wrapper'>
											<strong>
												<a href='#' actionid='".esc_attr( $wfaction->ID )."' class='post-com-count post-com-count-approved' data-comment='inbox_comment' post_id='".esc_attr( $wfaction->post_id )."'>
													<span class='comment-count-approved'>{$inbox_workflow->get_comment_count( $wfaction->ID, TRUE, $wfaction->post_id )}</span>
												</a>
											<span class='loading'>$sspace</span>
											</strong>
										</div>
									  </td>" ;
								echo "</tr>" ;
							}
							$count++;
						}
					else:
						echo "<tr>" ;
						echo "<td class='hurry-td' colspan='9'>
								<label class='hurray-lbl'>";
						echo __("Hurray! No assignments", "oasisworkflow");
						echo "</label></td>" ;
						echo "</tr>" ;
					endif;
				?>
			</tbody>
		</table>
		<div class="tablenav">
			<div class="tablenav-pages">
				<?php $inbox_workflow->get_page_link($count_posts,$pagenum, $per_page);?>
			</div>
		</div>
	</div>
</div>
<span id="wfeditlinecontent"></span>
<div id ="step_submit_content"></div>
<div id="reassign-div"></div>
<div id="post_com_count_content"></div>
