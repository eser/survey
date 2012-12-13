<?php

	/**
	 * @ignore
	 */
	class home extends controller {
		/**
		 * @ignore
		 */
		public function index() {
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
		public function blog() {
			statics::requireAuthentication(0);

			$this->load('postModel');
			$this->set('posts', $this->postModel->getAllPaged(0, 25));

			// render the page
			$this->view();
		}

		/**
		 * @ignore
		 */
		public function about() {
			statics::requireAuthentication(0);

			// render the page
			$this->view();
		}

		/**
		 * @ignore
		 */
		public function contact() {
			statics::requireAuthentication(0);

			// render the page
			$this->view();
		}

		/**
		 * @ignore
		 */
		public function page($tPage) {
			statics::requireAuthentication(0);

			// render the page
			$this->view('home/design/' . $tPage . '.cshtml');
		}
	}

?>
