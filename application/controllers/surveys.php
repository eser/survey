<?php

    namespace App\Controllers;

    use App\Includes\Statics;
    use App\Includes\SurveyController;
	use Scabbia\Extensions\Mvc\Controller;
	use Scabbia\Extensions\Validation\Contracts;
	use Scabbia\Extensions\Helpers\Arrays;
	use Scabbia\Extensions\Session\Session;
	use Scabbia\Extensions\Mvc\Mvc;
	use Scabbia\Extensions\Helpers\Date;
	use Scabbia\Extensions\Http\Http;
    use Scabbia\Request;

	/**
	 * surveys controller
	 * action methods for all surveys/* urls
	 */
	class Surveys extends SurveyController {
		/**
		 * number of surveys per page
		 */
		const PAGE_SIZE = Statics::DEFAULT_PAGE_SIZE;

		/**
		 * lists all surveys created by user
		 *
		 * @param $uPage int number of page which is going to be displayed
		 */
		public function get_index($uPage = '1') {
			// load and validate session data
			Statics::requireAuthentication(1);

			try {
				// validate the request: page numbers
				$tPage = intval($uPage);
				Contracts::isMinimum($tPage, 1)->exception('invalid page number');
				$tOffset = ($tPage - 1) * self::PAGE_SIZE;

				// pass pager data to view
				$this->load('App\\Models\\SurveyModel');
				$this->set('pagerTotal', $this->surveyModel->countByOwner(Statics::$user['userid']));
				$this->setRef('pagerCurrent', $tPage);

				// gather all survey data from model
				$tSurveys = $this->surveyModel->getAllPagedByOwner(Statics::$user['userid'], $tOffset, self::PAGE_SIZE);
				$this->setRef('surveys', $tSurveys);

				// construct an array of survey ids in order to use it to enquiry published surveys
				$tSurveyIds = arrays::column($tSurveys, 'surveyid');

				// gather specified published survey data from model and group them by survey id
				$this->load('App\\Models\\SurveypublishModel');
				$tSurveyPublishs = arrays::categorize($this->surveypublishModel->getAllBySurvey($tSurveyIds), 'surveyid');
				$this->setRef('surveypublishs', $tSurveyPublishs);

				// get all survey names from database
				$tAllSurveyNames = $this->surveyModel->getAllNamesByOwner(Statics::$user['userid']);
				$this->setRef('surveynames', $tAllSurveyNames);
			}
			catch(Exception $ex) {
				// set an error message to be passed thru session if an exception occurred.
				Session::set('notification', ['error', 'Error: ' . $ex->getMessage()]);

				// redirect user to parent page in order to display error message
				Http::redirect('surveys/index');
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
			Statics::requireAuthentication(1);

			// render the page
			$this->view();
		}

		/**
		 * postback method for new survey page
		 */
		public function post_new() {
			// load and validate session data
			Statics::requireAuthentication(1);

			try {
				// construct values for the record
				$tInput = Request::postArray(['title', 'description', 'categoryid', 'themeid', 'languageid']);
				$tInput['surveyid'] = String::generateUuid();
				$tInput['ownerid'] = Statics::$user['userid'];

				// validate values
				Contracts::lengthMinimum($tInput['title'], 3)->exception('title length must be 3 at least');
				Contracts::lengthMinimum($tInput['description'], 3)->exception('description length must be 3 at least');
				Contracts::isUuid($tInput['categoryid'])->exception('invalid category id format');
				Contracts::inKeys($tInput['categoryid'], Statics::$categoriesWithCounts)->exception('invalid category id');
				Contracts::isUuid($tInput['themeid'])->exception('invalid theme id format');
				Contracts::inKeys($tInput['themeid'], Statics::$themesWithCounts)->exception('invalid theme id');
				Contracts::length($tInput['languageid'], 2)->exception('invalid language id format');
				Contracts::inKeys($tInput['languageid'], Statics::$languagesWithCounts)->exception('invalid language id');

				// insert the record into database
				$this->load('App\\Models\\SurveyModel');
				$this->surveyModel->insert($tInput);

				Session::set('notification', ['success', 'Survey has added successfully']);
			}
			catch(Exception $ex) {
				// set an error message to be passed thru session if an exception occurred.
				Session::set('notification', ['error', 'Error: ' . $ex->getMessage()]);
			}

			Http::redirect('surveys/index');
		}

		/**
		 * edit survey page
		 *
		 * @param $uSurveyId string the uuid represents survey id
		 */
		public function get_edit($uSurveyId) {
			// load and validate session data
			Statics::requireAuthentication(1);

			try {
				// validate the request: survey id
				Contracts::isUuid($uSurveyId)->exception('invalid survey id format');

				// gather all survey data from model
				$this->load('App\\Models\\SurveyModel');
				$tSurvey = $this->surveyModel->get($uSurveyId);
				Contracts::isNotFalse($tSurvey)->exception('invalid survey id');
				Contracts::isEqual($tSurvey['ownerid'], Statics::$user['userid'])->exception('unauthorized access');
				$this->setRef('survey', $tSurvey);
			}
			catch(Exception $ex) {
				// set an error message to be passed thru session if an exception occurred.
				Session::set('notification', ['error', 'Error: ' . $ex->getMessage()]);

				// redirect user to parent page in order to display error message
				Http::redirect('surveys/index');
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
			Statics::requireAuthentication(1);

			try {
				// validate the request: question id
				Contracts::isUuid($uSurveyId)->exception('invalid survey id format');

				// check record
				$this->load('App\\Models\\SurveyModel');
				$tSurvey = $this->surveyModel->get($uSurveyId);
				Contracts::isNotFalse($tSurvey)->exception('invalid survey id');
				Contracts::isEqual($tSurvey['ownerid'], Statics::$user['userid'])->exception('unauthorized access');

				// construct values for the record
				$tInput = Request::postArray(['title', 'description', 'categoryid', 'themeid', 'languageid']);

				// validate the request
				Contracts::lengthMinimum($tInput['title'], 3)->exception('title length must be 3 at least');
				Contracts::lengthMinimum($tInput['description'], 3)->exception('description length must be 3 at least');
				Contracts::isUuid($tInput['categoryid'])->exception('invalid category id format');
				Contracts::inKeys($tInput['categoryid'], Statics::$categoriesWithCounts)->exception('invalid category id');
				Contracts::isUuid($tInput['themeid'])->exception('invalid theme id format');
				Contracts::inKeys($tInput['themeid'], Statics::$themesWithCounts)->exception('invalid theme id');
				Contracts::length($tInput['languageid'], 2)->exception('invalid language id format');
				Contracts::inKeys($tInput['languageid'], Statics::$languagesWithCounts)->exception('invalid language id');

				// update the record
				$this->surveyModel->update($uSurveyId, $tInput);
				Session::set('notification', ['success', 'Survey has edited successfully']);
			}
			catch(Exception $ex) {
				// set an error message to be passed thru session if an exception occurred.
				Session::set('notification', ['error', 'Error: ' . $ex->getMessage()]);
			}

			Http::redirect('surveys/index');
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
			Statics::requireAuthentication(1);

			try {
				// validate the request: survey id
				Contracts::isUuid($uSurveyId)->exception('invalid survey id format');

				// gather all survey data from model
				$this->load('App\\Models\\SurveyModel');
				$tSurvey = $this->surveyModel->get($uSurveyId);
				Contracts::isNotFalse($tSurvey)->exception('invalid survey id');
				Contracts::isEqual($tSurvey['ownerid'], Statics::$user['userid'])->exception('unauthorized access');
				$this->setRef('survey', $tSurvey);

				if($uTag == 'remove') {
					// validate the request: survey id
					Contracts::isUuid($uSecondTag)->exception('invalid questionid id format');

					// revision handling
					$tNextRevision = $this->post_questions_revisionwork($tSurvey['surveyid'], $tSurvey['lastrevision']);

					// remove a question
					$this->load('App\\Models\\SurveyquestionModel');
					$this->surveyquestionModel->delete($tSurvey['surveyid'], $tNextRevision, $uSecondTag);

					// set an notification message to be passed thru session.
					Session::set('notification', ['success', 'Question is successfully removed']);

					Http::redirect('surveys/questions/' . $tSurvey['surveyid']);

					return;
				}
				
				// gather all questions from data model
				$this->load('App\\Models\\QuestionModel');
				$tQuerySuggestions = [];
				foreach($this->questionModel->getAllAccessibleExcept(Statics::$user['userid'], $tSurvey['surveyid'], $tSurvey['lastrevision']) as $tRow) {
					$tQuerySuggestions[] = ['hiddenvalue' => $tRow['questionid'], 'label' => $tRow['content']];
				}
				$this->setRef('querySuggestions', $tQuerySuggestions);

				$tQuestions = $this->questionModel->getBySurveyID($tSurvey['surveyid'], $tSurvey['lastrevision']);
				$tQuestionIds = arrays::column($tQuestions, 'questionid');
				$this->setRef('questions', $tQuestions);

				if(count($tQuestionIds)) {
					$tChoices = $this->questionModel->getChoicesByQuestionIDs($tQuestionIds);
				}
				else {
					$tChoices = [];
				}
				$this->setRef('choices', $tChoices);
			}
			catch(Exception $ex) {
				// set an error message to be passed thru session if an exception occurred.
				Session::set('notification', ['error', 'Error: ' . $ex->getMessage()]);

				// redirect user to parent page in order to display error message
				Http::redirect('surveys/index');
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
			Statics::requireAuthentication(1);

			try {
				// validate the request: survey id
				Contracts::isUuid($uSurveyId)->exception('invalid survey id format');

				// gather all survey data from model
				$this->load('App\\Models\\SurveyModel');
				$tSurvey = $this->surveyModel->get($uSurveyId);
				Contracts::isNotFalse($tSurvey)->exception('invalid survey id');
				Contracts::isEqual($tSurvey['ownerid'], Statics::$user['userid'])->exception('unauthorized access');
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
				Session::set('notification', ['error', 'Error: ' . $ex->getMessage()]);
			}

			// render the page
			Http::redirect('surveys/questions/' . $uSurveyId);
		}

		/**
		 * a subroutine for post_questions
		 */
		private function &post_questions_revisionwork($uSurveyId, $uLastRevision) {
			$this->load('App\\Models\\SurveypublishModel');
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
					'createdate' => Date::toDb(time()),
					'ownerid' => Statics::$user['userid'],
					'details' => ''
				];

				$this->load('App\\Models\\SurveyrevisionModel');
				$this->surveyrevisionModel->insert($tNewRevision);

				// transfer previous revision's items to current one.
				$this->load('App\\Models\\SurveyquestionModel');
				$this->surveyquestionModel->transferQuestions($uSurveyId, $tLastRevision, $tNextRevision);
			}

			return $tNextRevision;
		}

		/**
		 * a subroutine for post_questions
		 */
		private function post_questions_questionpool($uSurveyId, $uRevision) {
			$tSurveyQuestion = Request::postArray(['questionid']);
			$tSurveyQuestion['surveyid'] = $uSurveyId;
			$tSurveyQuestion['revision'] = $uRevision;

			// validate the request
			$this->load('App\\Models\\QuestionModel');
			Contracts::isUuid($tSurveyQuestion['questionid'])->exception('invalid question id format');
			$tQuestion = $this->questionModel->get($tSurveyQuestion['questionid']);
			Contracts::isNotFalse($tQuestion)->exception('invalid question id');
			if($tQuestion['isshared'] != Statics::SHARE_SHARED) {
				Contracts::isEqual($tQuestion['ownerid'], Statics::$user['userid'])->exception('unauthorized access');
			}

			//TODO check for question repetation
			
			$this->load('App\\Models\\SurveyquestionModel');
			$this->surveyquestionModel->insert($tSurveyQuestion);
		}

		private function post_questions_other($uType, $uSurveyId, $uRevision) {
			// construct values for the record
			$tInput = Request::postArray(['content', 'typefilter', 'isshared', 'enabled']);
			$tInput['questionid'] = String::generateUuid();
			$tInput['type'] = $uType;
			$tInput['ownerid'] = Statics::$user['userid'];

			// validate the request
			Contracts::lengthMinimum($tInput['content'], 3)->exception('question length must be 3 at least');
			Contracts::inKeys($uType, Statics::$questiontypes)->exception('question type is invalid');
			if(isset($tInput['typefilter'])) {
				Contracts::inKeys($tInput['typefilter'], Statics::$questiontypefilters)->exception('question type filter is invalid');
			}
			else {
				$tInput['typefilter'] = '0';
			}
			Contracts::inKeys($tInput['isshared'], Statics::$sharedboolean)->exception('accessibility is invalid');

			if($uType == Statics::QUESTION_MULTIPLE) {
				$tOptions = Request::post('options');
				$tOptionTypes = Request::post('optiontypes');

				foreach($tOptions as $tKey => &$tOption) {
					Contracts::lengthMinimum($tOption, 1)->exception('a question choice length must be 3 at least');
					Contracts::inKeys($tOptionTypes[$tKey], Statics::$questionoptiontypes)->exception('a question choice type is invalid');
				}
			}

			// insert the record into database
			$this->load('App\\Models\\QuestionModel');
			$this->questionModel->insert($tInput);

			// insert question choices if and only if question's type is multiple choice
			if($uType == Statics::QUESTION_MULTIPLE) {
				// loop for each option of question
				foreach($tOptions as $tKey => &$tOption) {
					$tOptionInput = [
						'questionchoiceid' => String::generateUuid(),
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
			
			$this->load('App\\Models\\SurveyquestionModel');
			$this->surveyquestionModel->insert($tSurveyQuestion);
		}

		/**
		 * new survey publish page
		 *
		 * @param $uSurveyId string the uuid represents survey id
		 */
		public function get_publish($uSurveyId = null) {
			// load and validate session data
			Statics::requireAuthentication(1);

			try {
				// validate the request: survey id
				if(!is_null($uSurveyId)) {
					Contracts::isUuid($uSurveyId)->exception('invalid survey id format');
					// not necessary to verify ownership of specified survey since it's only affects choice in dropdown list
				}

				$this->setRef('surveyid', $uSurveyId);

				// gather all survey data from model
				$this->load('App\\Models\\SurveyModel');
				$tSurveys = $this->surveyModel->getAllByOwner(Statics::$user['userid']);
				$this->setRef('surveys', $tSurveys);
			}
			catch(Exception $ex) {
				// set an error message to be passed thru session if an exception occurred.
				Session::set('notification', ['error', 'Error: ' . $ex->getMessage()]);

				// redirect user to parent page in order to display error message
				Http::redirect('surveys/index');
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
			Statics::requireAuthentication(1);

			try {
				// validate the request
				$tInput = Request::postArray(['surveyid', 'ownerid', 'password', 'type', 'enabled']);
				$tInput['surveypublishid'] = String::generateUuid();
				$tInput['ownerid'] = Statics::$user['userid'];
				$tInput['startdate'] = Date::convert(Request::post('startdate'), 'd.m.Y', 'Y-m-d H:i:s');

				$tEndDate = Request::post('enddate');
				$tInput['enddate'] = (strlen($tEndDate) > 0) ? Date::convert($tEndDate, 'd.m.Y', 'Y-m-d H:i:s') : null;

				$tInput['userlimit'] = intval(Request::post('userlimit', '0'));
				if($tInput['userlimit'] < 0) {
					$tInput['userlimit'] = 0;
				}

				Contracts::isUuid($tInput['surveyid'])->exception('invalid survey id format');

				// gather all survey data from model
				$this->load('App\\Models\\QurveyModel');
				$tSurvey = $this->surveyModel->get($tInput['surveyid']);
				Contracts::isNotFalse($tSurvey)->exception('invalid survey id');
				Contracts::isEqual($tSurvey['ownerid'], Statics::$user['userid'])->exception('unauthorized access');
				
				$tInput['revision'] = $tSurvey['lastrevision'];

				$this->load('App\\Models\\SurveypublishModel');
				$this->surveypublishModel->insert($tInput);

				// set an error message to be passed thru session if an exception occurred.
				Session::set('notification', ['success', 'Survey published successfully']);
				
				// redirect user to parent page in order to display message
				Http::redirect('surveys/index');
				return;
			}
			catch(Exception $ex) {
				// set an error message to be passed thru session if an exception occurred.
				Session::set('notification', ['error', 'Error: ' . $ex->getMessage()]);

				// redirect user to parent page in order to display error message
				Http::redirect('surveys/index');
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
			Statics::requireAuthentication(1);

			try {
				// validate the request
				Contracts::isUuid($uSurveyPublishId)->exception('invalid survey publish id format');

				// gather all survey data from model
				$this->load('App\\Models\\SurveypublishModel');
				$tSurveyPublish = $this->surveypublishModel->get($uSurveyPublishId);
				Contracts::isNotFalse($tSurveyPublish)->exception('invalid survey publish id');
				Contracts::isEqual($tSurveyPublish['ownerid'], Statics::$user['userid'])->exception('unauthorized access');

				// assign the user data to view
				$this->set('surveypublish', $tSurveyPublish);
			}
			catch(Exception $ex) {
				// set an error message to be passed thru session if an exception occurred.
				Session::set('notification', ['error', 'Error: ' . $ex->getMessage()]);

				// redirect user to parent page in order to display error message
				Http::redirect('surveys/index');
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
			Statics::requireAuthentication(1);

			try {
				// validate the request
				Contracts::isUuid($uSurveyPublishId)->exception('invalid survey publish id format');

				// gather all survey data from model
				$this->load('App\\Models\\SurveypublishModel');
				$tSurveyPublish = $this->surveypublishModel->get($uSurveyPublishId);
				Contracts::isNotFalse($tSurveyPublish)->exception('invalid survey publish id');
				Contracts::isEqual($tSurveyPublish['ownerid'], Statics::$user['userid'])->exception('unauthorized access');

				// validate the request
				$tInput = Request::postArray(['password', 'type', 'enabled']);
				$tInput['startdate'] = Date::convert(Request::post('startdate'), 'd.m.Y', 'Y-m-d H:i:s');

				$tEndDate = Request::post('enddate');
				$tInput['enddate'] = (strlen($tEndDate) > 0) ? Date::convert($tEndDate, 'd.m.Y', 'Y-m-d H:i:s') : null;

				$tInput['userlimit'] = intval(Request::post('userlimit', '0'));
				if($tInput['userlimit'] < 0) {
					$tInput['userlimit'] = 0;
				}

				$this->surveypublishModel->update($uSurveyPublishId, $tInput);

				// set an error message to be passed thru session if an exception occurred.
				Session::set('notification', ['success', 'Survey publish edited successfully']);
			}
			catch(Exception $ex) {
				// set an error message to be passed thru session if an exception occurred.
				Session::set('notification', ['error', 'Error: ' . $ex->getMessage()]);
			}	

			// redirect user to parent page in order to display related messages
			Http::redirect('surveys/index');
			return;
		}

		/**
		 * share survey publish page
		 *
		 * @param $uSurveyId string the uuid represents survey publish id
		 */
		public function get_share($uSurveyPublishId) {
			// load and validate session data
			Statics::requireAuthentication(1);

			try {
				// validate the request
				Contracts::isUuid($uSurveyPublishId)->exception('invalid survey publish id format');

				// gather all survey data from model
				$this->load('App\\Models\\SurveypublishModel');
				$tSurveyPublish = $this->surveypublishModel->get($uSurveyPublishId);
				Contracts::isNotFalse($tSurveyPublish)->exception('invalid survey publish id');
				Contracts::isEqual($tSurveyPublish['ownerid'], Statics::$user['userid'])->exception('unauthorized access');

				// assign the user data to view
				$this->set('surveypublish', $tSurveyPublish);
			}
			catch(Exception $ex) {
				// set an error message to be passed thru session if an exception occurred.
				Session::set('notification', ['error', 'Error: ' . $ex->getMessage()]);

				// redirect user to parent page in order to display error message
				Http::redirect('surveys/index');
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
			Statics::requireAuthentication(0);

			try {
				// validate the request: survey publish id
				Contracts::isUuid($uSurveyPublishId)->exception('invalid survey publish id format');

				// gather all survey data from model
				$this->load('App\\Models\\SurveypublishModel');
				$tSurveyPublish = $this->surveypublishModel->get($uSurveyPublishId);
				Contracts::isNotFalse($tSurveyPublish)->exception('invalid survey publish id');
				$this->setRef('surveypublish', $tSurveyPublish);

				// survey visitor handling
				$this->load('App\\Models\\UserModel');
				$tUser = $this->userModel->get($tSurveyPublish['ownerid']);
				$this->setRef('user', $tUser);

				// counter
				$this->load('App\\Models\\SurveyvisitorModel');
				$tSurveyPublishCounter = $this->surveyvisitorModel->countBySurveyPublish($tSurveyPublish['surveypublishid']);
				$this->setRef('counter', $tSurveyPublishCounter);

				// is it disabled?
				if($tSurveyPublish['enabled'] == Statics::SURVEY_DISABLED) {
					$this->set('title', 'Survey Status');
					$this->set('message', 'Survey is currently disabled.');

					$this->view('surveys/take_message.cshtml');
					return;
				}

				// is it expired or future?
				$tStartDate = Date::fromDb($tSurveyPublish['startdate']);
				$tEndDate = (!is_null($tSurveyPublish['enddate'])) ? Date::fromDb($tSurveyPublish['enddate']) : null;
				$tToday = Date::today();
				if($tStartDate > $tToday || (!is_null($tEndDate) && $tEndDate < $tToday)) {
					$this->set('title', 'Survey Status');
					$this->set('message', 'The survey is either expired or not opened yet.');

					$this->view('surveys/take_message.cshtml');
					return;
				}

				// has reached the user limit?
				if($tSurveyPublish['userlimit'] != '0') {
					if(!Contracts::isLower($tSurveyPublishCounter, intval($tSurveyPublish['userlimit']))->check()) {
						$this->set('title', 'Survey User Limit');
						$this->set('message', 'User limit is reached for this survey.');

						$this->view('surveys/take_message.cshtml');
						return;
					}
				}

				// is it auth-only?
				$tUserId = ((!is_null(Statics::$user)) ? Statics::$user['userid'] : null);
				if(is_null($tUserId) && $tSurveyPublish['type'] == Statics::SURVEY_AUTHONLY) {
					$this->set('title', 'Survey');
					$this->set('message', 'This survey only accepts authorized users in the system.');

					$this->view('surveys/take_message.cshtml');
					return;
				}

				// already filled up?
				$this->load('App\\Models\\SurveyvisitorModel');
				$tExistingSurveyVisitor = $this->surveyvisitorModel->getBySurveyPublish(Session::$id, $tSurveyPublish['surveypublishid'], $tUserId);
				if($tExistingSurveyVisitor !== false) {
					$this->set('title', 'Survey');
					$this->set('message', 'You have already filled up this survey.');

					$this->view('surveys/take_message.cshtml');
					return;
				}

				// password protection?
				if(strlen($tSurveyPublish['password']) > 0) {
					$tPasswords = Session::get('passwords', []);
					if(!isset($tPasswords[$tSurveyPublish['surveypublishid']])) {
						$this->set('wrongPassword', false);
						$this->view('surveys/take_password.cshtml');
						return;
					}

					if($tPasswords[$tSurveyPublish['surveypublishid']] != $tSurveyPublish['password']) {
						$this->set('wrongPassword', true);
						$this->view('surveys/take_password.cshtml');
						return;
					}
				}

				$this->load('App\\Models\\QuestionModel');
				$tQuestions = $this->questionModel->getBySurveyID($tSurveyPublish['surveyid'], $tSurveyPublish['revision']);
				$tQuestionIds = arrays::column($tQuestions, 'questionid');
				$this->setRef('questions', $tQuestions);

				if(count($tQuestionIds)) {
					$tChoices = $this->questionModel->getChoicesByQuestionIDs($tQuestionIds);
				}
				else {
					$tChoices = [];
				}
				$this->setRef('choices', $tChoices);

				$this->load('App\\Models\\ThemeModel');
				$tTheme = $this->themeModel->get($tSurveyPublish['themeid']);
				$this->setRef('theme', $tTheme);
			}
			catch(Exception $ex) {
				// set an error message to be passed thru session if an exception occurred.
				Session::set('notification', ['error', 'Error: ' . $ex->getMessage()]);
			}

			// render the page
			$this->view();
		}

		/**
		 * wrapper method for take survey page
		 * it simply redirects entered password to the real take page
		 *
		 * @param $uSurveyPublishId string the uuid represents survey publish id
		 */
		public function post_takePwd($uSurveyPublishId) {
			// load and validate session data
			Statics::requireAuthentication(0);

			try {
				// validate the request: survey publish id
				Contracts::isUuid($uSurveyPublishId)->exception('invalid survey publish id format');

				// gather all survey data from model
				$this->load('App\\Models\\SurveypublishModel');
				$tSurveyPublish = $this->surveypublishModel->get($uSurveyPublishId);
				Contracts::isNotFalse($tSurveyPublish)->exception('invalid survey publish id');

				$tPasswords = Session::get('passwords', []);
				$tPasswords[$tSurveyPublish['surveypublishid']] = Request::post('password');
				Session::set('passwords', $tPasswords);

				Http::redirect('surveys/take/' . $tSurveyPublish['surveypublishid']);
				return;
			}
			catch(Exception $ex) {
				// set an error message to be passed thru session if an exception occurred.
				Session::set('notification', ['error', 'Error: ' . $ex->getMessage()]);
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
			// load and validate session data
			Statics::requireAuthentication(0);

			try {
				// validate the request: survey publish id
				Contracts::isUuid($uSurveyPublishId)->exception('invalid survey publish id format');
				
				// gather all survey data from model
				$this->load('App\\Models\\SurveypublishModel');
				$tSurveyPublish = $this->surveypublishModel->get($uSurveyPublishId);
				Contracts::isNotFalse($tSurveyPublish)->exception('invalid survey publish id');
				$this->setRef('surveypublish', $tSurveyPublish);
				
				// is it disabled?
				if($tSurveyPublish['enabled'] == Statics::SURVEY_DISABLED) {
					throw new \Exception('Survey is currently disabled.');
				}
				
				// is it expired or future?
				$tStartDate = Date::fromDb($tSurveyPublish['startdate']);
				$tEndDate = (!is_null($tSurveyPublish['enddate'])) ? Date::fromDb($tSurveyPublish['enddate']) : null;
				$tToday = Date::today();
				if($tStartDate > $tToday || (!is_null($tEndDate) && $tEndDate < $tToday)) {
					throw new \Exception('The survey is either expired or not opened yet.');
				}
				
				// has reached the user limit?
				if($tSurveyPublish['userlimit'] != '0') {
					if(!Contracts::isLower($tSurveyPublishCounter, intval($tSurveyPublish['userlimit']))->check()) {
						throw new \Exception('User limit is reached for this survey.');
					}
				}
				
				// is it auth-only?
				$tUserId = ((!is_null(Statics::$user)) ? Statics::$user['userid'] : null);
				if(is_null($tUserId) && $tSurveyPublish['type'] == Statics::SURVEY_AUTHONLY) {
					throw new \Exception('This survey only accepts authorized users in the system.');
				}
				
				// already filled up?
				$this->load('App\\Models\\SurveyvisitorModel');
				$tExistingSurveyVisitor = $this->surveyvisitorModel->getBySurveyPublish(Session::$id, $tSurveyPublish['surveypublishid'], $tUserId);
				if($tExistingSurveyVisitor !== false) {
					throw new \Exception('You have already filled up this survey.');
				}

				// password protection?
				if(strlen($tSurveyPublish['password']) > 0) {
					$tPasswords = Session::get('passwords', []);
					if(!isset($tPasswords[$tSurveyPublish['surveypublishid']])) {
						throw new \Exception('Password required.');
					}

					if($tPasswords[$tSurveyPublish['surveypublishid']] != $tSurveyPublish['password']) {
						throw new \Exception('Invalid password.');
					}
				}
				$tSurveyVisitor = array(
					'surveyvisitorid' => Session::$id,
					'surveypublishid' => $tSurveyPublish['surveypublishid'],
					'userid' => $tUserId,
					'ip' => $_SERVER['REMOTE_ADDR'],
					'useragent' => $_SERVER['HTTP_USER_AGENT'],
					'recorddate' => Date::toDb(time())
				);
				$this->surveyvisitorModel->insert($tSurveyVisitor);

				$this->load('App\\Models\\QuestionModel');
				$questions = $this->questionModel->getBySurveyID($tSurveyPublish['surveyid'], $tSurveyPublish['revision']);

				$answers = array();
				foreach($questions as $question) {
					$answers[$question['questionid']] = Request::post($question['questionid'], null);
					$answersvalues[$question['questionid']] = Request::post($question['questionid'] . 'value', null);

					$tInput = array(
						'surveypublishid' => $tSurveyPublish['surveypublishid'],
						'questionid' => $question['questionid'],
						'surveyvisitorid' => $tSurveyVisitor['surveyvisitorid'],
						'questionchoiceid' => ($question['type'] == Statics::QUESTION_MULTIPLE) ? $answers[$question['questionid']] : null,
						'value' => $answersvalues[$question['questionid']]
					);

					$this->questionModel->insertAnswer($tInput);
				}

				Session::set('notification', ['success', 'You filled up \'' . $tSurveyPublish['title'] . '\' survey sucessfully.']);
			}
			catch(Exception $ex) {
				// set an error message to be passed thru session if an exception occurred.
				Session::set('notification', ['error', 'Error: ' . $ex->getMessage()]);
			}

			Http::redirect('surveys/take/' . $uSurveyPublishId);
		}

		/**
		 * survey report page
		 *
		 * @param $uSurveyPublishId string the uuid represents survey publish id
		 */
		public function get_report($uSurveyPublishId) {
			// load and validate session data
			Statics::requireAuthentication(1);

			try {
				// validate the request
				Contracts::isUuid($uSurveyPublishId)->exception('invalid survey publish id format');

				// gather all survey data from model
				$this->load('App\\Models\\SurveypublishModel');
				$tSurveyPublish = $this->surveypublishModel->get($uSurveyPublishId);
				Contracts::isNotFalse($tSurveyPublish)->exception('invalid survey publish id');
				$this->setRef('surveypublish', $tSurveyPublish);

				// survey visitor handling
				$this->load('App\\Models\\UserModel');
				$tUser = $this->userModel->get($tSurveyPublish['ownerid']);
				$this->setRef('user', $tUser);

				// counter
				$this->load('App\\Models\\SurveyvisitorModel');
				$tSurveyPublishCounter = $this->surveyvisitorModel->countBySurveyPublish($tSurveyPublish['surveypublishid']);
				$this->setRef('counter', $tSurveyPublishCounter);

				// questions
				$this->load('App\\Models\\QuestionModel');
				$tQuestions = $this->questionModel->getBySurveyID($tSurveyPublish['surveyid'], $tSurveyPublish['revision']);
				$tQuestionIds = arrays::column($tQuestions, 'questionid');
				$this->setRef('questions', $tQuestions);

				if(count($tQuestionIds)) {
					$tChoices = $this->questionModel->getChoicesByQuestionIDs($tQuestionIds);
					$tAnswers = arrays::categorize($this->questionModel->getAnswersByPublishID($tSurveyPublish['surveypublishid']), ['questionid', 'questionchoiceid']);
					$tNewArray = [];

					foreach($tQuestions as &$tQuestion) {
						if(!isset($tNewArray[$tQuestion['questionid']])) {
							$tNewArray[$tQuestion['questionid']] = [
								'content' => $tQuestion['content'],
								'type' => $tQuestion['type'],
								'enabled' => $tQuestion['enabled'],
								'total' => 0,
								'items' => []
							];
						}

						if(isset($tChoices[$tQuestion['questionid']])) {
							foreach($tChoices[$tQuestion['questionid']] as &$tChoice) {
								if(!isset($tNewArray[$tQuestion['questionid']]['items'][$tChoice['questionchoiceid']])) {
									$tNewArray[$tQuestion['questionid']]['items'][$tChoice['questionchoiceid']] = [
										'content' => $tChoice['content'],
										'count' => 0
									];
								}
								if(isset($tAnswers[$tQuestion['questionid']][$tChoice['questionchoiceid']])) {
									$tCount = intval($tAnswers[$tQuestion['questionid']][$tChoice['questionchoiceid']][0]['count']);
									$tNewArray[$tQuestion['questionid']]['items'][$tChoice['questionchoiceid']]['count'] += $tCount;
									$tNewArray[$tQuestion['questionid']]['total'] += $tCount;
								}
							}
						}
					}
				}
				else {
					$tChoices = [];
					$tAnswers = [];
					$tNewArray = [];
				}

				$this->setRef('choices', $tChoices);
				$this->setRef('answers', $tAnswers);
				$this->setRef('newarray', $tNewArray);
			}
			catch(Exception $ex) {
				// set an error message to be passed thru session if an exception occurred.
				Session::set('notification', ['error', 'Error: ' . $ex->getMessage()]);

				// redirect user to parent page in order to display error message
				Http::redirect('surveys/index');
				return;
			}

			// render the page
			$this->view();
		}
	}

?>
