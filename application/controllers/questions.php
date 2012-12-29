<?php

	/**
	 * questions controller
	 * action methods for all questions/* urls
	 */
	class questions extends controller {
		/**
		 * number of questions per page
		 */
		const PAGE_SIZE = statics::DEFAULT_PAGE_SIZE;

		/**
		 * lists all questions created by user
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
				$this->load('questionModel');
				$this->set('pagerTotal', $this->questionModel->countByOwner(statics::$user['userid']));
				$this->setRef('pagerCurrent', $tPage);

				// pass question data to view
				$tQuestions = $this->questionModel->getAllPagedByOwner(statics::$user['userid'], $tOffset, self::PAGE_SIZE);
				$this->setRef('questions', $tQuestions);
			}
			catch(Exception $ex) {
				// set an error message to be passed thru session if an exception occurred.
				session::setFlash('notification', ['error', 'Error: ' . $ex->getMessage()]);

				// redirect user to parent page in order to display error message
				mvc::redirect('questions/index');
				return;
			}

			// render the page
			$this->view();
		}

		/**
		 * new question page
		 */
		public function get_new() {
			// load and validate session data
			statics::requireAuthentication(1);

			// render the page
			$this->view();
		}

		/**
		 * postback method for new question page
		 */
		public function post_new() {
			// load and validate session data
			statics::requireAuthentication(1);

			try {
				// construct values for the record
				$tInput = http::postArray(['content', 'type', 'typefilter', 'isshared']);
				$tInput['questionid'] = string::generateUuid();
				$tInput['ownerid'] = statics::$user['userid'];

				// validate the request
				contracts::lengthMinimum($tInput['content'], 3)->exception('question length must be 3 at least');
				contracts::inKeys($tInput['type'], statics::$questiontypes)->exception('question type is invalid');
				contracts::inKeys($tInput['typefilter'], statics::$questiontypefilters)->exception('question type filter is invalid');
				contracts::inKeys($tInput['isshared'], statics::$sharedboolean)->exception('accessibility is invalid');

				if($tInput['type'] == statics::QUESTION_MULTIPLE) {
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
				if($tInput['type'] == statics::QUESTION_MULTIPLE) {
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

				session::setFlash('notification', ['success', 'Question has added successfully']);
			}
			catch(Exception $ex) {
				// set an error message to be passed thru session if an exception occurred.
				session::setFlash('notification', ['error', 'Error: ' . $ex->getMessage()]);
			}

			// redirect user to parent page in order to display notifications
			mvc::redirect('questions/index');
		}

		/**
		 * edit question page
		 *
		 * @param $uQuestionId string the uuid represents question id
		 */
		public function get_edit($uQuestionId) {
			// load and validate session data
			statics::requireAuthentication(1);

			try {
				// validate the request: question id
				contracts::isUuid($uQuestionId)->exception('invalid question id format');

				// gather all question data from model
				$this->load('questionModel');
				$tQuestion = $this->questionModel->get($uQuestionId);
				contracts::isNotFalse($tQuestion)->exception('invalid question id');
				contracts::isEqual($tQuestion['ownerid'], statics::$user['userid'])->exception('unauthorized access');
				$this->setRef('question', $tQuestion);

				// ...and it's options, if it's a multiple choice question
				if($tQuestion['type'] == statics::QUESTION_MULTIPLE) {
					$tQuestionChoices = $this->questionModel->getAllChoices($uQuestionId);
					$this->setRef('questionchoices', $tQuestionChoices);
				}
			}
			catch(Exception $ex) {
				// set an error message to be passed thru session if an exception occurred.
				session::setFlash('notification', ['error', 'Error: ' . $ex->getMessage()]);

				// redirect user to parent page in order to display error message
				mvc::redirect('questions/index');
				return;
			}

			// render the page
			$this->view();
		}

		/**
		 * postback method for edit question page
		 *
		 * @param $uQuestionId string the uuid represents question id
		 */
		public function post_edit($uQuestionId) {
			// load and validate session data
			statics::requireAuthentication(1);

			try {
				// validate the request: question id
				contracts::isUuid($uQuestionId)->exception('invalid question id format');

				// check record
				$this->load('questionModel');
				$tQuestion = $this->questionModel->get($uQuestionId);
				contracts::isNotFalse($tQuestion)->exception('invalid question id');
				contracts::isEqual($tQuestion['ownerid'], statics::$user['userid'])->exception('unauthorized access');

				// construct values for the record
				$tInput = http::postArray(['content', 'type', 'typefilter', 'isshared']);

				// validate values
				contracts::lengthMinimum($tInput['content'], 3)->exception('question length must be 3 at least');
				contracts::inKeys($tInput['type'], statics::$questiontypes)->exception('question type is invalid');
				contracts::inKeys($tInput['typefilter'], statics::$questiontypefilters)->exception('question type filter is invalid');
				contracts::inKeys($tInput['isshared'], statics::$sharedboolean)->exception('accessibility is invalid');

				if($tInput['type'] == statics::QUESTION_MULTIPLE) {
					$tOptions = http::post('options');
					$tOptionTypes = http::post('optiontypes');

					foreach($tOptions as $tKey => &$tOption) {
						contracts::lengthMinimum($tOption, 1)->exception('a question choice length must be 3 at least');
						contracts::inKeys($tOptionTypes[$tKey], statics::$questionoptiontypes)->exception('a question choice type is invalid');
					}
				}

				// update the record
				$this->questionModel->update($tQuestion['questionid'], $tInput);

				// wipe the previous question choices anyway 
				$this->questionModel->truncateChoices($uQuestionId);

				// insert question choices if and only if question's type is multiple choice
				if($tInput['type'] == statics::QUESTION_MULTIPLE) {
					$tOptions = http::post('options');
					$tOptionTypes = http::post('optiontypes');

					// loop for each option of question
					foreach($tOptions as $tKey => &$tOption){
						// determine whether question choice were exist before or not by looking its key
						$tQuestionChoiceId = ((!is_integer($tKey)) ? $tKey : string::generateUuid());

						$tOptionInput = [
							'questionchoiceid' => $tQuestionChoiceId,
							'questionid' => $tInput['questionid'],
							'content' => $tOption,
							'type' => $tOptionTypes[$tKey]
						];

						$this->questionModel->insertChoice($tOptionInput);
					}
				}

				session::setFlash('notification', ['success', 'Question has edited successfully']);
			}
			catch(Exception $ex) {
				// set an error message to be passed thru session if an exception occurred.
				session::setFlash('notification', ['error', 'Error: ' . $ex->getMessage()]);
			}

			// redirect user to parent page in order to display notifications
			mvc::redirect('questions/index');
		}
	}

?>
