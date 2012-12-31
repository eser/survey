<?php

	/**
	 * Blackmore Extension: Kibrissiparis Admin Panel, questions Section
	 *
	 * @package Kibrissiparis
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
		public static function blackmore_registerModules($uParms) {
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
			$questionModel = mvc::load('questionModel');
			views::set('questions', $questionModel->getAll());
			views::view('surveyAdmin/questions/list.cshtml');
		}

		public static function add() {
			$viewBag = array();
			auth::checkRedirect('admin');
			$questionModel = mvc::load('questionModel');
			$userModel = mvc::load('userModel');
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
			$questionModel = mvc::load('questionModel');
			if(contracts::isUuid($questionID)->check()) {
				$result = $questionModel->delete($questionID);
				$viewBag['result'] = $result;
				$viewBag['message'] = 'Record Deleted';
			}
			$viewBag['questions'] = $questionModel->getAll();
			views::view('surveyAdmin/questions/list.cshtml', $viewBag);
		}

		public static function edit($actionName, $questionID) {
			$viewBag = array();
			auth::checkRedirect('admin');
			$questionModel = mvc::load('questionModel');
			$userModel = mvc::load('userModel');
			$viewBag = array(
				'question' => $questionModel->get($questionID),
				'users' => $userModel->getAll()
				);
			views::view('surveyAdmin/questions/add.cshtml', $viewBag);
			
			
		}


	}


?>