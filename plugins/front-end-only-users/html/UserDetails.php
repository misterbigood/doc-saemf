<?php 
	$Admin_Approval = get_option("EWD_FEUP_Admin_Approval");
	$Email_Confirmation = get_option("EWD_FEUP_Email_Confirmation");
	$Payment_Frequency = get_option("EWD_FEUP_Payment_Frequency");
?>
<!-- The details of a specific user for editing, based on the user ID -->
	<?php $UserDetails = $wpdb->get_results($wpdb->prepare("SELECT * FROM $ewd_feup_user_fields_table_name WHERE User_ID ='%d'", $_GET['User_ID'])); ?>
	<?php $UserAdmin = $wpdb->get_row($wpdb->prepare("SELECT * FROM $ewd_feup_user_table_name WHERE User_ID ='%d'", $_GET['User_ID'])); ?>
	<?php $Levels = $wpdb->get_results("SELECT * FROM $ewd_feup_levels_table_name ORDER BY Level_Privilege ASC"); ?>
	<?php 
		if (isset($_GET['Page'])) {$Page = $_GET['Page'];}
		else {$Page = 1;}

		$Current_Page = "admin.php?page=EWD-FEUP-options&Action=EWD_FEUP_User_Details&Selected=User&User_ID=" . $UserDetails->User_ID;
				
		$Sql = "SELECT * FROM $ewd_feup_user_events_table_name ";
		$Sql .= "WHERE User_ID=%d ";
		if (isset($_GET['OrderBy'])) {$Sql .= "ORDER BY " . $_GET['OrderBy'] . " " . $_GET['Order'] . " ";}
		else {$Sql .= "ORDER BY Event_Date DESC ";}
		$Sql .= "LIMIT " . ($Page - 1)*100 . ",100";
		$myrows = $wpdb->get_results($wpdb->prepare($Sql, $_GET['User_ID']));
		$Number_of_Pages = ceil($wpdb->num_rows/100);
		if (isset($_GET['OrderBy'])) {$Current_Page_With_Order_By = $Current_Page . "&OrderBy=" .$_GET['OrderBy'] . "&Order=" . $_GET['Order'];}
		$EventCount = $wpdb->num_rows;
	?>
		
	<div class="OptionTab ActiveTab" id="EditProduct">
		<div id='col-right'>
			<div class='col-wrap'>
				<div class="tablenav top">
					<div class="alignleft actions">
						<p><?php _e("Recent User Activity", 'EWD_FEUP'); ?>
					</div>
					<div class='tablenav-pages <?php if ($Number_of_Pages == 1) {echo "one-page";} ?>'>
						<span class="displaying-num"><?php echo $EventCount; ?> <?php _e("events", 'EWD_FEUP') ?> <?php if ($EWD_FEUP_Full_Version != "Yes") {echo "out of 100";}?></span>
						<span class='pagination-links'>
							<a class='first-page <?php if ($Page == 1) {echo "disabled";} ?>' title='Go to the first page' href='<?php echo $Current_Page_With_Order_By; ?>&Page=1'>&laquo;</a>
							<a class='prev-page <?php if ($Page <= 1) {echo "disabled";} ?>' title='Go to the previous page' href='<?php echo $Current_Page_With_Order_By; ?>&Page=<?php echo $Page-1;?>'>&lsaquo;</a>
							<span class="paging-input"><?php echo $Page; ?> <?php _e("of", 'EWD_FEUP') ?> <span class='total-pages'><?php echo $Number_of_Pages; ?></span></span>
							<a class='next-page <?php if ($Page >= $Number_of_Pages) {echo "disabled";} ?>' title='Go to the next page' href='<?php echo $Current_Page_With_Order_By; ?>&Page=<?php echo $Page+1;?>'>&rsaquo;</a>
							<a class='last-page <?php if ($Page == $Number_of_Pages) {echo "disabled";} ?>' title='Go to the last page' href='<?php echo $Current_Page_With_Order_By . "&Page=" . $Number_of_Pages; ?>'>&raquo;</a>
						</span>
					</div>
				</div>
				
				<table class="wp-list-table widefat tags sorttable fields-list ui-sortable" cellspacing="0">
					<thead>
						<tr>
							<th scope='col' class='manage-column column-cb check-column'  style="">
								<?php if ($_GET['OrderBy'] == "Event_Type" and $_GET['Order'] == "ASC") {$Order = "DESC";}
									  else {$Order = "ASC";} ?>
								<a href="<?php echo $Current_Page; ?>&OrderBy=Event_Type&Order=<?php echo $Order; ?>">
								<span>Event Type</span>
								<span class="sorting-indicator"></span>
							</th>
							<th scope='col' class='manage-column column-cb check-column'  style="">
								<?php if ($_GET['OrderBy'] == "Event_Location_Title" and $_GET['Order'] == "ASC") {$Order = "DESC";}
									  else {$Order = "ASC";} ?>
								<a href="<?php echo $Current_Page; ?>&OrderBy=Event_Location_Title&Order=<?php echo $Order; ?>">
								<span>Event Location</span>
								<span class="sorting-indicator"></span>
							</th>
							<th scope='col' class='manage-column column-cb check-column'  style="">
								<?php if ($_GET['OrderBy'] == "Event_Target_Title" and $_GET['Order'] == "ASC") {$Order = "DESC";}
									  else {$Order = "ASC";} ?>
								<a href="<?php echo $Current_Page; ?>&OrderBy=Event_Target_Title&Order=<?php echo $Order; ?>">
								<span>Event Target Title</span>
								<span class="sorting-indicator"></span>
							</th>
							<th scope='col' class='manage-column column-cb check-column'  style="">
								<?php if ($_GET['OrderBy'] == "Event_Date" and $_GET['Order'] == "ASC") {$Order = "DESC";}
									  else {$Order = "ASC";} ?>
								<a href="<?php echo $Current_Page; ?>&OrderBy=Event_Date&Order=<?php echo $Order; ?>">
								<span>Event Date</span>
								<span class="sorting-indicator"></span>
							</th>
						</tr>
					</thead>
				
					<tfoot>
						<tr>
							<th scope='col' class='manage-column column-cb check-column'  style="">
								<?php if ($_GET['OrderBy'] == "Event_Type" and $_GET['Order'] == "ASC") {$Order = "DESC";}
									  else {$Order = "ASC";} ?>
								<a href="<?php echo $Current_Page; ?>&OrderBy=Event_Type&Order=<?php echo $Order; ?>">
								<span>Event Type</span>
								<span class="sorting-indicator"></span>
							</th>
							<th scope='col' class='manage-column column-cb check-column'  style="">
								<?php if ($_GET['OrderBy'] == "Event_Location_Title" and $_GET['Order'] == "ASC") {$Order = "DESC";}
									  else {$Order = "ASC";} ?>
								<a href="<?php echo $Current_Page; ?>&OrderBy=Event_Location_Title&Order=<?php echo $Order; ?>">
								<span>Event Location</span>
								<span class="sorting-indicator"></span>
							</th>
							<th scope='col' class='manage-column column-cb check-column'  style="">
								<?php if ($_GET['OrderBy'] == "Event_Target_Title" and $_GET['Order'] == "ASC") {$Order = "DESC";}
									  else {$Order = "ASC";} ?>
								<a href="<?php echo $Current_Page; ?>&OrderBy=Event_Target_Title&Order=<?php echo $Order; ?>">
								<span>Event Target Title</span>
								<span class="sorting-indicator"></span>
							</th>
							<th scope='col' class='manage-column column-cb check-column'  style="">
								<?php if ($_GET['OrderBy'] == "Event_Date" and $_GET['Order'] == "ASC") {$Order = "DESC";}
									  else {$Order = "ASC";} ?>
								<a href="<?php echo $Current_Page; ?>&OrderBy=Event_Date&Order=<?php echo $Order; ?>">
								<span>Event Date</span>
								<span class="sorting-indicator"></span>
							</th>
						</tr>
					</tfoot>
				
					<tbody id="the-list" class='list:tag'>
					<?php if ($EWD_FEUP_Full_Version == "Yes") { ?>					
					<?php
						if ($myrows) { 
					  		foreach ($myrows as $Event) {
								echo "<tr id='Event-" . $Event->User_Event_ID ."'>";
								echo "<td class='name column-type'>" .  $Event->Event_Type . "</td>";
								echo "<td class='name column-location'>" .  $Event->Event_Location_Title . "</td>";
								echo "<td class='name column-target'>" .  $Event->Event_Target_Title . "</td>";
								echo "<td class='name column-date'>" .  $Event->Event_Date . "</td>";
								echo "</tr>";
							}
						}
					?>
					<?php } else {
						echo "<tr>";
						echo "<td colspan='4'>";
						echo __("The full version of Front-End Only Users is required to view user activity.", "EWD_FEUP");
						echo "<a href='http://www.etoilewebdesign.com/front-end-users-plugin/'>";
						echo __(" Please upgrade to view this information!", 'EWD_FEUP');
						echo "</a>";
						echo "</td>";
						echo "</tr>";
						} ?>
				
					</tbody>
				</table>
				
				<div class="tablenav bottom">
					<?php /*<div class="alignleft actions">
						<select name='action'>
				  			<option value='-1' selected='selected'><?php _e("Bulk Actions", 'EWD_FEUP') ?></option>
							<option value='delete'><?php _e("Delete", 'EWD_FEUP') ?></option>
						</select>
						<input type="submit" name="" id="doaction" class="button-secondary action" value="<?php _e('Apply', 'EWD_FEUP') ?>"  />
					</div>*/ ?>
					<div class='tablenav-pages <?php if ($Number_of_Pages == 1) {echo "one-page";} ?>'>
						<span class="displaying-num"><?php echo $EventCount; ?> <?php _e("events", 'EWD_FEUP') ?></span>
						<span class='pagination-links'>
							<a class='first-page <?php if ($Page == 1) {echo "disabled";} ?>' title='Go to the first page' href='<?php echo $Current_Page_With_Order_By; ?>&Page=1'>&laquo;</a>
							<a class='prev-page <?php if ($Page <= 1) {echo "disabled";} ?>' title='Go to the previous page' href='<?php echo $Current_Page_With_Order_By; ?>&Page=<?php echo $Page-1;?>'>&lsaquo;</a>
							<span class="paging-input"><?php echo $Page; ?> <?php _e("of", 'EWD_FEUP') ?> <span class='total-pages'><?php echo $Number_of_Pages; ?></span></span>
							<a class='next-page <?php if ($Page >= $Number_of_Pages) {echo "disabled";} ?>' title='Go to the next page' href='<?php echo $Current_Page_With_Order_By; ?>&Page=<?php echo $Page+1;?>'>&rsaquo;</a>
							<a class='last-page <?php if ($Page == $Number_of_Pages) {echo "disabled";} ?>' title='Go to the last page' href='<?php echo $Current_Page_With_Order_By . "&Page=" . $Number_of_Pages; ?>'>&raquo;</a>
						</span>
					</div>
					<br class="clear" />
				</div>

			</div>
		</div>

		<div id='col-left'>
			<div class='col-wrap'>
				<div class="form-wrap UserDetail">
					<a href="admin.php?page=EWD-FEUP-options&DisplayPage=Users" class="NoUnderline">&#171; <?php _e("Back", 'EWD_FEUP') ?></a>
					<h2><?php _e("Edit User", 'EWD_FEUP'); ?>: <?php echo($UserAdmin->Username); ?></h2>
					<a class='delete-tag feup-confirm-one-user' href='admin.php?page=EWD-FEUP-options&Action=EWD_FEUP_DeleteUser&DisplayPage=Users&User_ID=<?php echo $UserAdmin->User_ID; ?>'><?php _e("Delete User", 'EWD_FEUP') ?></a>
					<p><?php echo __("Member since ", 'EWD_FEUP') . $UserAdmin->User_Date_Created; ?></p>
					<?php $Fields = $wpdb->get_results("SELECT * FROM $ewd_feup_fields_table_name"); ?>
					<!-- Form to update a user -->
					<form id="addtag" method="post" action="admin.php?page=EWD-FEUP-options&Action=EWD_FEUP_EditUser&DisplayPage=Users" class="validate" enctype="multipart/form-data">
						<input type="hidden" name="action" value="Edit_User" />
						<input type="hidden" name="User_ID" value="<?php echo $_GET['User_ID']; ?>" />
						<?php wp_nonce_field(); ?>
						<?php wp_referer_field(); ?>
						<select name='Level_ID'>
						<option value='0'>None (0)</option>
						<?php foreach ($Levels as $Level) {
								echo "<option value='" . $Level->Level_ID . "' ";
								if ($UserAdmin->Level_ID == $Level->Level_ID) {echo "selected=selected";}
								echo ">" . $Level->Level_Name . " (" . $Level->Level_Privilege . ")</option>";
						}?> 
						</select>
						<?php if ($Admin_Approval == "Yes") { ?>
								<label for='Admin Approved' id='ewd-feup-register-admin-approved-div' class='ewd-feup-field-label'><?php _e('Admin Approved', 'EWD_FEUP');?>: </label>
								<input type='radio' class='ewd-feup-text-input' name='Admin_Approved' value='Yes' <?php if ($UserAdmin->User_Admin_Approved == "Yes"){echo "checked";} ?>>Yes<br />
								<input type='radio' class='ewd-feup-text-input' name='Admin_Approved' value='No' <?php if ($UserAdmin->User_Admin_Approved == "No"){echo "checked";} ?>>No<br />
						<?php } ?>
						<?php if ($Email_Confirmation == "Yes") { ?>
								<label for='Email Confirmation' id='ewd-feup-register-admin-approved-div' class='ewd-feup-field-label'><?php _e('Email Confirmed', 'EWD_FEUP');?>: </label>
								<input type='radio' class='ewd-feup-text-input' name='Email_Confirmation' value='Yes' <?php if ($UserAdmin->User_Email_Confirmed == "Yes"){echo "checked";} ?>>Yes<br />
								<input type='radio' class='ewd-feup-text-input' name='Email_Confirmation' value='No' <?php if ($UserAdmin->User_Email_Confirmed == "No"){echo "checked";} ?>>No<br />
						<?php } ?>
						<?php if ($Payment_Frequency != "None") { ?>
								<label for='User Membership Fees Paid' id='ewd-feup-register-admin-approved-div' class='ewd-feup-field-label'><?php _e('Membership Fees Paid', 'EWD_FEUP');?>: </label>
								<input type='radio' class='ewd-feup-text-input' name='User_Membership_Fees_Paid' value='Yes' <?php if ($UserAdmin->User_Membership_Fees_Paid == "Yes"){echo "checked";} ?>>Yes<br />
								<input type='radio' class='ewd-feup-text-input' name='User_Membership_Fees_Paid' value='No' <?php if ($UserAdmin->User_Membership_Fees_Paid == "No"){echo "checked";} ?>>No<br />
						<?php } ?>
						<?php if ($Payment_Frequency == "Yearly" or $Payment_Frequency == "Monthly") { ?>
								<label for='User Membership Fees Paid' id='ewd-feup-register-admin-approved-div' class='ewd-feup-field-label'><?php _e('Account Expiry Date', 'EWD_FEUP');?>: </label>
								<input type='datetime-local' class='ewd-feup-text-input' name='User_Account_Expiry' value='<?php echo str_replace(" ", "T", $UserAdmin->User_Account_Expiry); ?>' >
						<?php } ?>
						<?php foreach ($Fields as $Field) {
								$Value = "";
								foreach ($UserDetails as $UserField) { 
									if ($Field->Field_Name == $UserField->Field_Name) {$Value = $UserField->Field_Value;}
								}
						?>
								<div class="form-field form-required">
									<label for="<?php echo $Field->Field_Name; ?>"><?php echo $Field->Field_Name; ?></label>
									<?php if ($Field->Field_Type == "text" or $Field->Field_Type == "mediumint") {?><input name="<?php echo $Field->Field_Name; ?>" class='ewd-admin-regular-text' id="<?php echo $Field->Field_Name; ?>" type="text" value="<?php echo $Value;?>" size="60" />
									<?php } elseif ($Field->Field_Type == "date") {?>
												<input name='<?php echo $Field->Field_Name; ?>' id='ewd-feup-register-input-<?php echo $Field->Field_ID; ?>' class='ewd-feup-date-input pure-input-1-3' type='date' value='<?php echo $Value;?>' />
									<?php } elseif ($Field->Field_Type == "datetime") { ?>
												<input name='<?php echo $Field->Field_Name; ?>' id='ewd-feup-register-input-<?php echo $Field->Field_ID; ?>' class='ewd-feup-datetime-input pure-input-1-3' type='datetime-local' value='<?php echo $Value;?>' />
									<?php } elseif ($Field->Field_Type == "textarea") { ?>
												<textarea name="<?php echo $Field->Field_Name; ?>" class='ewd-admin-large-text' id="<?php echo $Field->Field_Name; ?>"><?php echo $Value ?></textarea>
									<?php } elseif ($Field->Field_Type == "file") {?>
												<?php echo __("Current file:", 'EWD_FEUP') . " " . substr($Value, 10);?>
												<input name='<?php echo $Field->Field_Name; ?>' id='ewd-feup-register-input-<?php echo $Field->Field_ID; ?>' class='ewd-feup-date-input pure-input-1-3' type='file' value='' />
									<?php } elseif ($Field->Field_Type == "picture") { ?>
												<?php _e("Current Picture - ", 'EWD_FEUP'); ?>
												<img src='<?php echo site_url("/wp-content/uploads/ewd-feup-user-uploads/") . $Value; ?>' alt='<?php echo $Field->Field_Name ?>' class='ewd-feup-profile-picture' /><br />
												<input name='<?php echo $Field->Field_Name ?>' id='ewd-feup-register-input-<?php echo $Field->Field_ID; ?>' class='ewd-feup-file-input' type='file' value=''/>
									<?php } elseif ($Field->Field_Type == "select" or $Field->Field_Type == "countries") { ?>
												<?php $Options = explode(",", $Field->Field_Options); ?>
												<?php if ($Field->Field_Type == "countries") {$Options = EWD_FEUP_Return_Country_Array();} ?>
												<select name="<?php echo $Field->Field_Name; ?>" id="<?php echo $Field->Field_Name; ?>">
												<?php foreach ($Options as $Option) { ?><option value='<?php echo $Option; ?>' <?php if ($Value == $Option) {echo "Selected";} ?>><?php echo $Option; ?></option><?php } ?>
												</select>
									<?php } elseif ($Field->Field_Type == "radio") { ?>
												<?php $Options = explode(",", $Field->Field_Options); ?>
												<?php foreach ($Options as $Option) { ?><input type='radio' name="<?php echo $Field->Field_Name; ?>" class='ewd-admin-small-input' value="<?php echo $Option; ?>" <?php if ($Value == $Option) {echo "checked";} ?>><?php echo $Option ?><br/><?php } ?>
									<?php } elseif ($Field->Field_Type == "checkbox") { ?>
												<?php $Options = explode(",", $Field->Field_Options); ?>
												<?php $User_Checkbox = explode(",", $Value); ?>
												<?php foreach ($Options as $Option) { ?><input type="checkbox" class='ewd-admin-small-input' name="<?php echo $Field->Field_Name; ?>[]" value="<?php echo $Option; ?>" <?php if (in_array($Option, $User_Checkbox)) {echo "checked";} ?>><?php echo $Option; ?></br><?php } ?>
									<?php } ?>
									<p><?php echo $Field->Field_Description; ?></p>
								</div>
						<?php } ?>

					<p class="submit"><input type="submit" name="submit" id="submit" class="button-primary" value="<?php _e('Edit User ', 'EWD_FEUP') ?>"  /></p></form>
				</div>
			</div>			
		</div>

	</div>	