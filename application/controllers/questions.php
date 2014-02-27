<?php

    namespace App\Controllers;

    use App\Includes\Statics;
    use App\Includes\SurveyController;
	use Scabbia\Extensions\Mvc\Controller;
	use Scabbia\Extensions\Validation\Contracts;
	use Scabbia\Extensions\Mvc\Mvc;
	use Scabbia\Extensions\Session\Session;
	use Scabbia\Extensions\Http\Http;
    use Scabbia\Request;

	/**
	 * questions controller
	 * action methods for all questions/* urls
	 */
	class Questions extends SurveyController {
		/**
		 * number of questions per page
		 */
		const PAGE_SIZE = Statics::DEFAULT_PAGE_SIZE;

		/**
		 * lists all questions created by user
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
				$this->load('App\\Models\\QuestionModel');
				$this->set('pagerTotal', $this->questionModel->countByOwner(Statics::$user['userid']));
				$this->setRef('pagerCurrent', $tPage);

				// pass question data to view
				$tQuestions = $this->questionModel->getAllPagedByOwner(Statics::$user['userid'], $tOffset, self::PAGE_SIZE);
				$this->setRef('questions', $tQuestions);
			}
			catch(Exception $ex) {
				// set an error message to be passed thru session if an exception occurred.
				Session::set('notification', ['error', 'Error: ' . $ex->getMessage()]);

				// redirect user to parent page in order to display error message
				Http::redirect('questions/index');
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
			Statics::requireAuthentication(1);

			// render the page
			$this->view();
		}

		/**
		 * postback method for new question page
		 */
		public function post_new() {
			// load and validate session data
			Statics::requireAuthentication(1);

			try {
				// construct values for the record
				$tInput = Request::postArray(['content', 'type', 'typefilter', 'isshared', 'enabled']);
				$tInput['questionid'] = String::generateUuid();
				$tInput['ownerid'] = Statics::$user['userid'];

				// validate the request
				Contracts::lengthMinimum($tInput['content'], 3)->exception('question length must be 3 at least');
				Contracts::inKeys($tInput['type'], Statics::$questiontypes)->exception('question type is invalid');
				Contracts::inKeys($tInput['typefilter'], Statics::$questiontypefilters)->exception('question type filter is invalid');
				Contracts::inKeys($tInput['isshared'], Statics::$sharedboolean)->exception('accessibility is invalid');
				Contracts::inKeys($tInput['enabled'], Statics::$surveystatus)->exception('enabled is invalid');

				if($tInput['type'] == Statics::QUESTION_MULTIPLE) {
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
				if($tInput['type'] == Statics::QUESTION_MULTIPLE) {
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

				Session::set('notification', ['success', 'Question has added successfully']);
			}
			catch(Exception $ex) {
				// set an error message to be passed thru session if an exception occurred.
				Session::set('notification', ['error', 'Error: ' . $ex->getMessage()]);
			}

			// redirect user to parent page in order to display notifications
			Http::redirect('questions/index');
		}

		/**
		 * edit question page
		 *
		 * @param $uQuestionId string the uuid represents question id
		 */
		public function get_edit($uQuestionId) {
			// load and validate session data
			Statics::requireAuthentication(1);

			try {
				// validate the request: question id
				Contracts::isUuid($uQuestionId)->exception('invalid question id format');

				// gather all question data from model
				$this->load('App\\Models\\QuestionModel');
				$tQuestion = $this->questionModel->get($uQuestionId);
				Contracts::isNotFalse($tQuestion)->exception('invalid question id');
				Contracts::isEqual($tQuestion['ownerid'], Statics::$user['userid'])->exception('unauthorized access');
				$this->setRef('question', $tQuestion);

				// ...and it's options, if it's a multiple choice question
				if($tQuestion['type'] == Statics::QUESTION_MULTIPLE) {
					$tQuestionChoices = $this->questionModel->getAllChoices($uQuestionId);
					$this->setRef('questionchoices', $tQuestionChoices);
				}
			}
			catch(Exception $ex) {
				// set an error message to be passed thru session if an exception occurred.
				Session::set('notification', ['error', 'Error: ' . $ex->getMessage()]);

				// redirect user to parent page in order to display error message
				Http::redirect('questions/index');
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
			Statics::requireAuthentication(1);

			try {
				// validate the request: question id
				Contracts::isUuid($uQuestionId)->exception('invalid question id format');

				// check record
				$this->load('questionModel');
				$tQuestion = $this->questionModel->get($uQuestionId);
				Contracts::isNotFalse($tQuestion)->exception('invalid question id');
				Contracts::isEqual($tQuestion['ownerid'], Statics::$user['userid'])->exception('unauthorized access');

				// construct values for the record
				$tInput = Request::postArray(['content', 'type', 'typefilter', 'isshared', 'enabled']);

				// validate values
				Contracts::lengthMinimum($tInput['content'], 3)->exception('question length must be 3 at least');
				Contracts::inKeys($tInput['type'], Statics::$questiontypes)->exception('question type is invalid');
				Contracts::inKeys($tInput['typefilter'], Statics::$questiontypefilters)->exception('question type filter is invalid');
				Contracts::inKeys($tInput['isshared'], Statics::$sharedboolean)->exception('accessibility is invalid');
				Contracts::inKeys($tInput['enabled'], Statics::$surveystatus)->exception('enabled is invalid');

				if($tInput['type'] == Statics::QUESTION_MULTIPLE) {
					$tOptions = Request::post('options');
					$tOptionTypes = Request::post('optiontypes');

					foreach($tOptions as $tKey => &$tOption) {
						Contracts::lengthMinimum($tOption, 1)->exception('a question choice length must be 3 at least');
						Contracts::inKeys($tOptionTypes[$tKey], Statics::$questionoptiontypes)->exception('a question choice type is invalid');
					}
				}

				// update the record
				$this->questionModel->update($tQuestion['questionid'], $tInput);

				// wipe the previous question choices anyway 
				$this->questionModel->truncateChoices($uQuestionId);

				// insert question choices if and only if question's type is multiple choice
				if($tInput['type'] == Statics::QUESTION_MULTIPLE) {
					$tOptions = Request::post('options');
					$tOptionTypes = Request::post('optiontypes');

					// loop for each option of question
					foreach($tOptions as $tKey => &$tOption){
						// determine whether question choice were exist before or not by looking its key
						$tQuestionChoiceId = ((!is_integer($tKey)) ? $tKey : String::generateUuid());

						$tOptionInput = [
							'questionchoiceid' => $tQuestionChoiceId,
							'questionid' => $tQuestion['questionid'],
							'content' => $tOption,
							'type' => $tOptionTypes[$tKey]
						];

						$this->questionModel->insertChoice($tOptionInput);
					}
				}

				Session::set('notification', ['success', 'Question has edited successfully']);
			}
			catch(Exception $ex) {
				// set an error message to be passed thru session if an exception occurred.
				Session::set('notification', ['error', 'Error: ' . $ex->getMessage()]);
			}

			// redirect user to parent page in order to display notifications
			Http::redirect('questions/index');
		}
	}

?>
