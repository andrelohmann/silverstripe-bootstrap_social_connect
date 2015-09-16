<?php

/**
 * Implements a verification email on user registration
 * @module EmailVerifiedMember
 */
class SocialConnectMember extends DataExtension {
    
    // Extra Statics
    private static $db = array(
        "SocialConnectType" => "Enum('email,facebook,google,instagram,twitter','email')"
    );
}