<?php

/**
 * Implements a verification email on user registration
 * @module EmailVerifiedMember
 */
class InstagramMember extends DataExtension {
    
    // Extra Statics
    private static $db = array(
        "Email" => "Varchar(255)",
        "InstagramID" => "Varchar(255)"
    );
    
    private static $indexes = array(
        'InstagramID' => true
    );
}