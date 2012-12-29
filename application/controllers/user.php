<?php

	/**
	 * user controller
	 * action methods for all user/* urls
	 */
	class user extends controller {
		/**
		 * removes the previous session and makes user ready to login
		 */
		public function get_login() {
			// load and validate session data
			statics::requireAuthentication(0);

			// removes user information from the session
			session::remove('user');
			statics::$user = null;

			// redirect user to homepage
			mvc::redirect('home/index');
		}

		/**
		 * ajax-enabled login procedure
		 * gets email and password as parameters to
		 * log the user into the system
		 */
		public function postajax_login() {
			// load and validate session data - not necessary on this stage
			// statics::requireAuthentication(0);

			// construct values from the request
			$tInput = http::postArray(['email', 'password']);

			// validate the request: login credentials
			contracts::isEmail($tInput['email'])->exception('invalid e-mail address input');
			contracts::isRequired($tInput['password'])->exception('password left empty');

			// gather all user data from model
			$this->load('userModel');
			$tUser = $this->userModel->getByEmail($tInput['email']);

			// check password, throw an exception if it's incorrect.
			if($tUser === false || strcmp($tInput['password'], $tUser['password']) != 0) {
				throw new Exception('no such user or password incorrect.');
			}

			// assign the user data to view
			$this->set('user', $tUser);

			// assign the user data to session
			session::set('user', $tUser);
			statics::$user = &$tUser;
			
			// render the page
			$this->json();
		}

		/**
		 * communicates the facebook by using its opengraph api
		 * to log the user in or register the user into the system
		 */
		public function get_fblogin() {
			// load and validate session data
			statics::requireAuthentication(0);

			// load the facebook api
			fb::loadApi();

			// if it's not a callback from facebook
			if(!isset($_GET['state'])) {
				// get the facebook login url to redirect user
				$tLoginUrl = fb::getLoginUrl('email', fb::$appRedirectUri);

				// redirect user then terminate the execution of php
				http::sendRedirect($tLoginUrl);
			}

			// if it's not a valid user, throw an exception here
			if(fb::$userId <= 0) {
				throw new Exception('Facebook login error.');
			}

			// get user details
			$tUser = fb::get('/me', false);

			// don't accept the user if it's not a verified facebook user
			// in case of it might be a bot or somekind of fraud
			if(!$tUser->object['verified']) {
				throw new Exception('Facebook account is not verified.');
			}

			// try to merge or update user account information if it points to
			// an existing user in our user database
			$tRealUser = $this->tryMergeAccountWithFacebook($tUser);
			if($tRealUser === false) {
				// if there is no such user, just register it
				$tRealUser = $this->registerWithFacebook($tUser);
			}

			// assign the user data to view
			$this->set('user', $tRealUser);

			// assign the user data to session
			session::set('user', $tRealUser);
			statics::$user = &$tRealUser;

			//TODO: flash notification

			// redirect user to homepage
			mvc::redirect('home/index');
		}

		/**
		 * tries to merge existing user information with
		 * facebook's user data
		 */
		private function &tryMergeAccountWithFacebook($uUser) {
			// gather all user data from model
			$this->load('userModel');
			$tRealUser = $this->userModel->getByEmailOrFacebookId($uUser->object['email'], $uUser->object['id']);

			// if user does not exist, return false
			if($tRealUser === false) {
				return $tRealUser; // returned the variable's itself since method should return a reference
			}

			// update user record
			$this->userModel->update(
				$tRealUser['userid'],
				[
					'displayname' => $uUser->object['name'],
					'email' => $uUser->object['email'],
					'facebookid' => $uUser->object['id']
				]
			);

			return $tRealUser;
		}

		/**
		 * registers the user into system with facebook user details
		 */
		private function &registerWithFacebook($uUser) {
			// construct values for the record
			$tRealUser = [
				'userid' => string::generateUuid(),
				'displayname' => $uUser->object['name'],
				'firstname' => $uUser->object['first_name'],
				'lastname' => $uUser->object['last_name'],
				'logo' => '', // facebook profile picture - https://graph.facebook.com/hasan.atbinici/picture
				'email' => $uUser->object['email'],
				'phonenumber' => '',
				'password' => string::generatePassword(6),
				'facebookid' => $uUser->object['id'],
				'languageid' => 'en'
			];

			// insert the constructed record into the database
			$this->load('userModel');
			$this->userModel->insert($tRealUser);

			// send an e-mail - apply template file
			$tHtmlBody = statics::emailTemplate('res/mailtemplates/registerFacebook.htm', $tRealUser);

			// send an e-mail
			$tNewMail = new mail();
			$tNewMail->to = $tRealUser['email'];
			$tNewMail->from = 'info@survey-e-bot.com';
			$tNewMail->subject = 'Your survey-e-bot account';
			$tNewMail->headers['Content-Type'] = 'text/html; charset=utf-8';
			$tNewMail->content = $tHtmlBody;
			$tNewMail->send();

			return $tRealUser;
		}

		/**
		 * registration page
		 */
		public function get_register() {
			// load and validate session data - only guests
			statics::requireAuthentication(-1);

			// render the page
			$this->view();
		}

		/**
		 * postback method for registration page
		 */
		public function post_register() {
			// load and validate session data - only guests
			statics::requireAuthentication(-1);

			// construct values for the record
			$tUser = http::postArray(['displayname', 'firstname', 'lastname', 'phonenumber', 'email', 'password']);
			$tUser['userid'] = string::generateUuid();
			$tUser['logo'] = ''; // facebook profile picture - https://graph.facebook.com/hasan.atbinici/picture
			$tUser['facebookid'] = $uUser->object['id'];
			$tUser['languageid'] = 'en';

			// compare the passwords
			if($tUser['password'] != http::post('password2')) {
				throw new Exception('passwords do not match.');
			}

			// insert the constructed record into the database
			$this->load('userModel');
			$this->userModel->insert($tUser);

			// send an e-mail - apply template file
			$tHtmlBody = statics::emailTemplate('res/mailtemplates/register.htm', $tUser);

			// send an e-mail
			$tNewMail = new mail();
			$tNewMail->to = $tRealUser['email'];
			$tNewMail->from = 'info@survey-e-bot.com';
			$tNewMail->subject = 'Your survey-e-bot account';
			$tNewMail->headers['Content-Type'] = 'text/html; charset=utf-8';
			$tNewMail->content = $tHtmlBody;
			$tNewMail->send();

			// render the page
			$this->view();
		}

		/**
		 * forgotten password page
		 */
		public function get_forgottenpassword() {
			// load and validate session data - only guests
			statics::requireAuthentication(-1);

			// render the page
			$this->view();
		}

		/**
		 * postback method for forgotten password page
		 */
		public function post_forgottenpassword() {
			// load and validate session data - only guests
			statics::requireAuthentication(-1);

			//TODO: mechanism

			// render the page
			$this->view();
		}

		/**
		 * edit user profile page
		 */
		public function get_profile() {
			// load and validate session data
			statics::requireAuthentication(1);

			// render the page
			$this->view();
		}

		/**
		 * postback method for edit user profile page
		 */
		public function post_profile() {
			// load and validate session data
			statics::requireAuthentication(1);

			// construct values for the record
			$tValues = http::postArray(['displayname', 'firstname', 'lastname', 'phonenumber', 'email', 'password']);
			$tValues['logo'] = '';

			// check password couple, throw an exception if it's incorrect.
			if($tValues['password'] != http::post('password2')) {
				throw new Exception('passwords do not match.');
			}

			// update the user record
			$this->load('userModel');
			$this->userModel->update(statics::$user['userid'], $tValues);

			// reload the stored user in session
			statics::reloadUserInfo(true);

			// render the page
			$this->view();
		}

		/**
		 * outputs an captcha image for human-verification
		 */
		public function get_image() {
			captcha::generate('registration');
		}
	}

?>
