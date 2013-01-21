<?php
function wsl_check_compatibilities()
{
	# widget settings / customize
	if( ! get_option( 'wsl_settings_redirect_url' ) ){ 
		update_option( 'wsl_settings_redirect_url', site_url() );
	}

	if( ! get_option( 'wsl_settings_connect_with_label' ) ){ 
		update_option( 'wsl_settings_connect_with_label', "Connect with:" );
	}

	if( ! get_option( 'wsl_settings_use_popup' ) ){ 
		update_option( 'wsl_settings_use_popup', 2 );
	}

	if( ! get_option( 'wsl_settings_widget_display' ) ){ 
		update_option( 'wsl_settings_widget_display', 1 );
	}

	if( ! get_option( 'wsl_settings_authentication_widget_css' ) ){ 
		update_option( 'wsl_settings_authentication_widget_css', "#wp-social-login-connect-with {font-weight: bold;}\n#wp-social-login-connect-options {padding:10px;}\n#wp-social-login-connect-options a {text-decoration: none;}\n#wp-social-login-connect-options img {border:0 none;}\n.wsl_connect_with_provider {}" );
	}

	# bouncer
	if( ! get_option( 'wsl_settings_bouncer_registration_enabled' ) ){ 
		update_option( 'wsl_settings_bouncer_registration_enabled', 1 );
	}

	if( ! get_option( 'wsl_settings_bouncer_authentication_enabled' ) ){ 
		update_option( 'wsl_settings_bouncer_authentication_enabled', 1 );
	}

	if( ! get_option( 'wsl_settings_bouncer_linking_accounts_enabled' ) ){ 
		update_option( 'wsl_settings_bouncer_linking_accounts_enabled', 2 );
	}

	if( ! get_option( 'wsl_settings_bouncer_email_validation_enabled' ) ){ 
		update_option( 'wsl_settings_bouncer_email_validation_enabled', 2 );
	}

	if( ! get_option( 'wsl_settings_bouncer_email_validation_text_notice' ) ){ 
		update_option( 'wsl_settings_bouncer_email_validation_text_notice', "Almost there, we just need your email address and username" );
	}

	if( get_option( 'wsl_settings_bouncer_email_validation_text_message' ) ){ 
		update_option( 'wsl_settings_bouncer_email_validation_text_notice', get_option( 'wsl_settings_bouncer_email_validation_text_message' ) );
	}

	if( ! get_option( 'wsl_settings_bouncer_email_validation_text_submit_button' ) ){ 
		update_option( 'wsl_settings_bouncer_email_validation_text_submit_button', "Continue" );
	} 

	if( ! get_option( 'wsl_settings_bouncer_email_validation_text_email' ) ){ 
		update_option( 'wsl_settings_bouncer_email_validation_text_email', "E-mail" );
	} 

	if( ! get_option( 'wsl_settings_bouncer_email_validation_text_username' ) ){ 
		update_option( 'wsl_settings_bouncer_email_validation_text_username', "Username" );
	} 

	if( ! get_option( 'wsl_settings_bouncer_email_validation_text_email_invalid' ) ){ 
		update_option( 'wsl_settings_bouncer_email_validation_text_email_invalid', "E-mail is not valid!" );
	} 

	if( ! get_option( 'wsl_settings_bouncer_email_validation_text_username_invalid' ) ){ 
		update_option( 'wsl_settings_bouncer_email_validation_text_username_invalid', "Username is not valid!" );
	} 

	if( ! get_option( 'wsl_settings_bouncer_email_validation_text_email_exists' ) ){ 
		update_option( 'wsl_settings_bouncer_email_validation_text_email_exists', "That E-mail is already registered!" );
	} 

	if( ! get_option( 'wsl_settings_bouncer_email_validation_text_username_exists' ) ){ 
		update_option( 'wsl_settings_bouncer_email_validation_text_username_exists', "That Username is already registered!" );
	} 

	if( ! get_option( 'wsl_settings_bouncer_email_validation_text_connected_with' ) ){ 
		update_option( 'wsl_settings_bouncer_email_validation_text_connected_with', "You are now connected via" );
	} 

	if( ! get_option( 'wsl_settings_bouncer_new_users_moderation_enabled' ) ){ 
		update_option( 'wsl_settings_bouncer_new_users_moderation_enabled', 2 );
	} 

	if( ! get_option( 'wsl_settings_bouncer_new_users_membership_default_role' ) ){ 
		update_option( 'wsl_settings_bouncer_new_users_membership_default_role', "default" );
	} 

	if( ! get_option( 'wsl_settings_bouncer_new_users_passcode_enabled' ) ){ 
		update_option( 'wsl_settings_bouncer_new_users_passcode_enabled', 2 );
	} 

	if( ! get_option( 'wsl_settings_bouncer_new_users_passcode_enabled' ) ){ 
		update_option( 'wsl_settings_bouncer_new_users_passcode_enabled', 2 );
	} 

	if( ! get_option( 'wsl_settings_bouncer_new_users_passcode_text_passcode' ) ){ 
		update_option( 'wsl_settings_bouncer_new_users_passcode_text_passcode', "Passcode" );
	} 

	if( ! get_option( 'wsl_settings_bouncer_new_users_passcode_text_notice' ) ){ 
		update_option( 'wsl_settings_bouncer_new_users_passcode_text_notice', "Please provide the Passcode" );
	} 

	if( ! get_option( 'wsl_settings_bouncer_new_users_passcode_text_error' ) ){ 
		update_option( 'wsl_settings_bouncer_new_users_passcode_text_error', "Passcode is not valid!" );
	} 

	if( ! get_option( 'wsl_settings_bouncer_new_users_passcode_text_submit_button' ) ){ 
		update_option( 'wsl_settings_bouncer_new_users_passcode_text_submit_button', "Continue" );
	} 

	if( ! get_option( 'wsl_settings_bouncer_new_users_agreement_enabled' ) ){ 
		update_option( 'wsl_settings_bouncer_new_users_agreement_enabled', 2 );
	} 

	if( ! get_option( 'wsl_settings_bouncer_agreement_text_submit_button' ) ){ 
		update_option( 'wsl_settings_bouncer_agreement_text_submit_button', "I Agree" );
	} 

	if( ! get_option( 'wsl_settings_bouncer_new_users_restrict_domain_enabled' ) ){ 
		update_option( 'wsl_settings_bouncer_new_users_restrict_domain_enabled', 2 );
	} 

	if( ! get_option( 'wsl_settings_bouncer_new_users_restrict_domain_text_bounce' ) ){ 
		update_option( 'wsl_settings_bouncer_new_users_restrict_domain_text_bounce', "Bouncer says no." );
	}

	if( ! get_option( 'wsl_settings_bouncer_new_users_restrict_email_enabled' ) ){ 
		update_option( 'wsl_settings_bouncer_new_users_restrict_email_enabled', 2 );
	} 

	if( ! get_option( 'wsl_settings_bouncer_new_users_restrict_email_text_bounce' ) ){ 
		update_option( 'wsl_settings_bouncer_new_users_restrict_email_text_bounce', "Bouncer say he refuses." );
	}

	if( ! get_option( 'wsl_settings_bouncer_new_users_restrict_profile_enabled' ) ){ 
		update_option( 'wsl_settings_bouncer_new_users_restrict_profile_enabled', 2 );
	}

	if( ! get_option( 'wsl_settings_bouncer_new_users_restrict_profile_text_bounce' ) ){ 
		update_option( 'wsl_settings_bouncer_new_users_restrict_profile_text_bounce', "Bouncer say only Mundo can go where he pleases!" );
	}

	# contacts import
	if( ! get_option( 'wsl_settings_contacts_import_facebook' ) ){ 
		update_option( 'wsl_settings_contacts_import_facebook', 2 );
	}

	if( ! get_option( 'wsl_settings_contacts_import_google' ) ){ 
		update_option( 'wsl_settings_contacts_import_google', 2 );
	}

	if( ! get_option( 'wsl_settings_contacts_import_twitter' ) ){ 
		update_option( 'wsl_settings_contacts_import_twitter', 2 );
	}

	if( ! get_option( 'wsl_settings_contacts_import_live' ) ){ 
		update_option( 'wsl_settings_contacts_import_live', 2 );
	}

	if( ! get_option( 'wsl_settings_contacts_import_linkedin' ) ){ 
		update_option( 'wsl_settings_contacts_import_linkedin', 2 );
	}
}
