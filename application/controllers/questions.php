<?php

	/**
	 * @ignore
	 */
	class questions extends controller {
		/**
		 * @ignore
		 */
		public function get_new() {
			statics::requireAuthentication(1);

			$this->view('questions/new.cshtml');
		}

		/**
		 * @ignore
		 */
		public function post_new() {
			statics::requireAuthentication(1);
			$this->load('questionModel');
			$this->load('optionModel');
			$questionID = string::generateUuid();
			$input = array(
				'questionid' => $questionID,
				'ownerid' => statics::$user['userid'],
				'content' => http::post('question'),
				'type' => '0'
			);
			$this->questionModel->insert($input);
			$options =  http::post('options');
			foreach($options as $option){
				$optionInput = array(
					'questionchoiceid' => string::generateUuid(),
					'questionid' => $questionID,
					'content' => $option
				);
				string::vardump($this->optionModel->insert($optionInput));
			}

			$this->view('questions/new.cshtml');
		}

		/**
		 * @ignore
		 */
		public function get_index() {
			statics::requireAuthentication(1);

			$this->load('questionModel');

			// gather all survey data from model
			$tQuestions = $this->questionModel->getAllByOwner(statics::$user['userid']);

			// assign the user data to view
			$this->setRef('questions', $tQuestions);

			// render the page
			$this->view();
		}

		/**
		 * @ignore
		 */
		public function get_edit($uSurveyId) {
			statics::requireAuthentication(1);

			$this->load('surveyModel');

			// gather all survey data from model
			$tSurvey = $this->surveyModel->get($uSurveyId);
			
			// assign the user data to view
			$this->setRef('survey', $tSurvey);

			// render the page
			$this->view();
		}

		/**
		 * @ignore
		 */
		public function get_report($uSurveyId) {
			statics::requireAuthentication(1);

			$this->load('surveyModel');

			// gather all survey data from model
			$tSurvey = $this->surveyModel->get($uSurveyId);
			
			// assign the user data to view
			$this->setRef('survey', $tSurvey);

			// render the page
			$this->view();
		}
	}

?>
