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

			// logout notification
			session::setFlash('loginNotification', ['success', 'You have signed out successfully']);

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

			if(!is_null($tUser['emailverification'])) {
				throw new Exception('your account is not verified, check your e-mail account to complete verification phase');
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

				session::setFlash('loginNotification', ['success', 'You have created a login by using Facebook. Check your e-mail for your generated password.']);
			}

			// assign the user data to view
			$this->set('user', $tRealUser);

			// assign the user data to session
			session::set('user', $tRealUser);
			statics::$user = &$tRealUser;

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
					'facebookid' => $uUser->object['id'],
					'emailverification' => null
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
				'languageid' => 'en',
				'emailverification' => null
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
		 * verifies user account
		 */
		public function get_verify($uUserId, $uCode) {
			// load and validate session data
			statics::requireAuthentication(0);

			// logout user first
			session::remove('user');
			statics::$user = null;

			try {
				// validate the request
				contracts::isUuid($uUserId)->exception('invalid user id format');
				contracts::length($uCode, 8)->exception('invalid verification code');

				$this->load('userModel');
				$tUser = $this->userModel->get($uUserId);
				contracts::isNotFalse($tUser)->exception('invalid user id');
				contracts::isRequired($tUser['emailverification'])->exception('user account is already verified');
				contracts::isEqual($uCode, $tUser['emailverification'])->exception('verification codes does not match');

				// update user record as verified
				$this->userModel->update(
					$tUser['userid'],
					[
						'emailverification' => null
					]
				);

				// assign the user data to session
				session::set('user', $tUser);
				// statics::$user = &$tUser; // not necessary

				session::setFlash('loginNotification', ['success', 'You have just verified your user account']);
			}
			catch(Exception $ex) {
				// logout notification
				session::setFlash('loginNotification', ['error', $ex->getMessage()]);
			}

			// redirect user to homepage
			mvc::redirect('home/index');
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

			$this->load('userModel');

			try {
				// construct values for the record
				$tUser = http::postArray(['displayname', 'firstname', 'lastname', 'phonenumber', 'email', 'password', 'languageid', 'verification']);
				$tUser['userid'] = string::generateUuid();
				$tUser['emailverification'] = string::generate(8);
				$tUser['facebookid'] = null;

				// validate the request
				validation::addRule('displayname')->lengthMinimum(3)->errorMessage('display name length must be 3 at least');
				validation::addRule('firstname')->lengthMinimum(3)->errorMessage('first name length must be 3 at least');
				validation::addRule('lastname')->lengthMinimum(3)->errorMessage('last name length must be 3 at least');
				validation::addRule('email')->isEmail()->errorMessage('invalid e-mail address input');
				validation::addRule('email')->custom(function($uValue) /* use ($this) */ { // checks e-mail is already taken
					$tTempCheck = $this->userModel->getByEmail($uValue);
					return ($tTempCheck === false);
				})->errorMessage('e-mail is already registered in system');
				validation::addRule('password')->lengthMinimum(3)->errorMessage('password length must be 3 at least');
				validation::addRule('password')->isEqual(http::post('password2'))->errorMessage('passwords do not match');
				validation::addRule('languageid')->inKeys(statics::$languagesWithCounts)->errorMessage('primary language is invalid');
				validation::addRule('verification')->custom(function($uValue) { // checks captcha is entered correctly
					return captcha::check($uValue, 'registration');
				})->errorMessage('verification code is invalid');

				// if the input variables passes validation,
				// create user and redirect user to homepage.
				if(validation::validate($tUser)) {
					unset($tUser['verification']);

					// if logo is being uploaded
					if(isset($_FILES['logofile']) && strlen($_FILES['logofile']['tmp_name']) > 0) {
						// open the temporary file and resize it
						$tFile = media::open($_FILES['logofile']['tmp_name'], $_FILES['logofile']['name']);
						$tFile->resize('400', '150');
						$tFile->mime = 'image/png';

						// write the modified file in proper path
						$tUser['logo'] = 'logos/' . $tUser['userid'] . '.png';
						$tPath = framework::writablePath($tUser['logo']);
						if(file_exists($tPath)) {
							unlink($tPath);
						}
						$tFile->save($tPath);
					}
					else {
						$tUser['logo'] = '';
					}

					// insert the constructed record into the database
					$this->userModel->insert($tUser);

					// send an e-mail - apply template file
					$tHtmlBody = statics::emailTemplate('res/mailtemplates/register.htm', $tUser);

					// send an e-mail
					$tNewMail = new mail();
					$tNewMail->to = $tUser['email'];
					$tNewMail->from = 'info@survey-e-bot.com';
					$tNewMail->subject = 'Your survey-e-bot account';
					$tNewMail->headers['Content-Type'] = 'text/html; charset=utf-8';
					$tNewMail->content = $tHtmlBody;
					$tNewMail->send();

					// set notification and redirect user to homepage after registration
					session::setFlash('loginNotification', ['success', 'You have just created an user account. Check your e-mail for details.']);
					mvc::redirect('home/index');

					return;
				}

				session::setFlash('notification', ['warning', 'Validation Errors:<br />' . implode('<br />', validation::getErrorMessages(true))]);
			}
			catch(Exception $ex) {
				// set an error message to be passed thru session if an exception occurred.
				session::setFlash('notification', ['error', 'Error: ' . $ex->getMessage()]);
			}

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

			$this->load('userModel');

			try {
				// construct values for the record
				$tInput = http::postArray(['email', 'verification']);

				// validate the request
				validation::addRule('email')->isEmail()->errorMessage('invalid e-mail address input');
				$tUser = $this->userModel->getByEmail($tInput['email']); // get the account by e-mail

				validation::addRule('email')->isNotFalse()->errorMessage('e-mail is not registered in system');
				validation::addRule('verification')->custom(function($uValue) { // checks captcha is entered correctly
					return captcha::check($uValue, 'registration');
				})->errorMessage('verification code is invalid');

				// if the input variables passes validation,
				// create user and redirect user to homepage.
				if(validation::validate($tInput)) {
					// send an e-mail - apply template file
					$tHtmlBody = statics::emailTemplate('res/mailtemplates/forgottenPassword.htm', $tUser);

					// send an e-mail
					$tNewMail = new mail();
					$tNewMail->to = $tUser['email'];
					$tNewMail->from = 'info@survey-e-bot.com';
					$tNewMail->subject = 'Forgotten password for survey-e-bot account';
					$tNewMail->headers['Content-Type'] = 'text/html; charset=utf-8';
					$tNewMail->content = $tHtmlBody;
					$tNewMail->send();

					// set notification and redirect user to homepage after registration
					session::setFlash('loginNotification', ['success', 'Your password is sent to your inbox. Check your e-mail for details.']);
					mvc::redirect('home/index');

					return;
				}

				session::setFlash('notification', ['warning', 'Validation Errors:<br />' . implode('<br />', validation::getErrorMessages(true))]);
			}
			catch(Exception $ex) {
				// set an error message to be passed thru session if an exception occurred.
				session::setFlash('notification', ['error', 'Error: ' . $ex->getMessage()]);
			}

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

			try {
				// construct values for the record
				$tValues = http::postArray(['displayname', 'firstname', 'lastname', 'phonenumber', 'password', 'languageid']);

				// validate the request
				validation::addRule('displayname')->lengthMinimum(3)->errorMessage('display name length must be 3 at least');
				validation::addRule('firstname')->lengthMinimum(3)->errorMessage('first name length must be 3 at least');
				validation::addRule('lastname')->lengthMinimum(3)->errorMessage('last name length must be 3 at least');
				validation::addRule('password')->lengthMinimum(3)->errorMessage('password length must be 3 at least');
				validation::addRule('password')->isEqual(http::post('password2'))->errorMessage('passwords do not match');
				validation::addRule('languageid')->inKeys(statics::$languagesWithCounts)->errorMessage('primary language is invalid');

				// if the input variables passes validation,
				// update user and redirect user to homepage.
				if(validation::validate($tValues)) {
					// if logo is being uploaded
					if(isset($_FILES['logofile']) && strlen($_FILES['logofile']['tmp_name']) > 0) {
						// open the temporary file and resize it
						$tFile = media::open($_FILES['logofile']['tmp_name'], $_FILES['logofile']['name']);
						$tFile->resize('400', '150');
						$tFile->mime = 'image/png';

						// write the modified file in proper path
						$tValues['logo'] = 'logos/' . statics::$user['userid'] . '.png';
						$tPath = framework::writablePath($tValues['logo']);
						if(file_exists($tPath)) {
							unlink($tPath);
						}
						$tFile->save($tPath);
					}

					// update the user record
					$this->load('userModel');
					$this->userModel->update(statics::$user['userid'], $tValues);

					// reload the stored user in session
					statics::reloadUserInfo(true);

					// set notification and redirect user to homepage after registration
					session::setFlash('loginNotification', ['success', 'You have updated your account information.']);
					mvc::redirect('home/index');

					return;
				}

				session::setFlash('notification', ['warning', 'Validation Errors:<br />' . implode('<br />', validation::getErrorMessages(true))]);
			}
			catch(Exception $ex) {
				// set an error message to be passed thru session if an exception occurred.
				session::setFlash('notification', ['error', 'Error: ' . $ex->getMessage()]);
			}

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
