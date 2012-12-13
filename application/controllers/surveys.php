<?php

	/**
	 * @ignore
	 */
	class surveys extends controller {
		/**
		 * @ignore
		 */
		public function get_index() {
			statics::requireAuthentication(0);

			$this->load('surveyModel');

			// gather all survey data from model
			// $tSurveys = $this->surveyModel->getAll();
			
			// assign the user data to view
			// $this->set('surveys', $tSurveys);

			// render the page
			$this->view();
		}
	}

?>
