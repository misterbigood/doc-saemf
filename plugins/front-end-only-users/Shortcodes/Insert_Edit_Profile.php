<?php 
/* The function that creates the HTML on the front-end, based on the parameters
* supplied in the product-catalog shortcode */
function Insert_Edit_Profile($atts) {
	// Include the required global variables, and create a few new ones
	global $wpdb, $user_message, $feup_success;
	global $ewd_feup_fields_table_name, $ewd_feup_user_table_name, $ewd_feup_user_fields_table_name, $ewd_feup_levels_table_name;
		
	$Payment_Types = get_option("EWD_FEUP_Payment_Types");

	$Custom_CSS = get_option("EWD_FEUP_Custom_CSS");
	$Salt = get_option("EWD_FEUP_Hash_Salt");
	$Time = time();
	
	$CheckCookie = CheckLoginCookie();
	
	$Sql = "SELECT * FROM $ewd_feup_fields_table_name WHERE Field_Show_In_Front_End='Yes' ORDER BY Field_Order";
	$Fields = $wpdb->get_results($Sql);
	$User = $wpdb->get_row($wpdb->prepare("SELECT * FROM $ewd_feup_user_table_name WHERE Username='%s'", $CheckCookie['Username']));
	$UserData = $wpdb->get_results($wpdb->prepare("SELECT * FROM $ewd_feup_user_fields_table_name WHERE User_ID='%d'", $User->User_ID));
	$UserLevel = $wpdb->get_row("SELECT * FROM $ewd_feup_levels_table_name WHERE Level_ID='" . $User->Level_ID . "'");
	$MaxLevel = $wpdb->get_var("SELECT MAX(Level_Privilege) FROM $ewd_feup_levels_table_name");
	
	$feup_Label_Require_Login_Message =  get_option("EWD_FEUP_Label_Require_Login_Message");
	if ($feup_Label_Require_Login_Message == "") {$feup_Label_Require_Login_Message =  __('You must be logged in to access this page.', 'EWD_FEUP');}
	$feup_Label_Please =  get_option("EWD_FEUP_Label_Please");
	if ($feup_Label_Please == "") {$feup_Label_Please = __("Please", 'EWD_FEUP');}
	$feup_Label_To_Continue =  get_option("EWD_FEUP_Label_To_Continue");
	if ($feup_Label_To_Continue == "") {$feup_Label_To_Continue = __("To Continue", 'EWD_FEUP');}
	$feup_Label_Login =  get_option("EWD_FEUP_Label_Login");
	if ($feup_Label_Login == "") {$feup_Label_Login = __("Login", 'EWD_FEUP');}
	$feup_Label_Current_File =  get_option("EWD_FEUP_Label_Current_File");
		if ($feup_Label_Current_File == "") {$feup_Label_Current_File =  __("Current file:", 'EWD_FEUP') ;}
	$feup_Label_Current_Picture =  get_option("EWD_FEUP_Label_Current_Picture");
		if ($feup_Label_Current_Picture == "") {$feup_Label_Current_Picture =  __("Current Picture - ", 'EWD_FEUP');}
	$feup_Label_Update_Picture =  get_option("EWD_FEUP_Label_Update_Picture");
		if ($feup_Label_Update_Picture == "") {$feup_Label_Update_Picture =  __("Update Picture - ", 'EWD_FEUP');}

	$ReturnString = "";
	
	// Get the attributes passed by the shortcode, and store them in new variables for processing
	extract( shortcode_atts( array(
				'redirect_page' => '#',
				'login_page' => '',
				'omit_fields' => '',
				'submit_text' => __('Edit Profile', 'EWD_FEUP')),
			$atts
		)
	);
											
	$ReturnString .= "<style type='text/css'>";
	$ReturnString .= $Custom_CSS;
	 $ReturnString .= EWD_FEUP_Add_Modified_Styles();
	
											
	if ($CheckCookie['Username'] == "") {
		$ReturnString .= $feup_Label_Require_Login_Message;
		if ($login_page != "") {$ReturnString .= "<br />" . $feup_Label_Please . " <a href='" . $login_page . "'>" . $feup_Label_Login . "</a> " . $feup_Label_To_Continue ;}
		return $ReturnString;
	}
	
	if ($feup_success and $redirect_page != '#') {FEUPRedirect($redirect_page);}
	
	$ReturnString .= "<div id='ewd-feup-edit-profile-form-div' class='ewd-feup-form-div'>";
	if (isset($user_message['Message'])) {$ReturnString .= $user_message['Message'];}
	$ReturnString .= "<form action='#' method='post' id='ewd-feup-edit-profile-form' class='pure-form pure-form-aligned feup-pure-form-aligned' enctype='multipart/form-data'>";
	$ReturnString .= "<input type='hidden' name='ewd-feup-check' value='" . sha1(md5($Time.$Salt)) . "'>";
	$ReturnString .= "<input type='hidden' name='ewd-feup-time' value='" . $Time . "'>";
	$ReturnString .= "<input type='hidden' name='ewd-feup-action' value='edit-profile'>";
	$ReturnString .= "<input type='hidden' name='Omit_Fields' value='" . $omit_fields . "'>";
	
	$Omitted_Fields = explode(",", $omit_fields);
	
	foreach ($Fields as $Field) {
		if (!in_array($Field->Field_Name, $Omitted_Fields)) {
			if ($Field->Field_Required == "Yes") {$Req_Text = "required";} 
			else {$Req_Text="";};
			$Value = "";
			foreach ($UserData as $UserField) {
				if ($Field->Field_Name == $UserField->Field_Name) {$Value = $UserField->Field_Value;}
			}
			$ReturnString .= "<div class='feup-pure-control-group'>";
			$ReturnString .= "<label for='" . $Field->Field_Name . "' id='ewd-feup-edit-" . $Field->Field_ID . "' class='ewd-feup-field-label'>" . $Field->Field_Name . ": </label>";
			if ($Field->Field_Type == "text" or $Field->Field_Type == "mediumint") {
			    $ReturnString .= "<input name='" . $Field->Field_Name . "' id='ewd-feup-register-input-" . $Field->Field_ID . "' class='ewd-feup-text-input' type='text' value='" . $Value . "' " . $Req_Text . "/>";
			}
			elseif ($Field->Field_Type == "date") {
				$ReturnString .= "<input name='" . $Field->Field_Name . "' id='ewd-feup-register-input-" . $Field->Field_ID . "' class='ewd-feup-date-input' type='date' value='" . $Value . "' " . $Req_Text . "/>";
			}
			elseif ($Field->Field_Type == "datetime") {
				$ReturnString .= "<input name='" . $Field->Field_Name . "' id='ewd-feup-register-input-" . $Field->Field_ID . "' class='ewd-feup-datetime-input' type='datetime-local' value='" . $Value . "' " . $Req_Text . "/>";
			}
			elseif ($Field->Field_Type == "textarea") {
				$ReturnString .= "<textarea name='" . $Field->Field_Name . "' id='ewd-feup-register-input-" . $Field->Field_ID . "' class='ewd-feup-textarea' " . $Req_Text . ">" . $Value . "</textarea>";
			}
			elseif ($Field->Field_Type == "file") {
				$ReturnString .= $feup_Label_Current_File . " " . substr($Value, 10) . " | ";
				$ReturnString .= "<input name='" . $Field->Field_Name . "' id='ewd-feup-register-input-" . $Field->Field_ID . "' class='ewd-feup-file-input' type='file' value='' " . $Req_Text . "/>";
			} 
			elseif ($Field->Field_Type == "picture") {
				$ReturnString .= $feup_Label_Current_Picture;
				$ReturnString .= "<img src='" . site_url("/wp-content/uploads/ewd-feup-user-uploads/") . $Value . "' alt='" . $Field->Field_Name . "' class='ewd-feup-profile-picture' /><br />";
				$ReturnString .= "<div class='ewd-feup-update-picture'>" . $feup_Label_Update_Picture . "<input name='" . $Field->Field_Name . "' id='ewd-feup-register-input-" . $Field->Field_ID . "' class='ewd-feup-file-input' type='file' value='' " . $Req_Text . "/></div>";
			} 
			elseif ($Field->Field_Type == "select" or $Field->Field_Type == "countries") { 
				$Options = explode(",", $Field->Field_Options);
				if ($Field->Field_Type == "countries") {$Options = EWD_FEUP_Return_Country_Array();}
				$ReturnString .= "<select name='" . $Field->Field_Name . "' id='ewd-feup-register-input-" . $Field->Field_ID . "' class='ewd-feup-select'>";
		 		foreach ($Options as $Option) {
					$ReturnString .= "<option value='" . $Option . "' ";
					if (trim($Option) == trim($Value)) {$ReturnString .= "selected='selected'";}
					$ReturnString .= ">" . $Option . "</option>";
				}
				$ReturnString .= "</select>";
			} 
			elseif ($Field->Field_Type == "radio") {
				$Counter = 0;
				$Options = explode(",", $Field->Field_Options);
				foreach ($Options as $Option) {
					if ($Counter != 0) {$ReturnString .= "</div><div class='feup-pure-control-group ewd-feup-negative-top'><label class='feup-pure-radio'></label>";}
					$ReturnString .= "<input type='radio' name='" . $Field->Field_Name . "' value='" . $Option . "' class='ewd-feup-radio' " . $Req_Text . " ";
					if (trim($Option) == trim($Value)) {$ReturnString .= "checked";}
					$ReturnString .= ">" . $Option;
					$Counter++;
				} 
			} 
			elseif ($Field->Field_Type == "checkbox") {
 				$Counter = 0;
				$Options = explode(",", $Field->Field_Options);
				$Values = explode(",", $Value);
				foreach ($Options as $Option) {
					if ($Counter != 0) {$ReturnString .= "</div><div class='feup-pure-control-group ewd-feup-negative-top'><label class='feup-pure-radio'></label>";}
					$ReturnString .= "<input type='checkbox' name='" . $Field->Field_Name . "[]' value='" . $Option . "' class='ewd-feup-checkbox' " . $Req_Text . " ";
					if (in_array($Option, $Values)) {$ReturnString .= "checked";}
					$ReturnString .= ">" . $Option . "</br>";
					$Counter++;
				}
			}
			$ReturnString .= "</div>";
			unset($Req_Text);
		}
	}
	
	$ReturnString .= "<div class='feup-pure-control-group'><label for='submit'></label><input type='submit' class='ewd-feup-submit feup-pure-button feup-pure-button-primary' name='Edit_Profile_Submit' value='" . $submit_text . "'></div>";
	$ReturnString .= "</form>";
	$ReturnString .= "</div>";

	if ($Payment_Types == "Levels") {
		if ($UserLevel->Level_Privilege < $MaxLevel) {
			$ReturnString .= do_shortcode("[account-payment]");
		}
	}
	
	return $ReturnString;
}
add_shortcode("edit-profile", "Insert_Edit_Profile");