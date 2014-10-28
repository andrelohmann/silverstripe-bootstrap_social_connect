<?php
/**
 * Implements a basic Controller
 * @package some config
 * http://doc.silverstripe.org/framework/en/3.1/topics/controller
 */
class TwitterController extends Page_Controller {
	
	private static $url_segment = 'twitter';
	
	private static $allowed_actions = array(
            'login',
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
        
        // creating the oath tokens and redirecting to the twitter auth page
        public function login(){
            
            if(Member::currentUser()) return $this->redirect(Security::default_login_dest());
            
            try{
                $connection = new TwitterOAuth(TwitterMember::get_twitter_consumer_key(), TwitterMember::get_twitter_consumer_secret());
                $temporary_credentials = $connection->getRequestToken(TwitterMember::get_twitter_callback_url());
                
                /* Save temporary credentials to session. */
                Session::set('oauth_token', $temporary_credentials['oauth_token']);
                Session::set('oauth_token_secret', $temporary_credentials['oauth_token_secret']);
                
                /* If last connection failed don't display authorization link. */
                switch ($connection->http_code) {
                    case 200:
                        /* Build authorize URL and redirect user to Twitter. */
                        $redirect_url = $connection->getAuthorizeURL($temporary_credentials['oauth_token']);
                        $this->redirect($redirect_url);
                    break;
                
                    default:
                        /* Show notification if something went wrong. */
                        Session::clear('oauth_token');
                        Session::clear('oauth_token_secret');
                        $this->redirect(TwitterMember::get_error_path());
                    break;
                }
            }catch(Exception $e){
                Session::clear('oauth_token');
                Session::clear('oauth_token_secret');
                $this->redirect(TwitterMember::get_error_path());
            }
        }
        
        public function auth(){
            
            if(Member::currentUser()) return $this->redirect(Security::default_login_dest());
            
            /* check If the oauth_token is old */
            if (isset($_REQUEST['oauth_token']) && $_SESSION['oauth_token'] !== $_REQUEST['oauth_token']){
                Session::clear('oauth_token');
                Session::clear('oauth_token_secret');
                $this->redirect(TwitterMember::get_error_path());
            }else{
                try{
                    $connection = new TwitterOAuth(TwitterMember::get_twitter_consumer_key(), TwitterMember::get_twitter_consumer_secret(), Session::get('oauth_token'), Session::get('oauth_token_secret'));
                    $token_credentials = $connection->getAccessToken($_REQUEST['oauth_verifier']);
            
                    $connection = new TwitterOAuth(TwitterMember::get_twitter_consumer_key(), TwitterMember::get_twitter_consumer_secret(), $token_credentials['oauth_token'], $token_credentials['oauth_token_secret']);
                
                    $account = $connection->get('account/verify_credentials');
                
                    if(isset($account->id)){
                        // Twitter Login successfull
                        // fetch user by Twitter ID
                        if($o_Member = Member::get()->filter(array('TwitterID' => $account->id))->First()){
                            
                            // twitter member found
                            // login and redirect
                            $o_Member->logIn();
                            $this->redirect(Security::default_login_dest());
                        }else{
                            // twitter member not found
                            // save session data and redirect to twitter
                            Session::set('TwitterUserData', $account);
                            $this->redirect(TwitterMember::get_signup_path());
                        }
                    }else{
                        // acesstoken return value has changed
                        Session::clear('oauth_token');
                        Session::clear('oauth_token_secret');
                        $this->redirect(TwitterMember::get_error_path());
                    }
                }catch(Exception $e){
                    Session::clear('oauth_token');
                    Session::clear('oauth_token_secret');
                    $this->redirect(TwitterMember::get_error_path());
                }
            }
        }

	/**
	 * Show the registration form
	 */
	public function signup() { // Signup Step 1
            
            if(Member::currentUser()) return $this->redirect(Security::default_login_dest());
            
            // Signup nur zulassen, wenn TwitterUserData Session gesetzt wurde
            if(!$user = Session::get('TwitterUserData')) return $this->redirect(TwitterMember::get_error_path());
            
            $o_Member = new Member();
            
            $o_Member->FirstName = $user->screen_name;
            
            $o_Member->TwitterID = $user->id;
            
            $o_Member->Email = $user->screen_name."@twitter.com"; // Twitter Users got no Email Adress
            
            // if EmailVerifiedMember Module is used
            //EmailVerifiedMember::set_deactivate_send_validation_mail(false);
            //$o_Member->Verified = true;
            //$o_Member->VerificationEmailSent = true;
            //EmailVerifiedMember::set_deactivate_send_validation_mail(true);
            $o_Member->write();
            //EmailVerifiedMember::set_deactivate_send_validation_mail(false);
            
            $o_Member->logIn();
            
            Session::clear('TwitterUserData');
            
            $this->redirect(Security::default_login_dest());
	}

	/**
	 * Show the registration form
	 */
	public function error() {
            
            $tmpPage = new Page();
            $tmpPage->Title = _t('TwitterConnect.ERRORTITLE', 'TwitterConnect.ERRORTITLE');
            $tmpPage->URLSegment = $this->stat('url_segment');
            $tmpPage->ID = -1; // Set the page ID to -1 so we dont get the top level pages as its children
            $controller = new Page_Controller($tmpPage);
            $controller->init();

            $customisedController = $controller->customise(array(
                'Title' => _t('TwitterConnect.ERRORTITLE', 'TwitterConnect.ERRORTITLE'),
                'Content' => _t('TwitterConnect.ERRORCONTENT', 'TwitterConnect.ERRORCONTENT')
            ));
            return $customisedController->renderWith(
                array('Twitter_error', 'Twitter', 'Page', $this->stat('template_main'), 'BlankPage')
            );
	}
}
