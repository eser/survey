<?php

	/**
	 * @ignore
	 */
	class home extends controller {
		/**
		 * @ignore
		 */
		public function get_index() {
			statics::requireAuthentication(0);

			$this->load('userModel');

			// gather all user data from model
			$tUsers = $this->userModel->getAll();
			
			// assign the user data to view
			$this->set('users', $tUsers);

			// render the page
			$this->view();
		}

		/**
		 * @ignore
		 */
		public function get_blog($uId = null) {
			statics::requireAuthentication(0);

			$this->load('postModel');

			if(!is_null($uId)) {
				$this->set('post', $this->postModel->get($uId));

				$this->view('home/blogentry.cshtml');
				return;
			}

			$this->set('posts', $this->postModel->getAllPaged(0, 25));

			// render the page
			$this->view();
		}

		/**
		 * @ignore
		 */
		public function get_about() {
			statics::requireAuthentication(0);

			// render the page
			$this->view();
		}

		/**
		 * @ignore
		 */
		public function get_contact() {
			statics::requireAuthentication(0);

			// render the page
			$this->view();
		}

		/**
		 * @ignore
		 */
		public function get_page($tPage) {
			statics::requireAuthentication(0);

			// render the page
			$this->view('home/design/' . $tPage . '.cshtml');
		}
	}

?>
