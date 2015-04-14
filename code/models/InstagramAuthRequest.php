<?php

/**
 * InstagramAuthRequest
 */
class InstagramAuthRequest extends DataObject {
    
    private static $instagram_client_id = null;
    
    private static $instagram_client_secret = null;
    
    private static $instagram_redirect_url = null;
    
    private static $instagram_scope = null;
    
    private static $instagram_signup_path = null;
    
    private static $instagram_error_path = null;
    
    public static function set_instagram_client_id($id){
        self::$instagram_client_id = $id;
    }
    
    public static function get_instagram_client_id(){
        return self::$instagram_client_id;
    }
    
    public static function set_instagram_client_secret($secret){
        self::$instagram_client_secret = $secret;
    }
    
    public static function get_instagram_client_secret(){
        return self::$instagram_client_secret;
    }
    
    public static function set_instagram_redirect_url($url){
        self::$instagram_redirect_url = $url;
    }
    
    public static function get_instagram_redirect_url(){
        return self::$instagram_redirect_url;
    }
    
    public static function set_instagram_scope($scope){
        self::$instagram_scope = $scope;
    }
    
    public static function get_instagram_scope(){
        return self::$instagram_scope;
    }
    
    public static function set_signup_path($path){
        self::$instagram_signup_path = $path;
    }
    
    public static function get_signup_path(){
        return self::$instagram_signup_path;
    }
    
    public static function set_error_path($path){
        self::$instagram_error_path = $path;
    }
    
    public static function get_error_path(){
        return self::$instagram_error_path;
    }
    
    private static $EXCHANGE_ACCESS_TOKEN_URL = 'https://api.instagram.com/oauth/access_token';
    
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
            if(InstagramAuthRequest::get()->filter(array("StateToken" => $Token))->First()) return true;
            else return false;
        }else{
            
            $o_StateToken = new InstagramAuthRequest();
            
            do{
                $o_StateToken->StateToken = md5(microtime().base64_encode(pack('N6', mt_rand(), mt_rand(), mt_rand(), mt_rand(), mt_rand(), uniqid())));
            }while(InstagramAuthRequest::State($o_StateToken->StateToken));
            
            $o_StateToken->write();
            
            return $o_StateToken->StateToken;
        }
    }
    
    /**
     * Delete old states
     */
    private static function cleanup(){
        // delete all old state tokens
        foreach(InstagramAuthRequest::get()->Where("Created < '".date ("Y-m-d H:i:s", strtotime("- ".InstagramAuthRequest::get_calc_state_lifetime()." seconds"))."'") as $o_ST){
            $o_ST->delete();
        }
    }
    
    public static function ExchangeAccessToken($code){
        return self::run_curl(self::$EXCHANGE_ACCESS_TOKEN_URL, 'POST', "client_id=".self::get_instagram_client_id()."&redirect_uri=".self::get_instagram_redirect_url()."&client_secret=".self::get_instagram_client_secret()."&code=".$code."&grant_type=authorization_code");
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