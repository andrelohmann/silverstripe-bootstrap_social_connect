<?php

/**
 * Implements a verification email on user registration
 * @module EmailVerifiedMember
 */
class GoogleMember extends DataExtension {
    
    // Extra Statics
    private static $db = array(
        "Email" => "Varchar(255)",
        "GoogleID" => "Varchar(255)"
    );
    
    private static $indexes = array(
        'GoogleID' => true
    );
}