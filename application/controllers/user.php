<?php

	/**
	 * @ignore
	 */
	class user extends controller {
		/**
		 * @ignore
		 */
		public function post_login() {
			$this->load('userModel');

			$tEmail = http::post('email');
			$tPassword = http::post('password');

			// gather all user data from model
			$tUser = $this->userModel->getByEmail($tEmail);
			if($tUser === false) {
				exit('no such user.');
			}

			if($tPassword != $tUser['password']) {
				exit();
			}
			
			// assign the user data to view
			$this->set('users', $tUsers);

			// render the page
			$this->view();
		}
	}

?>
