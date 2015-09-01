<?php

if(defined('FACEBOOK_APP_ID')){

    /**
     * FacebookMember
     */
    Member::add_extension('FacebookMember');

    Controller::add_extension('FacebookCredentialController');
    
    Config::inst()->update('FacebookAuthRequest', 'app_id', FACEBOOK_APP_ID);
    Config::inst()->update('FacebookAuthRequest', 'app_secret', FACEBOOK_APP_SECRET);
    Config::inst()->update('FacebookAuthRequest', 'redirect_url', FACEBOOK_REDIRECT_URL);
    Config::inst()->update('FacebookAuthRequest', 'scope', FACEBOOK_SCOPE);
    Config::inst()->update('FacebookAuthRequest', 'signup_path', FACEBOOK_SIGNUP_PATH);
    Config::inst()->update('FacebookAuthRequest', 'error_path', FACEBOOK_ERROR_PATH);
}

if(defined('GOOGLE_CLIENT_ID')){

    /**
     * GoogleMember
     */
    Member::add_extension('GoogleMember');

    Controller::add_extension('GoogleCredentialController');

    Config::inst()->update('GoogleAuthRequest', 'client_id', GOOGLE_CLIENT_ID);
    Config::inst()->update('GoogleAuthRequest', 'client_secret', GOOGLE_CLIENT_SECRET);
    Config::inst()->update('GoogleAuthRequest', 'redirect_url', GOOGLE_REDIRECT_URL);
    Config::inst()->update('GoogleAuthRequest', 'scope', GOOGLE_SCOPE);
    Config::inst()->update('GoogleAuthRequest', 'signup_path', GOOGLE_SIGNUP_PATH);
    Config::inst()->update('GoogleAuthRequest', 'error_path', GOOGLE_ERROR_PATH);
}

if(defined('TWITTER_CONSUMER_KEY')){

    /**
     * TwitterMember
     */
    Member::add_extension('TwitterMember');

    TwitterMember::set_twitter_consumer_key(TWITTER_CONSUMER_KEY);
    TwitterMember::set_twitter_consumer_secret(TWITTER_CONSUMER_SECRET);
    TwitterMember::set_twitter_callback_url(TWITTER_CALLBACK_URL);
    TwitterMember::set_signup_path(TWITTER_SIGNUP_PATH);
    TwitterMember::set_error_path(TWITTER_ERROR_PATH);
}

if(defined('INSTAGRAM_CLIENT_ID')){

    /**
     * GoogleMember
     */
    Member::add_extension('InstagramMember');

    Controller::add_extension('InstagramCredentialController');

    Config::inst()->update('InstagramAuthRequest', 'client_id', INSTAGRAM_CLIENT_ID);
    Config::inst()->update('InstagramAuthRequest', 'client_secret', INSTAGRAM_CLIENT_SECRET);
    Config::inst()->update('InstagramAuthRequest', 'redirect_url', INSTAGRAM_REDIRECT_URL);
    Config::inst()->update('InstagramAuthRequest', 'scope', INSTAGRAM_SCOPE);
    Config::inst()->update('InstagramAuthRequest', 'signup_path', INSTAGRAM_SIGNUP_PATH);
    Config::inst()->update('InstagramAuthRequest', 'error_path', INSTAGRAM_ERROR_PATH);
}