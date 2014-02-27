<?php

	use Scabbia\Extensions\Mvc\Controllers;

	/**
	 * Blackmore Extension: survey-e-bot Admin Panel, questions Section
	 *
	 * @package survey-e-bot
	 * @subpackage admin_users
	 * @version 1.0.2
	 *
	 * @scabbia-fwversion 1.0
	 * @scabbia-fwdepends string, resources, validation, http, auth, zmodels
	 * @scabbia-phpversion 5.2.0
	 * @scabbia-phpdepends
	 */
	class admin_questions {
		/**
		 * @ignore
		 */
		public static function blackmoreRegisterModules($uParms) {
			// disable scabbia routines
			$uParms['modules']['index']['actions'] = array();
			$uParms['modules']['index']['submenus'] = false;

			$uParms['modules']['questions'] = array(
				'title' => 'Questions',
				'callback' => 'admin_questions::index',
				'submenus' => true,
				'actions' => array(
					array(
						'callback' => 'admin_questions::questionList',
						'menutitle' => 'List',
						'action' => 'questionList'
					),
					array(
						'callback' => 'admin_questions::add',
						'menutitle' => 'Add',
						'action' => 'add'
					),
					array(
						'callback' => 'admin_questions::delete',
						'action' => 'delete'
					),
					array(
						'callback' => 'admin_questions::edit',
						'action' => 'edit'
					),
					array(
						'callback' => 'admin_questions::choices',
						'action' => 'choices'
					),
					array(
						'callback' => 'admin_questions::addChoice',
						'action' => 'addChoice'
					),
					array(
						'callback' => 'admin_questions::editChoice',
						'action' => 'editChoice'
					),
					array(
						'callback' => 'admin_questions::deleteChoice',
						'action' => 'deleteChoice'
					)
				)
			);
		}

		/**
		 * @ignore
		 */
		public static function index() {
			Auth::checkRedirect('admin');
			self::questionList();
		}

		public static function questionList() {
			Auth::checkRedirect('admin');
			$questionModel = Controllers::load('App\\Models\\QuestionModel');
			Views::set('questions', $questionModel->getAll());
			Views::viewFile('{app}views/surveyAdmin/questions/list.cshtml');
		}

		public static function add() {
			$viewBag = array();
			Auth::checkRedirect('admin');
			$questionModel = Controllers::load('App\\Models\\QuestionModel');
			$userModel = Controllers::load('App\\Models\\UserModel');
			if(Http::$method == 'post') {
				$input = array(
					'content' => Request::post('content'),
					'type' => Request::post('type'),
					'isshared' => Request::post('isshared'),
					'typefilter' => Request::post('typefilter'),
					'ownerid' => Request::post('ownerid')
					 );
				$questionID = Request::post('questionid');
				if($questionID != null && Contracts::isUuid($questionID)->check()) {
					$viewBag['message'] = $questionModel->update($questionID, $input).' Record Updated';
				}
				else {
					$input['questionid'] = String::generateUuid();
					$viewBag['message'] = $questionModel->insert($input).' Record Inserted';
				}
			}
			$viewBag['questions'] = $questionModel->getAll();
			$viewBag['users'] = $userModel->getAll();
			Views::viewFile('{app}views/surveyAdmin/questions/add.cshtml', $viewBag);
		}

		public static function delete($actionName, $questionID) {
			$viewBag = array();
			Auth::checkRedirect('admin');
			$questionModel = Controllers::load('App\\Models\\QuestionModel');
			if(Contracts::isUuid($questionID)->check()) {
				$viewBag['message'] = $questionModel->delete($questionID).' Record Deleted';
			}
			$viewBag['questions'] = $questionModel->getAll();
			Views::viewFile('{app}views/surveyAdmin/questions/list.cshtml', $viewBag);
		}

		public static function edit($actionName, $questionID) {
			$viewBag = array();
			Auth::checkRedirect('admin');
			$questionModel = Controllers::load('App\\Models\\QuestionModel');
			$userModel = Controllers::load('App\\Models\\UserModel');
			$viewBag = array(
				'question' => $questionModel->get($questionID),
				'users' => $userModel->getAll()
				);
			Views::viewFile('{app}views/surveyAdmin/questions/add.cshtml', $viewBag);
		
		}

		public static function choices($actionName, $questionID) {
			Auth::checkRedirect('admin');
			$viewBag = array();
			$questionModel = Controllers::load('App\\Models\\QuestionModel');
			$viewBag['question'] = $questionModel->get($questionID);
			$viewBag['choices'] = $questionModel->getChoicesByQuestionID($questionID);
			Views::viewFile('{app}views/surveyAdmin/questions/choices.cshtml', $viewBag);
		}

		public static function addChoice($actionName, $questionID) {
			$viewBag = array();
			Auth::checkRedirect('admin');
			$questionModel = Controllers::load('App\\Models\\QuestionModel');
			if(Http::$method == 'post') {
				$input = array(
					'content' => Request::post('content'),
					'type' => Request::post('type'),
					'questionid' => $questionID
					 );
				$questionChoiceID = Request::post('questionchoiceid');
				if($questionChoiceID != null && Contracts::isUuid($questionChoiceID)->check()) {
					$viewBag['message'] = $questionModel->updateChoice($questionChoiceID, $input).' Record Updated';
				}
				else {
					$input['questionchoiceid'] = String::generateUuid();
					$viewBag['message'] = $questionModel->insertChoice($input).' Record Inserted';
				}
			}
			$viewBag['question'] = $questionModel->get($questionID);
			Views::viewFile('{app}views/surveyAdmin/questions/addChoice.cshtml', $viewBag);
		}

		public static function editChoice($actionName, $questionID, $questionChoiceID) {
			$viewBag = array();
			Auth::checkRedirect('admin');
			$questionModel = Controllers::load('App\\Models\\QuestionModel');
			$viewBag = array(
				'question' => $questionModel->get($questionID),
				'choice' => $questionModel->getChoice($questionChoiceID)
				);
			Views::viewFile('{app}views/surveyAdmin/questions/addChoice.cshtml', $viewBag);
		
		}

		public static function deleteChoice($actionName, $questionID, $questionChoiceID) {
			$viewBag = array();
			Auth::checkRedirect('admin');
			$questionModel = Controllers::load('App\\Models\\QuestionModel');
			if(Contracts::isUuid($questionChoiceID)->check()) {
				$viewBag['message'] = $questionModel->deleteChoice($questionChoiceID).' Record Deleted';
			}
			$viewBag['question'] = $questionModel->get($questionID);
			$viewBag['choices'] = $questionModel->getChoicesByQuestionID($questionID);
			Views::viewFile('{app}views/surveyAdmin/questions/choices.cshtml', $viewBag);
		}


	}


?>