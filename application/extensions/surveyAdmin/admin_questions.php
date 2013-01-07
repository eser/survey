<?php

	use Scabbia\controllers;

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
			auth::checkRedirect('admin');
			self::questionList();
		}

		public static function questionList() {
			auth::checkRedirect('admin');
			$questionModel = controllers::load('questionModel');
			views::set('questions', $questionModel->getAll());
			views::view('surveyAdmin/questions/list.cshtml');
		}

		public static function add() {
			$viewBag = array();
			auth::checkRedirect('admin');
			$questionModel = controllers::load('questionModel');
			$userModel = controllers::load('userModel');
			if(http::$method == 'post') {
				$input = array(
					'content' => http::post('content'),
					'type' => http::post('type'),
					'isshared' => http::post('isshared'),
					'typefilter' => http::post('typefilter'),
					'ownerid' => http::post('ownerid')
					 );
				$questionID = http::post('questionid');
				if($questionID != null && contracts::isUuid($questionID)->check()) {
					$viewBag['message'] = $questionModel->update($questionID, $input).' Record Updated';
				}
				else {
					$input['questionid'] = string::generateUuid();
					$viewBag['message'] = $questionModel->insert($input).' Record Inserted';
				}
			}
			$viewBag['questions'] = $questionModel->getAll();
			$viewBag['users'] = $userModel->getAll();
			views::view('surveyAdmin/questions/add.cshtml', $viewBag);
		}

		public static function delete($actionName, $questionID) {
			$viewBag = array();
			auth::checkRedirect('admin');
			$questionModel = controllers::load('questionModel');
			if(contracts::isUuid($questionID)->check()) {
				$viewBag['message'] = $questionModel->delete($questionID).' Record Deleted';
			}
			$viewBag['questions'] = $questionModel->getAll();
			views::view('surveyAdmin/questions/list.cshtml', $viewBag);
		}

		public static function edit($actionName, $questionID) {
			$viewBag = array();
			auth::checkRedirect('admin');
			$questionModel = controllers::load('questionModel');
			$userModel = controllers::load('userModel');
			$viewBag = array(
				'question' => $questionModel->get($questionID),
				'users' => $userModel->getAll()
				);
			views::view('surveyAdmin/questions/add.cshtml', $viewBag);
		
		}

		public static function choices($actionName, $questionID) {
			auth::checkRedirect('admin');
			$viewBag = array();
			$questionModel = controllers::load('questionModel');
			$viewBag['question'] = $questionModel->get($questionID);
			$viewBag['choices'] = $questionModel->getChoicesByQuestionID($questionID);
			views::view('surveyAdmin/questions/choices.cshtml', $viewBag);
		}

		public static function addChoice($actionName, $questionID) {
			$viewBag = array();
			auth::checkRedirect('admin');
			$questionModel = controllers::load('questionModel');
			if(http::$method == 'post') {
				$input = array(
					'content' => http::post('content'),
					'type' => http::post('type'),
					'questionid' => $questionID
					 );
				$questionChoiceID = http::post('questionchoiceid');
				if($questionChoiceID != null && contracts::isUuid($questionChoiceID)->check()) {
					$viewBag['message'] = $questionModel->updateChoice($questionChoiceID, $input).' Record Updated';
				}
				else {
					$input['questionchoiceid'] = string::generateUuid();
					$viewBag['message'] = $questionModel->insertChoice($input).' Record Inserted';
				}
			}
			$viewBag['question'] = $questionModel->get($questionID);
			views::view('surveyAdmin/questions/addChoice.cshtml', $viewBag);
		}

		public static function editChoice($actionName, $questionID, $questionChoiceID) {
			$viewBag = array();
			auth::checkRedirect('admin');
			$questionModel = controllers::load('questionModel');
			$viewBag = array(
				'question' => $questionModel->get($questionID),
				'choice' => $questionModel->getChoice($questionChoiceID)
				);
			views::view('surveyAdmin/questions/addChoice.cshtml', $viewBag);
		
		}

		public static function deleteChoice($actionName, $questionID, $questionChoiceID) {
			$viewBag = array();
			auth::checkRedirect('admin');
			$questionModel = controllers::load('questionModel');
			if(contracts::isUuid($questionChoiceID)->check()) {
				$viewBag['message'] = $questionModel->deleteChoice($questionChoiceID).' Record Deleted';
			}
			$viewBag['question'] = $questionModel->get($questionID);
			$viewBag['choices'] = $questionModel->getChoicesByQuestionID($questionID);
			views::view('surveyAdmin/questions/choices.cshtml', $viewBag);
		}


	}


?>