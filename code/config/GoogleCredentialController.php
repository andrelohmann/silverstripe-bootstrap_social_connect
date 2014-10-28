<?php

/**
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class GoogleCredentialController extends Extension {
    
    private static $allowed_actions = array(
        'GoogleClientId',
        'GoogleRedirectUrl',
        'GoogleScope',
        'GoogleState'
    );
    
    public function GoogleClientId(){
        return GoogleAuthRequest::get_google_client_id();
    }
    
    public function GoogleRedirectUrl(){
        return GoogleAuthRequest::get_google_redirect_url();
    }
    
    public function GoogleScope(){
        return GoogleAuthRequest::get_google_scope();
    }
    
    public function GoogleState(){
        return GoogleAuthRequest::State();
    }
    
    public function GoogleConnectUrl(){
        return "https://accounts.google.com/o/oauth2/auth?client_id={$this->owner->GoogleClientId()}&redirect_uri={$this->owner->GoogleRedirectUrl()}&state={$this->owner->GoogleState()}&response_type=code&scope={$this->owner->GoogleScope()}";
    }
}
