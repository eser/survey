<?php

	/**
	 * @ignore
	 */
	class surveys extends controller {
		/**
		 * @ignore
		 */
		public function get_new() {
			statics::requireAuthentication(1);

			// render the page
			$this->view();
		}

		/**
		 * @ignore
		 */
		public function get_index() {
			statics::requireAuthentication(1);

			$this->load('surveyModel');

			// gather all survey data from model
			$tSurveys = $this->surveyModel->getAllByOwner(statics::$user['userid']);
			
			// assign the user data to view
			$this->setRef('surveys', $tSurveys);

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
