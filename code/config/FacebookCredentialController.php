<?php

/**
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class FacebookCredentialController extends Extension {
    
    private static $allowed_actions = array(
        'FacebookAppId',
        'FacebookRedirectUrl',
        'FacebookScope',
        'FacebookState',
        
    );
    
    public function FacebookAppId(){
        return FacebookAuthRequest::get_facebook_app_id();
    }
    
    public function FacebookRedirectUrl(){
        return FacebookAuthRequest::get_facebook_redirect_url();
    }
    
    public function FacebookScope(){
        return FacebookAuthRequest::get_facebook_scope();
    }
    
    public function FacebookState(){
        return FacebookAuthRequest::State();
    }
    
    public function FacebookConnectUrl(){
        return "https://www.facebook.com/dialog/oauth?client_id={$this->owner->FacebookAppId()}&redirect_uri={$this->owner->FacebookRedirectUrl()}&state={$this->owner->FacebookState()}&response_type=code&scope={$this->owner->FacebookScope()}";
    }
}
