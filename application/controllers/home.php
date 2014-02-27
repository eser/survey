<?php

    namespace App\Controllers;

    use App\Includes\Statics;
    use App\Includes\SurveyController;
    use Scabbia\Extensions\Mvc\Controller;
	use Scabbia\Extensions\Helpers\Arrays;
	use Scabbia\Extensions\Validation\validation;
	use Scabbia\Extensions\Media\Captcha;
	use Scabbia\Extensions\Http\Http;
	use Scabbia\Extensions\Session\Session;
    use Scabbia\Request;

	/**
	 * home controller
	 * action methods for all home/* urls
	 */
	class Home extends SurveyController {
		/**
		 * the homepage
		 */
		public function get_index() {
			// load and validate session data - guests allowed
			Statics::requireAuthentication(0);

			// render the page
			$this->view();
		}

		/**
		 * frequently asked questions page
		 */
		public function get_faq() {
			// load and validate session data - guests allowed
			Statics::requireAuthentication(0);

			// gather all question data from model
			$this->load('App\\Models\\FaqModel');
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
			Statics::requireAuthentication(0);

			// render the page
			$this->view();
		}

		/**
		 * contact page
		 */
		public function get_contact() {
			// load and validate session data - guests allowed
			Statics::requireAuthentication(0);

			if(is_null(Statics::$user)) {
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
			Statics::requireAuthentication(0);

			try {
				if(is_null(Statics::$user)) {
					// construct values for the record
					$tInput = Request::postArray(['name', 'email', 'subject', 'msg', 'verification']);

					// validate the request
					Validation::addRule('name')->lengthMinimum(3)->errorMessage('name length must be 3 at least');
					Validation::addRule('email')->isEmail()->errorMessage('invalid e-mail address input');
					Validation::addRule('subject')->lengthMinimum(3)->errorMessage('subject length must be 3 at least');
					Validation::addRule('msg')->lengthMinimum(3)->errorMessage('msg length must be 3 at least');
					Validation::addRule('verification')->custom(function($uValue) { // checks captcha is entered correctly
						return Captcha::check($uValue, 'contactform');
					})->errorMessage('verification code is invalid');
				}
				else {
					// construct values for the record
					$tInput = Request::postArray(['subject', 'msg']);
					$tInput['name'] = Statics::$user['displayname'];
					$tInput['email'] = Statics::$user['email'];

					Validation::addRule('subject')->lengthMinimum(3)->errorMessage('subject length must be 3 at least');
					Validation::addRule('msg')->lengthMinimum(3)->errorMessage('msg length must be 3 at least');
				}

				if(Validation::validate($tInput)) {
					if(isset($tInput['verification'])) {
						unset($tInput['verification']);
					}

					// send an e-mail - apply template file
					$tHtmlBody = Statics::emailTemplate('assets/mailtemplates/contactForm.htm', $tInput);

					// send an e-mail
					$tNewMail = new mail();
					$tNewMail->to = 'eser@sent.com';
					$tNewMail->from = 'info@survey-e-bot.com';
					$tNewMail->subject = 'survey-e-bot contact form';
					$tNewMail->headers['Content-Type'] = 'text/html; charset=utf-8';
					$tNewMail->content = $tHtmlBody;
					$tNewMail->send();

					// set notification and redirect user to homepage after registration
					Session::set('loginNotification', ['success', 'You have just dropped a message to system administrators. You will be contacted shortly.']);

					// redirect user to homepage
					Http::redirect('home/index');

					return;
				}
				
				Session::set('notification', ['warning', 'Validation Errors:<br />' . implode('<br />', Validation::getErrorMessages(true))]);
			}
			catch(Exception $ex) {
				// set an error message to be passed thru session if an exception occurred.
				Session::set('notification', ['error', 'Error: ' . $ex->getMessage()]);
			}

			if(is_null(Statics::$user)) {
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
			Captcha::generate('contactform');
		}
	}

?>
