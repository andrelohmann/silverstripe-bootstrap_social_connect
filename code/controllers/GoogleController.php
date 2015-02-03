<?php
/**
 * Implements a basic Controller
 * @package some config
 * http://doc.silverstripe.org/framework/en/3.1/topics/controller
 */
class GoogleController extends Controller {
	
	private static $url_segment = 'google';
	
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
 	}
        
        public function auth(){
            
            if(Member::currentUser()) return $this->redirect(Security::default_login_dest());
            
            // Access Denied
            if(isset($_GET['error'])){
                // possible access_denied_error
                $this->redirect(GoogleAuthRequest::get_error_path());
            }else if(isset($_GET['code']) && isset($_GET['state']) && GoogleAuthRequest::State($_GET['state'])){
                // CODE auswerten
                $result = json_decode(GoogleAuthRequest::ExchangeAccessToken($_GET['code']), true);
                if(isset($result['access_token'])){
                    $result = json_decode(GoogleAuthRequest::run_curl('https://www.googleapis.com/oauth2/v1/userinfo?access_token='.$result['access_token']), true);
                    if(isset($result['id']) && !isset($result['error'])){
                        // Google Login successfull
                        // fetch user by Google ID
                        if($o_Member = Member::get()->filter(array('GoogleID' => $result['id']))->First()){
                            
                            // google member found
                            // login and redirect
                            $o_Member->logIn();
                            $this->redirect(Security::default_login_dest());
                        }else{
                            // google member not found
                            // save session data and redirect to google
                            Session::set('GoogleUserData', $result);
                            $this->redirect(GoogleAuthRequest::get_signup_path());
                        }
                    }else{
                        // acesstoken return value has changed
                        $this->redirect(GoogleAuthRequest::get_error_path());
                    }
                }else{
                    // no accesstoken returned
                    // Code invalid
                    $this->redirect(GoogleAuthRequest::get_error_path());
                }
            }else{
                // state token unavailable
                $this->redirect(GoogleAuthRequest::get_error_path());
            }
        }
        
        public function signup(){
            
            if(Member::currentUser()) return $this->redirect(Security::default_login_dest());
            
            // Signup nur zulassen, wennFacebookUserData Session gesetzt wurde
            if(!$user = Session::get('GoogleUserData')) return $this->redirect(GoogleAuthRequest::get_error_path());
            
            $o_Member = new Member();
            
            $o_Member->FirstName = $user['name'];
            
            $o_Member->GoogleID = $user['id'];
            
            $o_Member->Email = $user['email'];
            
            // if EmailVerifiedMember Module is used
            //EmailVerifiedMember::set_deactivate_send_validation_mail(false);
            //$o_Member->Verified = true;
            //$o_Member->VerificationEmailSent = true;
            //EmailVerifiedMember::set_deactivate_send_validation_mail(true);
            $o_Member->write();
            //EmailVerifiedMember::set_deactivate_send_validation_mail(false);
            
            $o_Member->logIn();
            
            Session::clear('GoogleUserData');
            
            $this->redirect(Security::default_login_dest());
	}

	/**
	 * Show the registration form
	 */
	public function error() {
            
            return $this->customise(new ArrayData(array(
                'Title' => _t('GoogleConnect.ERRORTITLE', 'GoogleConnect.ERRORTITLE'),
                'Content' => _t('GoogleConnect.ERRORCONTENT', 'GoogleConnect.ERRORCONTENT')
            )))->renderWith(
                array('Google_error', 'Google', 'Page', $this->stat('template_main'), 'BlankPage')
            );
	}
}
