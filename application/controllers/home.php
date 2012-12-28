<?php

	/**
	 * home controller
	 * action methods for all home/* urls
	 */
	class home extends controller {
		/**
		 * the homepage
		 */
		public function get_index() {
			// load and validate session data - guests allowed
			statics::requireAuthentication(0);

			// render the page
			$this->view();
		}

		/**
		 * @ignore
		 */
		public function get_faq() {
			// load and validate session data - guests allowed
			statics::requireAuthentication(0);

			// render the page
			$this->view();
		}

		/**
		 * @ignore
		 */
		public function get_about() {
			// load and validate session data - guests allowed
			statics::requireAuthentication(0);

			// render the page
			$this->view();
		}

		/**
		 * @ignore
		 */
		public function get_contact() {
			// load and validate session data - guests allowed
			statics::requireAuthentication(0);

			// render the page
			$this->view();
		}
	}

?>
