<?php

	/**
	 * @ignore
	 */
	class surveys extends controller {
		const PAGE_SIZE = statics::DEFAULT_PAGE_SIZE;

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
		public function post_new() {
			statics::requireAuthentication(1);

			$input = array(
				'surveyid' => string::generateUuid(),
				'ownerid' => statics::$user['userid'],
				'title' => http::post('title'),
				'categoryid' => http::post('categoryid'),
				'themeid' => http::post('themeid'),
				'languageid' => http::post('languageid')
			);

			$this->load('surveyModel');
			$insertSurvey = $this->surveyModel->insert($input);

			if($insertSurvey > 0){
				echo "<script>alert('Survey Added Successfuly');</script>";
			}
			else {
				echo "<script>alert('Unexpected Error.');</script>";
			}

			$this->view();
		}

		/**
		 * @ignore
		 */
		public function get_index($uPage = '1') {
			statics::requireAuthentication(1);

			$tPage = intval($uPage);
			if($tPage < 1) {
				$tPage = 1;
			}

			$this->load('surveyModel');

			// gather all survey data from model
			$tOffset = ($tPage - 1) * self::PAGE_SIZE;
			$tSurveys = $this->surveyModel->getAllPagedByOwner(statics::$user['userid'], $tOffset, self::PAGE_SIZE);

			$this->load('publishSurveyModel');
			$tSurveyIds = arrays::column($tSurveys, 'surveyid');
			$tSurveyPublishs = arrays::categorize($this->publishSurveyModel->getAllBySurvey($tSurveyIds), 'surveyid');

			$tAllSurveyNames = $this->surveyModel->getAllNamesByOwner(statics::$user['userid']);

			// assign the user data to view
			$this->set('pagerTotal', $this->surveyModel->countByOwner(statics::$user['userid']));
			$this->setRef('pagerCurrent', $tPage);
			$this->setRef('surveys', $tSurveys);
			$this->setRef('surveypublishs', $tSurveyPublishs);
			$this->setRef('surveynames', $tAllSurveyNames);

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
