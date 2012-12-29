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
		 * ajaxified login
		 */
		public function postajax_login() {
			// statics::requireAuthentication(0);

			// construct values from the request
			$tInput = http::postArray(['email', 'password']);

			// validate the request: login credentials
			contracts::isEmail($tInput['email'])->exception('invalid e-mail address input');
			contracts::isRequired($tInput['password'])->exception('password left empty');

			// gather all user data from model
			$this->load('userModel');
			$tUser = $this->userModel->getByEmail($tInput['email']);

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
		 * @ignore
		 */
		public function get_fblogin() {
			statics::requireAuthentication(0);

			fb::loadApi();
			if(!isset($_GET['state'])) {
				$tLoginUrl = fb::getLoginUrl('email', fb::$appRedirectUri);

				header('Location: ' . $tLoginUrl, true);
				framework::end(0);
			}

			if(fb::$userId <= 0) {
				throw new Exception('Facebook login error.');
			}

			$tUser = fb::get('/me', false);

			if(!$tUser->object['verified']) {
				throw new Exception('Facebook account is not verified.');
			}

			$tRealUser = $this->tryMergeAccountWithFacebook($tUser);
			if($tRealUser === false) {
				$tRealUser = $this->registerWithFacebook($tUser);
			}

			// assign the user data to view
			$this->set('user', $tRealUser);

			session::set('user', $tRealUser);
			statics::$user = &$tRealUser;

			mvc::redirect('home/index');
		}

		/**
		 * @ignore
		 */
		private function &tryMergeAccountWithFacebook($uUser) {
			$this->load('userModel');

			$tRealUser = $this->userModel->getByEmailOrFacebookId($uUser->object['email'], $uUser->object['id']);
			if($tRealUser !== false) {
				$this->userModel->update($tRealUser['userid'], [
					'displayname' => $uUser->object['name'],
					'email' => $uUser->object['email'],
					'facebookid' => $uUser->object['id']
				]);

				return $tRealUser;
			}

			return $tRealUser;
		}

		/**
		 * @ignore
		 */
		private function &registerWithFacebook($uUser) {
			$this->load('userModel');

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
			
			$this->userModel->insert($tRealUser);

			smtp::send(
				'info@survey-e-bot.com', // 'Survey-e-bot <info@survey-e-bot.com>',
				$tRealUser['email'], // $tRealUser['displayname'] . ' <' . $tRealUser['email'] . '>',
				'Your account | Welcome to the survey-e-bot',
				'Your password is: ' . $tRealUser['password']
			);

			return $tRealUser;
		}

		/**
		 * @ignore
		 */
		public function get_register() {
			statics::requireAuthentication(-1);

			// render the page
			$this->view();
		}

		/**
		 * @ignore
		 */
		public function post_register() {
			statics::requireAuthentication(-1);

			$this->load('userModel');

			$tUser = http::postArray(['displayname', 'firstname', 'lastname', 'phonenumber', 'email', 'password']);
			$tUser['userid'] = string::generateUuid();
			$tUser['logo'] = ''; // facebook profile picture - https://graph.facebook.com/hasan.atbinici/picture
			$tUser['facebookid'] = $uUser->object['id'];
			$tUser['languageid'] = 'en';

			if($tUser['password'] != http::post('password2')) {
				throw new Exception('passwords do not match.');
			}

			$this->userModel->insert($tUser);

			smtp::send(
				'info@survey-e-bot.com', // 'Survey-e-bot <info@survey-e-bot.com>',
				$tUser['email'], // $tRealUser['displayname'] . ' <' . $tRealUser['email'] . '>',
				'Your account | Welcome to the survey-e-bot',
				'Your password is: ' . $tUser['password']
			);

			// render the page
			$this->view();
		}

		/**
		 * @ignore
		 */
		public function get_forgottenpassword() {
			statics::requireAuthentication(-1);

			// render the page
			$this->view();
		}

		/**
		 * @ignore
		 */
		public function get_profile() {
			statics::requireAuthentication(1);

			// render the page
			$this->view();
		}

		/**
		 * @ignore
		 */
		public function post_profile() {
			statics::requireAuthentication(1);

			$tValues = http::postArray(['displayname', 'firstname', 'lastname', 'phonenumber', 'email', 'password']);

			$tValues['logo'] = '';

			if($tValues['password'] != http::post('password2')) {
				throw new Exception('passwords do not match.');
			}

			$this->load('userModel');
			$this->userModel->update(statics::$user['userid'], $tValues);

			statics::reloadUserInfo(true);

			// render the page
			$this->view();
		}

		/*
		 * @ignore
		 */
		public function get_image() {
			captcha::generate();
		}
	}

?>
