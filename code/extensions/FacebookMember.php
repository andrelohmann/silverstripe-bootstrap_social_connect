<?php

/**
 * Implements a verification email on user registration
 * @module EmailVerifiedMember
 */
class FacebookMember extends DataExtension {
    
    // Extra Statics
    private static $db = array(
        "Email" => "Varchar(255)",
        "FacebookID" => "Varchar(255)"
    );
    
    private static $indexes = array(
        'FacebookID' => true
    );
}