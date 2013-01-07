<?php

	use Scabbia\controller;
	use Scabbia\arrays;
	use Scabbia\validation;
	use Scabbia\captcha;
	use Scabbia\http;
	use Scabbia\session;

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

			// gather all question data from model
			$this->load('faqModel');
			$tFaqData = $this->faqModel->getAll();

			// assign the data with categorizing it by the name of the faqcategory
			$this->set('faq', arrays::categorize($tFaqData, 'name'));

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
			// load and validate session data - guests allowed
			statics::requireAuthentication(0);

			try {
				if(is_null(statics::$user)) {
					// construct values for the record
					$tInput = http::postArray(['name', 'email', 'subject', 'msg', 'verification']);

					// validate the request
					validation::addRule('name')->lengthMinimum(3)->errorMessage('name length must be 3 at least');
					validation::addRule('email')->isEmail()->errorMessage('invalid e-mail address input');
					validation::addRule('subject')->lengthMinimum(3)->errorMessage('subject length must be 3 at least');
					validation::addRule('msg')->lengthMinimum(3)->errorMessage('msg length must be 3 at least');
					validation::addRule('verification')->custom(function($uValue) { // checks captcha is entered correctly
						return captcha::check($uValue, 'contactform');
					})->errorMessage('verification code is invalid');
				}
				else {
					// construct values for the record
					$tInput = http::postArray(['subject', 'msg']);
					$tInput['name'] = statics::$user['displayname'];
					$tInput['email'] = statics::$user['email'];

					validation::addRule('subject')->lengthMinimum(3)->errorMessage('subject length must be 3 at least');
					validation::addRule('msg')->lengthMinimum(3)->errorMessage('msg length must be 3 at least');
				}

				if(validation::validate($tInput)) {
					if(isset($tInput['verification'])) {
						unset($tInput['verification']);
					}

					// send an e-mail - apply template file
					$tHtmlBody = statics::emailTemplate('res/mailtemplates/contactForm.htm', $tInput);

					// send an e-mail
					$tNewMail = new mail();
					$tNewMail->to = 'eser@sent.com';
					$tNewMail->from = 'info@survey-e-bot.com';
					$tNewMail->subject = 'survey-e-bot contact form';
					$tNewMail->headers['Content-Type'] = 'text/html; charset=utf-8';
					$tNewMail->content = $tHtmlBody;
					$tNewMail->send();

					// set notification and redirect user to homepage after registration
					session::setFlash('loginNotification', ['success', 'You have just dropped a message to system administrators. You will be contacted shortly.']);

					// redirect user to homepage
					mvc::redirect('home/index');

					return;
				}
				
				session::setFlash('notification', ['warning', 'Validation Errors:<br />' . implode('<br />', validation::getErrorMessages(true))]);
			}
			catch(Exception $ex) {
				// set an error message to be passed thru session if an exception occurred.
				session::setFlash('notification', ['error', 'Error: ' . $ex->getMessage()]);
			}

			if(is_null(statics::$user)) {
				// render the page for visitors
				$this->view('home/contact_visitor.cshtml');

				return;
			}

			// render the page
			$this->view();
		}

		/*
		 * generates captcha image for contact form
		 */
		public function get_contactImage() {
			captcha::generate('contactform');
		}
	}

?>
