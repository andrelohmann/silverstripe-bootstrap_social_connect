<?php
/**
 * Implements a basic Controller
 * @package some config
 * http://doc.silverstripe.org/framework/en/3.1/topics/controller
 */
class InstagramController extends Controller {
	
	public static $url_topic = 'socialconnect';
	
	public static $url_segment = 'instagram';
	
	private static $allowed_actions = array( 
            'auth',
            'signup',
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
	 * Initialise the controller
	 */
	public function init() {
            parent::init();
            
            if(!defined('INSTAGRAM_CLIENT_ID')) return $this->httpError(404, _t('InstagramConnect.ERRORUNAVAILABLE', 'InstagramConnect.ERRORUNAVAILABLE'));
 	}
        
        public function auth(){
            
            if(Member::currentUser()) return $this->redirect(Security::default_login_dest());
            
            // Access Denied
            if(isset($_GET['error'])){
                // possible access_denied_error
                $this->redirect(InstagramAuthRequest::get_error_path());
            }else if(isset($_GET['code']) && isset($_GET['state']) && InstagramAuthRequest::State($_GET['state'])){
                // CODE auswerten
                $result = json_decode(InstagramAuthRequest::ExchangeAccessToken($_GET['code']), true);
                if(isset($result['access_token']) && isset($result['user']) && isset($result['user']['id']) && !isset($result['error'])){
                    
                    // Instagram Login successfull
                    // fetch user by Instagram ID
                    if($o_Member = Member::get()->filter(array('InstagramID' => $result['user']['id']))->First()){
                        // instagram member found
                        // login and redirect
                        $o_Member->logIn();
                        $this->redirect(Security::default_login_dest());
                    }else{
                        // instagram member not found
                        // save session data and redirect to signup
                        Session::set('InstagramUserData', $result['user']);
                        $this->redirect(InstagramAuthRequest::get_signup_path());
                    }
                }else{
                    // no accesstoken returned
                    // Code invalid
                    $this->redirect(InstagramAuthRequest::get_error_path());
                }
            }else{
                // state token unavailable
                $this->redirect(InstagramAuthRequest::get_error_path());
            }
        }
        
        public function signup(){
            
            if(Member::currentUser()) return $this->redirect(Security::default_login_dest());
            
            // Signup nur zulassen, wennFacebookUserData Session gesetzt wurde
            if(!$user = Session::get('InstagramUserData')) return $this->redirect(InstagramAuthRequest::get_error_path());
            
            $o_Member = new Member();
            
            $o_Member->FirstName = $user['username'];
            
            $o_Member->InstagramID = $user['id'];
            
            $o_Member->Email = $user['username']."@instagram.com";
            
            // if EmailVerifiedMember Module is used
            if(class_exists('EmailVerifiedMember')) {
                EmailVerifiedMember::set_deactivate_send_validation_mail(false);
                $o_Member->Verified = true;
                $o_Member->VerificationEmailSent = true;
                EmailVerifiedMember::set_deactivate_send_validation_mail(true);
                $o_Member->write();
                EmailVerifiedMember::set_deactivate_send_validation_mail(false);
            }else{
                $o_Member->write();
            }
            
            $o_Member->logIn();
            
            Session::clear('InstagramUserData');
            
            $this->redirect(Security::default_login_dest());
	}

	/**
	 * Show the registration form
	 */
	public function error() {
            
            return $this->customise(new ArrayData(array(
                'Title' => _t('InstagramConnect.ERRORTITLE', 'InstagramConnect.ERRORTITLE'),
                'Content' => _t('InstagramConnect.ERRORCONTENT', 'InstagramConnect.ERRORCONTENT')
            )))->renderWith(
                array('Instagram_error', 'Instagram', 'Page', $this->stat('template_main'), 'BlankPage')
            );
	}
}
