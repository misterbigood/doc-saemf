<?php
function Insert_Edit_Account_Form($atts) {
		global $wpdb, $user_message, $feup_success;
		global $ewd_feup_user_table_name;
		
		$Custom_CSS = get_option("EWD_FEUP_Custom_CSS");
		$Username_Is_Email = get_option("EWD_FEUP_Username_Is_Email");
		
		$CheckCookie = CheckLoginCookie();
		
		//$Sql = "SELECT * FROM $ewd_feup_fields_table_name ";
		//$Fields = $wpdb->get_results($Sql);
		$User = $wpdb->get_row($wpdb->prepare("SELECT * FROM $ewd_feup_user_table_name WHERE Username='%s'", $CheckCookie['Username']));
		
		$feup_Label_Please =  get_option("EWD_FEUP_Label_Please");
		if ($feup_Label_Please == "") {$feup_Label_Please = __("Please", 'EWD_FEUP');}
		$feup_Label_To_Continue =  get_option("EWD_FEUP_Label_To_Continue");
		if ($feup_Label_To_Continue == "") {$feup_Label_To_Continue = __("To Continue", 'EWD_FEUP');}
		$feup_Label_Login =  get_option("EWD_FEUP_Label_Login");
		if ($feup_Label_Login == "") {$feup_Label_Login = __("Login", 'EWD_FEUP');}
		$feup_Label_Email =  get_option("EWD_FEUP_Label_Email");
		if ($feup_Label_Email == "") {$feup_Label_Email = __("Email", 'EWD_FEUP');}
		$feup_Label_Username =  get_option("EWD_FEUP_Label_Username");
		if ($feup_Label_Username == "") {$feup_Label_Username = __("Username", 'EWD_FEUP');}
		$feup_Label_Password =  get_option("EWD_FEUP_Label_Password");
		if ($feup_Label_Password == "") {$feup_Label_Password = __("Password", 'EWD_FEUP');}
		$feup_Label_Repeat_Password = get_option("EWD_FEUP_Label_Repeat_Password");
		if ($feup_Label_Repeat_Password == "") {$feup_Label_Repeat_Password = __("Repeat Password", 'EWD_FEUP');}
		$feup_Label_Password_Strength = get_option("EWD_FEUP_Label_Password_Strength");
		if ($feup_Label_Password_Strength == "") {$feup_Label_Password_Strength = __("Password Strength", 'EWD_FEUP');}
		$feup_Label_Require_Login_Message =  get_option("EWD_FEUP_Label_Require_Login_Message");
	if ($feup_Label_Require_Login_Message == "") {$feup_Label_Require_Login_Message =  __('You must be logged in to access this page.', 'EWD_FEUP');}

		$ReturnString = "";
		
		// Get the attributes passed by the shortcode, and store them in new variables for processing
		extract( shortcode_atts( array(
						 								 		'redirect_page' => '#',
																'login_page' => '',
																'submit_text' => __('Update Account', 'EWD_FEUP')),
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
		
		$ReturnString .= "<div id='ewd-feup-edit-account-form-div' class='ewd-feup-form-div'>";
		if (isset($user_message['Message'])) {$ReturnString .= $user_message['Message'];}
		$ReturnString .= "<form action='#' method='post' id='ewd-feup-edit-account-form' class='pure-form pure-form-aligned feup-pure-form-aligned'>";
		$ReturnString .= "<input type='hidden' name='ewd-feup-check' value='" . sha1(md5($Time.$Salt)) . "'>";
		$ReturnString .= "<input type='hidden' name='ewd-feup-time' value='" . $Time . "'>";
		$ReturnString .= "<input type='hidden' name='ewd-feup-action' value='edit-account'>";
		if($Username_Is_Email == "Yes") {
			$ReturnString .= "<div class='feup-pure-control-group'>";
			$ReturnString .= "<label for='Username' id='ewd-feup-register-username-div' class='ewd-feup-field-label ewd-feup-login-label'>" . $feup_Label_Email . ": </label>";
						// $ReturnString .= "<div id='ewd-feup-register-username-div' class='ewd-feup-field-label'>" . __('Email', 'EWD_FEUP') . ": </div>";
			$ReturnString .= "<input type='email' class='ewd-feup-text-input' name='Username' value='" . $User->Username . "'>";
			$ReturnString .= "</div>";
		} else {
			$ReturnString .= "<div class='feup-pure-control-group'>";
			$ReturnString .= "<label for='Username' id='ewd-feup-register-username-div' class='ewd-feup-field-label ewd-feup-login-label'>" . $feup_Label_Username . ": </label>";
						// $ReturnString .= "<div id='ewd-feup-register-username-div' class='ewd-feup-field-label'>" . __('Username', 'EWD_FEUP') . ": </div>";
			$ReturnString .= "<input type='text' class='ewd-feup-text-input' name='Username' value='" . $User->Username . "'>";
			$ReturnString .= "</div>";
		}
		$ReturnString .= "<div class='feup-pure-control-group'>";
		$ReturnString .= "<label for='Password' id='ewd-feup-login-password-div' class='ewd-feup-field-label ewd-feup-login-label'>" . $feup_Label_Password . ": </label>";
		// $ReturnString .= "<div id='ewd-feup-register-password-div' class='ewd-feup-field-label'>" . __('Password', 'EWD_FEUP') . ": </div>";
		$ReturnString .= "<input type='password' class='ewd-feup-text-input ewd-feup-password-input' name='User_Password' value=''>";
		$ReturnString .= "</div>";
		$ReturnString .= "<div class='feup-pure-control-group'>";
		$ReturnString .= "<label for='Repeat-Password' id='ewd-feup-register-password-confirm-div' class='ewd-feup-field-label ewd-feup-login-label'>". $feup_Label_Repeat_Password .  ": </label>";
		// $ReturnString .= "<div id='ewd-feup-register-password-confirm-div' class='ewd-feup-field-label'>" . __('Repeat Password', 'EWD_FEUP') . ": </div>";
		$ReturnString .= "<input type='password' class='ewd-feup-text-input ewd-feup-check-password-input' name='Confirm_User_Password' value=''>";
		$ReturnString .= "</div>";
		$ReturnString .= "<div class='feup-pure-control-group'>";
		$ReturnString .= "<label for='Password Strength' id='ewd-feup-password-strength' class='ewd-feup-field-label'>" . $feup_Label_Password_Strength . ": </label>";
		$ReturnString .= "<span id='ewd-feup-password-result'>Too Short</span>";
		$ReturnString .= "</div>";
		$ReturnString .= "<div class='feup-pure-control-group'><label for='submit'></label><input type='submit' class='ewd-feup-submit feup-pure-button feup-pure-button-primary' name='Edit_Profile_Submit' value='" . $submit_text . "'></div>";
		$ReturnString .= "</form>";
		$ReturnString .= "</div>";

		return $ReturnString;
}
add_shortcode("account-details", "Insert_Edit_Account_Form");
?>
