<?php

/**
 * FacebookAuthRequest
 */
class FacebookAuthRequest extends DataObject {
    
    private static $facebook_app_id = null;
    
    private static $facebook_app_secret = null;
    
    private static $facebook_redirect_url = null;
    
    private static $facebook_scope = null;
    
    private static $facebook_signup_path = null;
    
    private static $facebook_error_path = null;
    
    public static function set_facebook_app_id($id){
        self::$facebook_app_id = $id;
    }
    
    public static function get_facebook_app_id(){
        return self::$facebook_app_id;
    }
    
    public static function set_facebook_app_secret($secret){
        self::$facebook_app_secret = $secret;
    }
    
    public static function get_facebook_app_secret(){
        return self::$facebook_app_secret;
    }
    
    public static function set_facebook_redirect_url($url){
        self::$facebook_redirect_url = $url;
    }
    
    public static function get_facebook_redirect_url(){
        return self::$facebook_redirect_url;
    }
    
    public static function set_facebook_scope($scope){
        self::$facebook_scope = $scope;
    }
    
    public static function get_facebook_scope(){
        return self::$facebook_scope;
    }
    
    public static function set_signup_path($path){
        self::$facebook_signup_path = $path;
    }
    
    public static function get_signup_path(){
        return self::$facebook_signup_path;
    }
    
    public static function set_error_path($path){
        self::$facebook_error_path = $path;
    }
    
    public static function get_error_path(){
        return self::$facebook_error_path;
    }
    
    private static $EXCHANGE_ACCESS_TOKEN_URL = 'https://graph.facebook.com/oauth/access_token?';
    
    // State Token Lifetime in Seconds
    private static $DEFAULT_STATE_LIFETIME = 900;
    private static $DEFAULT_STATE_LIFETIME_BUFFER = 10; // 10 seconds
    
    public static function set_state_lifetime($lifetime){
        self::$DEFAULT_STATE_LIFETIME = $lifetime;
    }
    
    public static function get_state_lifetime(){
        return self::$DEFAULT_STATE_LIFETIME;
    }
    
    public static function get_calc_state_lifetime(){
        return (self::$DEFAULT_STATE_LIFETIME + self::$DEFAULT_STATE_LIFETIME_BUFFER);
    }
    
    private static $db = array(
        'StateToken' => 'Varchar(32)'
    );
    
    private static $indexes = array(
        'StateToken' => true
    );
    
    public static function State($Token = false){
            
        self::cleanup();
        
        if($Token){
            if(FacebookAuthRequest::get()->filter(array("StateToken" => $Token))->First()) return true;
            else return false;
        }else{
            
            $o_StateToken = new FacebookAuthRequest();
            
            do{
                $o_StateToken->StateToken = md5(microtime().base64_encode(pack('N6', mt_rand(), mt_rand(), mt_rand(), mt_rand(), mt_rand(), uniqid())));
            }while(FacebookAuthRequest::State($o_StateToken->StateToken));
            
            $o_StateToken->write();
            
            return $o_StateToken->StateToken;
        }
    }
    
    /**
     * Delete old states
     */
    private static function cleanup(){
        // delete all old state tokens
        foreach(FacebookAuthRequest::get()->Where("Created < '".date ("Y-m-d H:i:s", strtotime("- ".FacebookAuthRequest::get_calc_state_lifetime()." seconds"))."'") as $o_ST){
            $o_ST->delete();
        }
    }
    
    public static function ExchangeAccessToken($code){
        return self::run_curl(self::$EXCHANGE_ACCESS_TOKEN_URL."client_id=".self::get_facebook_app_id()."&redirect_uri=".self::get_facebook_redirect_url()."&client_secret=".self::get_facebook_app_secret()."&code=".$code);
    }
    
    public static function run_curl($url, $method = 'GET', $postvars = null){
        $ch = curl_init($url);
        
        // GET
        if($method == 'GET'){
            $options = array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => 1
            );
            curl_setopt_array($ch, $options);
        }else{
            $options = array(
                CURLOPT_URL => $url,
                CURLOPT_POST => 1,
                CURLOPT_POSTFIELDS => $postvars,
                CURLOPT_RETURNTRANSFER => 1
            );
            curl_setopt_array($ch, $options);
        }
    
        $response = curl_exec($ch);
        curl_close($ch);
    
        return $response;
    }
            
}