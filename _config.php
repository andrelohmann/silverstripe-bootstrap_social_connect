<?php

/**
 * FacebookMember
 */
Member::add_extension('FacebookMember');

Controller::add_extension('FacebookCredentialController');

/**
 * Exampke Configs

FacebookAuthRequest::set_facebook_app_id('1234567890');
FacebookAuthRequest::set_facebook_app_secret('1234567890');
FacebookAuthRequest::set_facebook_redirect_url('http://YOURDOMAIN/facebook/auth');
//https://developers.facebook.com/docs/reference/login/#permissions
FacebookAuthRequest::set_facebook_scope('email,user_about_me,user_birthday');
FacebookAuthRequest::set_signup_path('facebook/signup');
FacebookAuthRequest::set_error_path('facebook/error');

 */

/**
 * GoogleMember
 */
Member::add_extension('GoogleMember');

Controller::add_extension('GoogleCredentialController');

/**
 * Exampke Configs

GoogleAuthRequest::set_google_client_id('1234567890');
GoogleAuthRequest::set_google_client_secret('1234567890');
GoogleAuthRequest::set_google_redirect_url('http://YOURDOMAIN/google/auth');
GoogleAuthRequest::set_google_scope('https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile');
GoogleAuthRequest::set_signup_path('google/signup');
GoogleAuthRequest::set_error_path('googles/error');

 */

/**
 * GoogleMember
 */
Member::add_extension('TwitterMember');

/**
 * Exampke Configs

TwitterAuthRequest::set_twitter_consumer_key('1234567890');
TwitterAuthRequest::set_twitter_consumer_secret('1234567890');
TwitterAuthRequest::set_twitter_callback_url('http://YOURDOMAIN/google/auth');
TwitterMember::set_signup_path('twitter/signup');
TwitterMember::set_error_path('twitter/error');

 */