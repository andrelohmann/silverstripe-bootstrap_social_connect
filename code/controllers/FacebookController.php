<?php
/**
 * Implements a basic Controller
 * @package some config
 * http://doc.silverstripe.org/framework/en/3.1/topics/controller
 */
class FacebookController extends Controller {
	
	public static $url_topic = 'socialconnect';
	
	public static $url_segment = 'facebook';
	
	private static $allowed_actions = array( 
            'auth',
            'signup',
            'emailexists',
            'error'
	);
	
	public static $template = 'BlankPage';
	
	/**
	 * Template thats used to render the pages.
	 *
	 * @var string
	 */
	public static $template_main = 'Page';

	/**
	 * Returns a link to this controller.  Overload with your own Link rules if they exist.
	 */
	public function Link() {
		return self::$url_segment .'/';
	}
	
	/**
	 * Initialise the controller
	 */
	public function init() {
        parent::init();
            
        if(!defined('FACEBOOK_APP_ID')) return $this->httpError(404, _t('FacebookConnect.ERRORUNAVAILABLE', 'FacebookConnect.ERRORUNAVAILABLE'));
 	}
        
	public function auth(){

		if(Member::currentUser()) return $this->redirect(Security::config()->default_login_dest);

		// Access Denied
		if(isset($_GET['error'])){
			// possible access_denied_error
			return $this->redirect(FacebookAuthRequest::config()->error_path);
		}else if(isset($_GET['code']) && isset($_GET['state']) && FacebookAuthRequest::State($_GET['state'])){
			// CODE auswerten
			$result = FacebookAuthRequest::ExchangeAccessToken($_GET['code']);
			if(stristr($result, 'access_token')){
				$result = explode('&', $result);
				$token = explode("=", $result[0]);
				if($token[0] == 'access_token'){
					$access_token = $token[1];

					$result = json_decode(FacebookAuthRequest::run_curl('https://graph.facebook.com/me?fields='.Config::inst()->get('FacebookAuthRequest', 'fields').'&appsecret_proof='.hash_hmac('sha256', $access_token, Config::inst()->get('FacebookAuthRequest', 'app_secret')).'&access_token='.$access_token), true);
					if(isset($result['id']) && isset($result['name']) && isset($result['email']) && !isset($result['error'])){
						// Facebook Login successfull
						// fetch user by Facebook ID
						if($o_Member = Member::get()->filter(array('FacebookID' => $result['id']))->First()){
							// facebook member found
							// login and redirect
							$o_Member->logIn();
							return $this->redirect(Security::config()->default_login_dest);
						}else{
							// facebook member not found

							// save session data and redirect
							Session::set('FacebookUserData', $result);

							// check if Email allready exists in the system
							if($o_Member = Member::get()->filter(array('Email' => $result['email']))->First()){
								return $this->redirect(FacebookAuthRequest::config()->emailexists_path);
							}else{
								return $this->redirect(FacebookAuthRequest::config()->signup_path);
							}
						}
					}else{
						// Login unsuccessfull
						return $this->redirect(FacebookAuthRequest::config()->error_path);
					}
				}else{
					// acesstoken return value has changed
					return $this->redirect(FacebookAuthRequest::config()->error_path);
				}
			}else{
				// no accesstoken returned
				// Code invalid
				return $this->redirect(FacebookAuthRequest::config()->error_path);
			}
		}else{
			// state token unavailable
			return $this->redirect(FacebookAuthRequest::config()->error_path);
		}
	}

	public function signup(){

		if(Member::currentUser()) return $this->redirect(Security::config()->default_login_dest);

		// Signup nur zulassen, wennFacebookUserData Session gesetzt wurde
		if(!$user = Session::get('FacebookUserData')) return $this->redirect(FacebookAuthRequest::config()->error_path);

		$o_Member = new Member();

		$o_Member->SocialConnectType = 'facebook';

		$o_Member->FirstName = $user['name'];

		$o_Member->FacebookID = $user['id'];

		$o_Member->Email = $user['email'];

		// if EmailVerifiedMember Module is used
		if(class_exists('EmailVerifiedMember')) {
			Config::inst()->update('Member', 'deactivate_send_validation_mail', false);
			$o_Member->Verified = true;
			$o_Member->VerificationEmailSent = true;
			Config::inst()->update('Member', 'deactivate_send_validation_mail', true);
			$o_Member->write();
			Config::inst()->update('Member', 'deactivate_send_validation_mail', false);
		}else{
			$o_Member->write();
		}

		$o_Member->logIn();

		Session::clear('FacebookUserData');

		return $this->redirect(Security::config()->default_login_dest);
	}

	public function emailexists(){

		if(Member::currentUser()) return $this->redirect(Security::config()->default_login_dest);

		// Signup nur zulassen, wennFacebookUserData Session gesetzt wurde
		if(!$user = Session::get('FacebookUserData')) return $this->redirect(FacebookAuthRequest::config()->error_path);
		
		if(!$o_Member = Member::get()->filter(array('Email' => $user['email']))->First()) return $this->redirect(FacebookAuthRequest::config()->error_path);

		Session::clear('FacebookUserData');
		
		return $this->customise(new ArrayData(array(
            'Member' => $o_Member
        )))->renderWith(
            array('Facebook_emailexists', 'Facebook', $this->stat('template_main'), $this->stat('template'))
        );
	}
        
	/**
	 * Show the registration form
	 */
	public function error() {
            
        return $this->customise(new ArrayData(array(
            'Title' => _t('FacebookConnect.ERRORTITLE', 'FacebookConnect.ERRORTITLE'),
            'Content' => _t('FacebookConnect.ERRORCONTENT', 'FacebookConnect.ERRORCONTENT')
        )))->renderWith(
            array('Facebook_error', 'Facebook', $this->stat('template_main'), $this->stat('template'))
        );
	}
}
