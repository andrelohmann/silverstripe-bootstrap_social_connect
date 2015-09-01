<?php

/**
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class InstagramCredentialController extends Extension {
    
    private static $allowed_actions = array(
        'InstagramClientId',
        'InstagramRedirectUrl',
        'InstagramScope',
        'InstagramState'
    );
    
    public function InstagramClientId(){
        return InstagramAuthRequest::config()->client_id;
    }
    
    public function InstagramRedirectUrl(){
        return InstagramAuthRequest::config()->redirect_url;
    }
    
    public function InstagramScope(){
        return InstagramAuthRequest::config()->scope;
    }
    
    public function InstagramState(){
        return InstagramAuthRequest::State();
    }
    
    public function InstagramConnectUrl(){
        return "https://api.instagram.com/oauth/authorize/?client_id={$this->owner->InstagramClientId()}&redirect_uri={$this->owner->InstagramRedirectUrl()}&state={$this->owner->InstagramState()}&response_type=code&scope={$this->owner->InstagramScope()}";
    }
}
