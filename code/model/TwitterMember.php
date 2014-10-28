<?php

/**
 * Implements a verification email on user registration
 * @module EmailVerifiedMember
 */
class TwitterMember extends DataExtension {
    
    private static $twitter_consumer_key = null;
    
    private static $twitter_consumer_secret = null;
    
    private static $twitter_callback_url = null;
    
    private static $twitter_signup_path = null;
    
    private static $twitter_error_path = null;
    
    public static function set_twitter_consumer_key($key){
        self::$twitter_consumer_key = $key;
    }
    
    public static function get_twitter_consumer_key(){
        return self::$twitter_consumer_key;
    }
    
    public static function set_twitter_consumer_secret($secret){
        self::$twitter_consumer_secret = $secret;
    }
    
    public static function get_twitter_consumer_secret(){
        return self::$twitter_consumer_secret;
    }
    
    public static function set_twitter_callback_url($url){
        self::$twitter_callback_url = $url;
    }
    
    public static function get_twitter_callback_url(){
        return self::$twitter_callback_url;
    }
    
    public static function set_signup_path($path){
        self::$twitter_signup_path = $path;
    }
    
    public static function get_signup_path(){
        return self::$twitter_signup_path;
    }
    
    public static function set_error_path($path){
        self::$twitter_error_path = $path;
    }
    
    public static function get_error_path(){
        return self::$twitter_error_path;
    }
    
    // Extra Statics
    private static $db = array(
        "Email" => "Varchar(255)",
        "TwitterID" => "Varchar(255)"
    );
    
    private static $indexes = array(
        'TwitterID' => true
    );
}