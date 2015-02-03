<?php

/**
 * FacebookMember
 */
Member::add_extension('FacebookMember');

Controller::add_extension('FacebookCredentialController');

FacebookAuthRequest::set_facebook_app_id(FACEBOOK_APP_ID);
FacebookAuthRequest::set_facebook_app_secret(FACEBOOK_APP_SECRET);
FacebookAuthRequest::set_facebook_redirect_url(FACEBOOK_REDIRECT_URL);
FacebookAuthRequest::set_facebook_scope(FACEBOOK_SCOPE);
FacebookAuthRequest::set_signup_path(FACEBOOK_SIGNUP_PATH);
FacebookAuthRequest::set_error_path(FACEBOOK_ERROR_PATH);

/**
 * GoogleMember
 */
Member::add_extension('GoogleMember');

Controller::add_extension('GoogleCredentialController');

GoogleAuthRequest::set_google_client_id(GOOGLE_CLIENT_ID);
GoogleAuthRequest::set_google_client_secret(GOOGLE_CLIENT_SECRET);
GoogleAuthRequest::set_google_redirect_url(GOOGLE_REDIRECT_URL);
GoogleAuthRequest::set_google_scope(GOOGLE_SCOPE);
GoogleAuthRequest::set_signup_path(GOOGLE_SIGNUP_PATH);
GoogleAuthRequest::set_error_path(GOOGLE_ERROR_PATH);

/**
 * TwitterMember
 */
Member::add_extension('TwitterMember');

TwitterMember::set_twitter_consumer_key(TWITTER_CONSUMER_KEY);
TwitterMember::set_twitter_consumer_secret(TWITTER_CONSUMER_SECRET);
TwitterMember::set_twitter_callback_url(TWITTER_CALLBACK_URL);
TwitterMember::set_signup_path(TWITTER_SIGNUP_PATH);
TwitterMember::set_error_path(TWITTER_ERROR_PATH);