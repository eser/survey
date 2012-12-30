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
				$this->load('surveypublishModel');
				$tSurveyPublishs = arrays::categorize($this->surveypublishModel->getAllBySurvey($tSurveyIds), 'surveyid');
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
		 *
		 * ** INCOMPLETE
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

				// gather all questions from data model
				$this->load('questionModel');
				$tQuerySuggestions = [];
				foreach($this->questionModel->getAllAccessible(statics::$user['userid']) as $tRow) {
					$tQuerySuggestions[] = ['hiddenvalue' => $tRow['questionid'], 'label' => $tRow['content']];
				}
				$this->setRef('querySuggestions', $tQuerySuggestions);
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
		 * new survey publish page
		 *
		 * ** INCOMPLETE
		 */
		public function get_publish($uSurveyId = null) {
			// load and validate session data
			statics::requireAuthentication(1);

			try {
				$this->setRef('surveyid', $uSurveyId);

				// gather all survey data from model
				$this->load('surveyModel');
				$tSurveys = $this->surveyModel->getAllByOwner(statics::$user['userid']);
				$this->setRef('surveys', $tSurveys);
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
		 * postback method for new survey publish page
		 *
		 * ** INCOMPLETE
		 */
		public function post_publish() {
			// load and validate session data
			statics::requireAuthentication(1);

			$this->load('surveypublishModel');
			$input = array(
				'surveypublishid' => string::generateUuid(),
				'surveyid' => http::post('surveyid'),
				'revision' => '1',
				'ownerid' => statics::$user['userid'],
				'startdate' => http::post('startdate'),
				'enddate' => http::post('enddate'),
				'password' => http::post('password'),
				'type' => http::post('type'),
				'enabled' => http::post('enabled'),
				'limit' => http::post('limit')
			);

			$insertSurvey = $this->surveypublishModel->insert($input);
			if($insertSurvey > 0){
				echo "<script>alert('Survey Added Successfuly');</script>";
			}
			else {
				echo "<script>alert('Unexpected Error.');</script>";
			}

			$this->load('surveyModel');
			$surveys = $this->surveyModel->getAllByOwner(statics::$user['userid']);

			$this->setRef('surveys', $surveys);
			$this->view();
		}

		/**
		 * @ignore
		 *
		 * ** INCOMPLETE
		 */
		public function get_editpublish($uSurveyPublishsId) {
			statics::requireAuthentication(1);
			$this->load('surveypublishModel');
			// gather all survey data from model
			$tSurvey = $this->surveypublishModel->get($uSurveyPublishsId);

			// assign the user data to view
			$this->set('surveypublishs', $tSurvey);
			// render the page
			$this->view();
		}

		/**
		 * @ignore
		 *
		 * ** INCOMPLETE
		 */
		public function post_editpublish($uSurveyPublishId) {
			statics::requireAuthentication(1);
			$this->load('surveypublishModel');
			$update = array(
				'revision' => '1',
				'ownerid' => statics::$user['userid'],
				'startdate' => http::post('startdate'),
				'enddate' => http::post('enddate'),
				'password' => http::post('password'),
				'type' => http::post('type'),
				'enabled' => http::post('enabled'),
				'limit' => http::post('limit')
			);

			// gather all survey data from model
			$tEditPublish = $this->surveypublishModel->update($uSurveyPublishId, $update);
			if($tEditPublish > 0){
				echo "<script> alert('Record updated successfuly.');</script>";
			}
			else{
				echo "<script> alert('Unexpected Error. Try Again Later.');</script>";
			}

			$tSurvey = $this->surveypublishModel->get($uSurveyPublishId);

			// assign the user data to view
			$this->set('surveypublishs', $tSurvey);

			// render the page
			$this->view();
		}

		/**
		 * @ignore
		 *
		 * ** INCOMPLETE
		 */
		public function get_take($uSurveyPublishId) {
			statics::requireAuthentication(0);

			/*
			$checkPast = $this->surveypublishModel->checkPast(statics::$user['userid'],$uSurveyPublishId);
			if(count($checkPast) > 0) {
				//return ( bu anketi doldurdunuz sayfası.
				exit;
			}
			*/

			$this->load('surveyvisitorModel');
			$tExistingSurveyVisitor = $this->surveyvisitorModel->get(session::$id);
			if($tExistingSurveyVisitor !== false) {
				throw new Exception('dolmus o anket');
			}
			
			// gather all survey data from model
			$this->load('surveypublishModel');
			$survey = $this->surveypublishModel->get($uSurveyPublishId);
			$this->load('questionModel');
			$questions = $this->questionModel->getBySurveyID($survey['surveyid'], $survey['revision']);
			$choices = array();
			foreach($questions as $question) {
				$choices[$question['questionid']] = $this->questionModel->getChoicesByQuestionID($question['questionid']);
			}

			// assign the user data to view
			$this->setRef('surveys', $survey);
			$this->setRef('choices', $choices);
			$this->setRef('questions', $questions);

			// render the page
			$this->view();
		}

		/**
		 * @ignore
		 *
		 * ** INCOMPLETE
		 */
		public function post_take($uSurveyPublishId) {
			statics::requireAuthentication(0);
			
			// check auth-only
			
			$this->load('surveypublishModel');
			$this->load('surveyvisitorModel');
			$this->load('questionModel');

			// gather all survey data from model
			$survey = $this->surveypublishModel->get($uSurveyPublishId);
			$tExistingSurveyVisitor = $this->surveyvisitorModel->get(session::$id);
			if($tExistingSurveyVisitor !== false) {
				throw new Exception('dolmus o anket');
			}

			$tSurveyVisitor = array(
				'surveyvisitorid' => session::$id,
				'surveypublishid' => $survey['surveypublishid'],
				'userid' => (!is_null(statics::$user) ? statics::$user['userid'] : null),
				'ip' => $_SERVER['REMOTE_ADDR'],
				'useragent' => $_SERVER['HTTP_USER_AGENT'],
				'recorddate' => time::toDb(time())
			);
			$this->surveyvisitorModel->insert($tSurveyVisitor);

			$questions = $this->questionModel->getBySurveyID($survey['surveyid']);

			$answers = array();
			foreach($questions as $question) {
				$answers[$question['questionid']] = http::post($question['questionid']);
				$answersvalues[$question['questionid']] = http::post($question['questionid'] . 'value');

				if($question['type'] == statics::QUESTION_MULTIPLE) {
					$input = array(
						'surveypublishid' => $uSurveyPublishId,
						'questionid' => $question['questionid'],
						'surveyvisitorid' => $tSurveyVisitor['surveyvisitorid'],
						'questionchoiceid' => $answers[$question['questionid']],
						'value'=> $answersvalues[$question['questionid']]
					);
				} else {
					$input = array(
						'surveyid' => $uSurveyPublishId,
						'questionid' => $question['questionid'],
						'surveyvisitorid' => $tSurveyVisitor['surveyvisitorid'],
						'questionchoiceid' => null,
						'value'=> $answers[$question['questionid']]
					);
				}

				$this->questionModel->insertAnswer($input);
			}

			//Anketi Doldurdunuz uyarısı flash filan koyulmalı.
			mvc::redirect('home/index');

			// render the page
			$this->view();
		}

		/**
		 * @ignore
		 *
		 * ** INCOMPLETE
		 */
		public function get_report($uSurveyPublishId) {
			statics::requireAuthentication(1);
			$this->load('surveypublishModel');

			// gather all survey data from model
			$survey = $this->surveypublishModel->get($uSurveyPublishId);

			$this->load('questionModel');
			$questions = $this->questionModel->getBySurveyID($survey['surveyid'], $survey['revision']);
			$questionids = arrays::column($questions, 'questionid');

			$answers = $this->questionModel->getByPublishID($uSurveyPublishId, $questionids);
			string::vardump($answers);

			// assign the user data to view
			$this->setRef('reports', $categorize);
			$this->setRef('surveys', $survey);
			$this->setRef('questions', $questionsTest);
			$this->setRef('choices', $choices);

			// render the page
			$this->view();
		}
	}

?>
