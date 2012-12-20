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
			$tSurveys = $this->surveyModel->getAll();
			
			// assign the user data to view
			$this->setRef('surveys', $tSurveys);

			// render the page
			$this->view();
		}
	}

?>
