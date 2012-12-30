<?php

	/**
	 * surveys controller
	 * action methods for all surveys/* urls
	 */
	class surveys extends controller {
		/**
		 * number of surveys per page
		 */
		const PAGE_SIZE = statics::DEFAULT_PAGE_SIZE;

		/**
		 * lists all surveys created by user
		 *
		 * @param $uPage int number of page which is going to be displayed
		 */
		public function get_index($uPage = '1') {
			// load and validate session data
			statics::requireAuthentication(1);

			try {
				// validate the request: page numbers
				$tPage = intval($uPage);
				contracts::isMinimum($tPage, 1)->exception('invalid page number');
				$tOffset = ($tPage - 1) * self::PAGE_SIZE;

				// pass pager data to view
				$this->load('surveyModel');
				$this->set('pagerTotal', $this->surveyModel->countByOwner(statics::$user['userid']));
				$this->setRef('pagerCurrent', $tPage);

				// gather all survey data from model
				$tSurveys = $this->surveyModel->getAllPagedByOwner(statics::$user['userid'], $tOffset, self::PAGE_SIZE);
				$this->setRef('surveys', $tSurveys);

				// construct an array of survey ids in order to use it to enquiry published surveys
				$tSurveyIds = arrays::column($tSurveys, 'surveyid');

				// gather specified published survey data from model and group them by survey id
				$this->load('publishSurveyModel');
				$tSurveyPublishs = arrays::categorize($this->publishSurveyModel->getAllBySurvey($tSurveyIds), 'surveyid');
				$this->setRef('surveypublishs', $tSurveyPublishs);

				// get all survey names from database
				$tAllSurveyNames = $this->surveyModel->getAllNamesByOwner(statics::$user['userid']);
				$this->setRef('surveynames', $tAllSurveyNames);
			}
			catch(Exception $ex) {
				// set an error message to be passed thru session if an exception occurred.
				session::setFlash('notification', ['error', 'Error: ' . $ex->getMessage()]);

				// redirect user to parent page in order to display error message
				mvc::redirect('surveys/index');
				return;
			}

			// render the page
			$this->view();
		}

		/**
		 * new survey page
		 */
		public function get_new() {
			// load and validate session data
			statics::requireAuthentication(1);

			// render the page
			$this->view();
		}

		/**
		 * postback method for new survey page
		 */
		public function post_new() {
			// load and validate session data
			statics::requireAuthentication(1);

			try {
				// construct values for the record
				$tInput = http::postArray(['title', 'description', 'categoryid', 'themeid', 'languageid']);
				$tInput['surveyid'] = string::generateUuid();
				$tInput['ownerid'] = statics::$user['userid'];

				// validate values
				contracts::lengthMinimum($tInput['title'], 3)->exception('title length must be 3 at least');
				contracts::lengthMinimum($tInput['description'], 3)->exception('description length must be 3 at least');
				contracts::isUuid($tInput['categoryid'])->exception('invalid category id format');
				contracts::inKeys($tInput['categoryid'], statics::$categoriesWithCounts)->exception('invalid category id');
				contracts::isUuid($tInput['themeid'])->exception('invalid theme id format');
				contracts::inKeys($tInput['themeid'], statics::$themesWithCounts)->exception('invalid theme id');
				contracts::length($tInput['languageid'], 2)->exception('invalid language id format');
				contracts::inKeys($tInput['languageid'], statics::$languagesWithCounts)->exception('invalid language id');

				// insert the record into database
				$this->load('surveyModel');
				$this->surveyModel->insert($tInput);

				session::setFlash('notification', ['success', 'Survey has added successfully']);
			}
			catch(Exception $ex) {
				// set an error message to be passed thru session if an exception occurred.
				session::setFlash('notification', ['error', 'Error: ' . $ex->getMessage()]);
			}

			mvc::redirect('surveys/index');
		}

		/**
		 * edit survey page
		 *
		 * @param $uSurveyId string the uuid represents survey id
		 */
		public function get_edit($uSurveyId) {
			// load and validate session data
			statics::requireAuthentication(1);

			try {
				// validate the request: survey id
				contracts::isUuid($uSurveyId)->exception('invalid survey id format');

				// gather all survey data from model
				$this->load('surveyModel');
				$tSurvey = $this->surveyModel->get($uSurveyId);
				contracts::isNotFalse($tSurvey)->exception('invalid survey id');
				contracts::isEqual($tSurvey['ownerid'], statics::$user['userid'])->exception('unauthorized access');
				$this->setRef('survey', $tSurvey);
			}
			catch(Exception $ex) {
				// set an error message to be passed thru session if an exception occurred.
				session::setFlash('notification', ['error', 'Error: ' . $ex->getMessage()]);

				// redirect user to parent page in order to display error message
				mvc::redirect('surveys/index');
				return;
			}

			// render the page
			$this->view();
		}
		
		/**
		 * postback method for edit survey page
		 *
		 * @param $uSurveyId string the uuid represents survey id
		 */
		public function post_edit($uSurveyId) {
			// load and validate session data
			statics::requireAuthentication(1);

			try {
				// validate the request: question id
				contracts::isUuid($uSurveyId)->exception('invalid survey id format');

				// check record
				$this->load('surveyModel');
				$tSurvey = $this->surveyModel->get($uSurveyId);
				contracts::isNotFalse($tSurvey)->exception('invalid survey id');
				contracts::isEqual($tSurvey['ownerid'], statics::$user['userid'])->exception('unauthorized access');

				// construct values for the record
				$tInput = http::postArray(['title', 'description', 'categoryid', 'themeid', 'languageid']);

				// validate the request
				contracts::lengthMinimum($tInput['title'], 3)->exception('title length must be 3 at least');
				contracts::lengthMinimum($tInput['description'], 3)->exception('description length must be 3 at least');
				contracts::isUuid($tInput['categoryid'])->exception('invalid category id format');
				contracts::inKeys($tInput['categoryid'], statics::$categoriesWithCounts)->exception('invalid category id');
				contracts::isUuid($tInput['themeid'])->exception('invalid theme id format');
				contracts::inKeys($tInput['themeid'], statics::$themesWithCounts)->exception('invalid theme id');
				contracts::length($tInput['languageid'], 2)->exception('invalid language id format');
				contracts::inKeys($tInput['languageid'], statics::$languagesWithCounts)->exception('invalid language id');

				// update the record
				$this->surveyModel->update($uSurveyId, $tInput);
				session::setFlash('notification', ['success', 'Survey has edited successfully']);
			}
			catch(Exception $ex) {
				// set an error message to be passed thru session if an exception occurred.
				session::setFlash('notification', ['error', 'Error: ' . $ex->getMessage()]);
			}

			mvc::redirect('surveys/index');
		}

		/**
		 * questions form
		 */
		public function get_questions($uSurveyId) {
			/// load and validate session data
			statics::requireAuthentication(1);

			try {
				// validate the request: survey id
				contracts::isUuid($uSurveyId)->exception('invalid survey id format');

				// gather all survey data from model
				$this->load('surveyModel');
				$tSurvey = $this->surveyModel->get($uSurveyId);
				contracts::isNotFalse($tSurvey)->exception('invalid survey id');
				contracts::isEqual($tSurvey['ownerid'], statics::$user['userid'])->exception('unauthorized access');
				$this->setRef('survey', $tSurvey);
			}
			catch(Exception $ex) {
				// set an error message to be passed thru session if an exception occurred.
				session::setFlash('notification', ['error', 'Error: ' . $ex->getMessage()]);

				// redirect user to parent page in order to display error message
				mvc::redirect('surveys/index');
				return;
			}

			// render the page
			$this->view();
		}
	}

?>
