<?php

	/**
	 * @ignore
	 */
	class user extends controller {
		/**
		 * @ignore
		 */
		public function postajax_login() {
			// statics::requireAuthentication(0);

			$this->load('userModel');

			$tEmail = http::post('email');
			$tPassword = http::post('password');

			// gather all user data from model
			$tUser = $this->userModel->getByEmail($tEmail);

			if($tUser === false || strcmp($tPassword, $tUser['password']) != 0) {
				throw new Exception('no such user or password incorrect.');
			}

			// assign the user data to view
			$this->set('user', $tUser);

			session::set('user', $tUser);
			statics::$user = &$tUser;
			
			// render the page
			$this->json();
		}

		/**
		 * @ignore
		 */
		public function get_login() {
			statics::requireAuthentication(0);

			session::remove('user');
			statics::$user = null;

			mvc::redirect('home/index');
		}

		/**
		 * @ignore
		 */
		public function get_fblogin() {
			statics::requireAuthentication(0);

			fb::loadApi();
			if(!isset($_GET['state'])) {
				$tLoginUrl = fb::getLoginUrl('email', 'http://localhost/survey/user/fblogin');

				header('Location: ' . $tLoginUrl, true);
				framework::end(0);
			}

			if(fb::$userId > 0) {
				string::vardump(fb::get('/me', false));
			}

			echo 'done.';
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

			$tValues = http::postArray(
				array('fullname', 'phonenumber', 'email', 'password')
			);

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
