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
	}

?>
