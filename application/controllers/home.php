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
		 * frequently asked questions page
		 */
		public function get_faq() {
			// load and validate session data - guests allowed
			statics::requireAuthentication(0);

			// render the page
			$this->view();
		}

		/**
		 * about page
		 */
		public function get_about() {
			// load and validate session data - guests allowed
			statics::requireAuthentication(0);

			// render the page
			$this->view();
		}

		/**
		 * contact page
		 */
		public function get_contact() {
			// load and validate session data - guests allowed
			statics::requireAuthentication(0);

			if(is_null(statics::$user)) {
				// render the page for visitors
				$this->view('home/contact_visitor.cshtml');

				return;
			}

			// render the page
			$this->view();
		}

		/**
		 * postback method for contact page
		 */
		public function post_contact() {
			//TODO: post_contact method
			throw new Exception('not implemented');

			//TODO: set flash and redirect
		}

		/*
		 * generates captcha image for contact form
		 */
		public function get_contactImage() {
			captcha::generate('contactform');
		}
	}

?>
