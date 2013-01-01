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
		 * @param $uSurveyId string the uuid represents survey id
		 * @param $uTag string an operation tag which is used for survey related operations like 'remove'
		 * @param $uSecondTag string extra tag attribute for operations specified in $uTag
		 */
		public function get_questions($uSurveyId, $uTag = '', $uSecondTag = '') {
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

				if($uTag == 'remove') {
					// validate the request: survey id
					contracts::isUuid($uSecondTag)->exception('invalid questionid id format');

					// revision handling
					$tNextRevision = $this->post_questions_revisionwork($tSurvey['surveyid'], $tSurvey['lastrevision']);

					// remove a question
					$this->load('surveyquestionModel');
					$this->surveyquestionModel->delete($tSurvey['surveyid'], $tNextRevision, $uSecondTag);

					// set an notification message to be passed thru session.
					session::setFlash('notification', ['success', 'Question is successfully removed']);

					mvc::redirect('surveys/questions/' . $tSurvey['surveyid']);

					return;
				}
				
				// gather all questions from data model
				$this->load('questionModel');
				$tQuerySuggestions = [];
				foreach($this->questionModel->getAllAccessibleExcept(statics::$user['userid'], $tSurvey['surveyid'], $tSurvey['lastrevision']) as $tRow) {
					$tQuerySuggestions[] = ['hiddenvalue' => $tRow['questionid'], 'label' => $tRow['content']];
				}
				$this->setRef('querySuggestions', $tQuerySuggestions);

				$tQuestions = $this->questionModel->getBySurveyID($tSurvey['surveyid'], $tSurvey['lastrevision']);
				$tQuestionIds = arrays::column($tQuestions, 'questionid');
				$this->setRef('questions', $tQuestions);

				$tChoices = $this->questionModel->getChoicesByQuestionIDs($tQuestionIds);
				$this->setRef('choices', $tChoices);
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
		 * postback method for questions form
		 *
		 * @param $uSurveyId string the uuid represents survey id
		 * @param $uQuestionType int the question type
		 */
		public function post_questions($uSurveyId, $uQuestionType = null) {
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

				// revision handling
				$tNextRevision = $this->post_questions_revisionwork($tSurvey['surveyid'], $tSurvey['lastrevision']);

				if(!is_null($uQuestionType)) {
					$this->post_questions_other($uQuestionType, $uSurveyId, $tNextRevision);
				}
				else {
					$this->post_questions_questionpool($uSurveyId, $tNextRevision);
				}
			}
			catch(Exception $ex) {
				// set an error message to be passed thru session if an exception occurred.
				session::setFlash('notification', ['error', 'Error: ' . $ex->getMessage()]);
			}

			// render the page
			mvc::redirect('surveys/questions/' . $uSurveyId);
		}

		/**
		 * a subroutine for post_questions
		 */
		private function &post_questions_revisionwork($uSurveyId, $uLastRevision) {
			$this->load('surveypublishModel');
			$tCount = $this->surveypublishModel->getPublishCountBySurvey($uSurveyId, $uLastRevision);
			$tLastRevision = intval($uLastRevision);
			if($tLastRevision <= 0 || intval($tCount) > 0) {
				$tNextRevision = $tLastRevision + 1;
			}
			else {
				$tNextRevision = $tLastRevision;
			}

			if($tNextRevision > $tLastRevision) {
				$tNewRevision = [
					'surveyid' => $uSurveyId,
					'revision' => $tNextRevision,
					'createdate' => time::toDb(time()),
					'ownerid' => statics::$user['userid'],
					'details' => ''
				];

				$this->load('surveyrevisionModel');
				$this->surveyrevisionModel->insert($tNewRevision);

				// transfer previous revision's items to current one.
				$this->load('surveyquestionModel');
				$this->surveyquestionModel->transferQuestions($uSurveyId, $tLastRevision, $tNextRevision);
			}

			return $tNextRevision;
		}

		/**
		 * a subroutine for post_questions
		 */
		private function post_questions_questionpool($uSurveyId, $uRevision) {
			$tSurveyQuestion = http::postArray(['questionid']);
			$tSurveyQuestion['surveyid'] = $uSurveyId;
			$tSurveyQuestion['revision'] = $uRevision;

			// validate the request
			$this->load('questionModel');
			contracts::isUuid($tSurveyQuestion['questionid'])->exception('invalid question id format');
			$tQuestion = $this->questionModel->get($tSurveyQuestion['questionid']);
			contracts::isNotFalse($tQuestion)->exception('invalid question id');
			if($tQuestion['isshared'] != statics::SHARE_SHARED) {
				contracts::isEqual($tQuestion['ownerid'], statics::$user['userid'])->exception('unauthorized access');
			}

			//TODO check for question repetation
			
			$this->load('surveyquestionModel');
			$this->surveyquestionModel->insert($tSurveyQuestion);
		}

		private function post_questions_other($uType, $uSurveyId, $uRevision) {
			// construct values for the record
			$tInput = http::postArray(['content', 'typefilter', 'isshared']);
			$tInput['questionid'] = string::generateUuid();
			$tInput['type'] = $uType;
			$tInput['ownerid'] = statics::$user['userid'];

			// validate the request
			contracts::lengthMinimum($tInput['content'], 3)->exception('question length must be 3 at least');
			contracts::inKeys($uType, statics::$questiontypes)->exception('question type is invalid');
			if(isset($tInput['typefilter'])) {
				contracts::inKeys($tInput['typefilter'], statics::$questiontypefilters)->exception('question type filter is invalid');
			}
			else {
				$tInput['typefilter'] = '0';
			}
			contracts::inKeys($tInput['isshared'], statics::$sharedboolean)->exception('accessibility is invalid');

			if($uType == statics::QUESTION_MULTIPLE) {
				$tOptions = http::post('options');
				$tOptionTypes = http::post('optiontypes');

				foreach($tOptions as $tKey => &$tOption) {
					contracts::lengthMinimum($tOption, 1)->exception('a question choice length must be 3 at least');
					contracts::inKeys($tOptionTypes[$tKey], statics::$questionoptiontypes)->exception('a question choice type is invalid');
				}
			}

			// insert the record into database
			$this->load('questionModel');
			$this->questionModel->insert($tInput);

			// insert question choices if and only if question's type is multiple choice
			if($uType == statics::QUESTION_MULTIPLE) {
				// loop for each option of question
				foreach($tOptions as $tKey => &$tOption) {
					$tOptionInput = [
						'questionchoiceid' => string::generateUuid(),
						'questionid' => $tInput['questionid'],
						'content' => $tOption,
						'type' => $tOptionTypes[$tKey]
					];

					$this->questionModel->insertChoice($tOptionInput);
				}
			}

			$tSurveyQuestion = [
				'questionid' => $tInput['questionid'],
				'surveyid' => $uSurveyId,
				'revision' => $uRevision
			];
			
			$this->load('surveyquestionModel');
			$this->surveyquestionModel->insert($tSurveyQuestion);
		}

		/**
		 * new survey publish page
		 *
		 * @param $uSurveyId string the uuid represents survey id
		 */
		public function get_publish($uSurveyId = null) {
			// load and validate session data
			statics::requireAuthentication(1);

			try {
				// validate the request: survey id
				if(!is_null($uSurveyId)) {
					contracts::isUuid($uSurveyId)->exception('invalid survey id format');
					// not necessary to verify ownership of specified survey since it's only affects choice in dropdown list
				}

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
		 */
		public function post_publish() {
			// load and validate session data
			statics::requireAuthentication(1);

			try {
				// validate the request
				$tInput = http::postArray(['surveyid', 'ownerid', 'password', 'type', 'enabled']);
				$tInput['surveypublishid'] = string::generateUuid();
				$tInput['ownerid'] = statics::$user['userid'];
				$tInput['startdate'] = time::convert(http::post('startdate'), 'd.m.Y', 'Y-m-d H:i:s');

				$tEndDate = http::post('enddate');
				$tInput['enddate'] = (strlen($tEndDate) > 0) ? time::convert($tEndDate, 'd.m.Y', 'Y-m-d H:i:s') : null;

				$tInput['userlimit'] = intval(http::post('userlimit', '0'));
				if($tInput['userlimit'] < 0) {
					$tInput['userlimit'] = 0;
				}

				contracts::isUuid($tInput['surveyid'])->exception('invalid survey id format');

				// gather all survey data from model
				$this->load('surveyModel');
				$tSurvey = $this->surveyModel->get($tInput['surveyid']);
				contracts::isNotFalse($tSurvey)->exception('invalid survey id');
				contracts::isEqual($tSurvey['ownerid'], statics::$user['userid'])->exception('unauthorized access');
				
				$tInput['revision'] = $tSurvey['lastrevision'];

				$this->load('surveypublishModel');
				$this->surveypublishModel->insert($tInput);

				// set an error message to be passed thru session if an exception occurred.
				session::setFlash('notification', ['success', 'Survey published successfully']);
				
				// redirect user to parent page in order to display message
				mvc::redirect('surveys/index');
				return;
			}
			catch(Exception $ex) {
				// set an error message to be passed thru session if an exception occurred.
				session::setFlash('notification', ['error', 'Error: ' . $ex->getMessage()]);

				// redirect user to parent page in order to display error message
				mvc::redirect('surveys/index');
				return;
			}
				
			$this->view();
		}

		/**
		 * edit survey publish page
		 *
		 * @param $uSurveyId string the uuid represents survey publish id
		 */
		public function get_editpublish($uSurveyPublishId) {
			// load and validate session data
			statics::requireAuthentication(1);

			try {
				// validate the request
				contracts::isUuid($uSurveyPublishId)->exception('invalid survey publish id format');

				// gather all survey data from model
				$this->load('surveypublishModel');
				$tSurveyPublish = $this->surveypublishModel->get($uSurveyPublishId);
				contracts::isNotFalse($tSurveyPublish)->exception('invalid survey publish id');
				contracts::isEqual($tSurveyPublish['ownerid'], statics::$user['userid'])->exception('unauthorized access');

				// assign the user data to view
				$this->set('surveypublish', $tSurveyPublish);
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
		 * postback method for edit survey publish page
		 *
		 * @param $uSurveyId string the uuid represents survey publish id
		 */
		public function post_editpublish($uSurveyPublishId) {
			// load and validate session data
			statics::requireAuthentication(1);

			try {
				// validate the request
				contracts::isUuid($uSurveyPublishId)->exception('invalid survey publish id format');

				// gather all survey data from model
				$this->load('surveypublishModel');
				$tSurveyPublish = $this->surveypublishModel->get($uSurveyPublishId);
				contracts::isNotFalse($tSurveyPublish)->exception('invalid survey publish id');
				contracts::isEqual($tSurveyPublish['ownerid'], statics::$user['userid'])->exception('unauthorized access');

				// validate the request
				$tInput = http::postArray(['password', 'type', 'enabled']);
				$tInput['startdate'] = time::convert(http::post('startdate'), 'd.m.Y', 'Y-m-d H:i:s');

				$tEndDate = http::post('enddate');
				$tInput['enddate'] = (strlen($tEndDate) > 0) ? time::convert($tEndDate, 'd.m.Y', 'Y-m-d H:i:s') : null;

				$tInput['userlimit'] = intval(http::post('userlimit', '0'));
				if($tInput['userlimit'] < 0) {
					$tInput['userlimit'] = 0;
				}

				$this->surveypublishModel->update($uSurveyPublishId, $tInput);

				// set an error message to be passed thru session if an exception occurred.
				session::setFlash('notification', ['success', 'Survey publish edited successfully']);
			}
			catch(Exception $ex) {
				// set an error message to be passed thru session if an exception occurred.
				session::setFlash('notification', ['error', 'Error: ' . $ex->getMessage()]);
			}	

			// redirect user to parent page in order to display related messages
			mvc::redirect('surveys/index');
			return;
		}

		/**
		 * share survey publish page
		 *
		 * @param $uSurveyId string the uuid represents survey publish id
		 */
		public function get_share($uSurveyPublishId) {
			// load and validate session data
			statics::requireAuthentication(1);

			try {
				// validate the request
				contracts::isUuid($uSurveyPublishId)->exception('invalid survey publish id format');

				// gather all survey data from model
				$this->load('surveypublishModel');
				$tSurveyPublish = $this->surveypublishModel->get($uSurveyPublishId);
				contracts::isNotFalse($tSurveyPublish)->exception('invalid survey publish id');
				contracts::isEqual($tSurveyPublish['ownerid'], statics::$user['userid'])->exception('unauthorized access');

				// assign the user data to view
				$this->set('surveypublish', $tSurveyPublish);
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
		 * take survey page
		 *
		 * @param $uSurveyPublishId string the uuid represents survey publish id
		 */
		public function get_take($uSurveyPublishId) {
			// load and validate session data
			statics::requireAuthentication(0);

			try {
				// validate the request: survey publish id
				contracts::isUuid($uSurveyPublishId)->exception('invalid survey publish id format');

				// survey visitor handling
				$this->load('surveyvisitorModel');
				$tExistingSurveyVisitor = $this->surveyvisitorModel->get(session::$id);
				if($tExistingSurveyVisitor !== false) {
					throw new Exception('dolmus o anket');
				}

				// gather all survey data from model
				$this->load('surveypublishModel');
				$tSurvey = $this->surveypublishModel->get($uSurveyPublishId);
				$this->setRef('surveys', $tSurvey);

				$this->load('questionModel');
				$tQuestions = $this->questionModel->getBySurveyID($tSurvey['surveyid'], $tSurvey['revision']);
				$tQuestionIds = arrays::column($tQuestions, 'questionid');
				$this->setRef('questions', $tQuestions);

				$tChoices = $this->questionModel->getChoicesByQuestionIDs($tQuestionIds);
				$this->setRef('choices', $tChoices);

				$this->load('themeModel');
				$tTheme = $this->themeModel->get($tSurvey['themeid']);
				$this->setRef('theme', $tTheme);
			}
			catch(Exception $ex) {
				// set an error message to be passed thru session if an exception occurred.
				session::setFlash('notification', ['error', 'Error: ' . $ex->getMessage()]);
			}

			// render the page
			$this->view();
		}

		/**
		 * postback method for take survey page
		 *
		 * @param $uSurveyPublishId string the uuid represents survey publish id
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
