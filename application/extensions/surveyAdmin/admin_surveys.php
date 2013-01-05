<?php

	/**
	 * Blackmore Extension: survey-e-bot Admin Panel, surveys Section
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
	class admin_surveys {
		/**
		 * @ignore
		 */
		public static function blackmoreRegisterModules($uParms) {
			$uParms['modules']['surveys'] = array(
				'title' => 'Surveys',
				'callback' => 'admin_surveys::index',
				'submenus' => true,
				'actions' => array(
					
					array(
						'callback' => 'admin_surveys::surveyList',
						'menutitle' => 'List',
						'action' => 'surveyList'
					),
					array(
						'callback' => 'admin_surveys::add',
						'menutitle' => 'Add',
						'action' => 'add'
					),
					array(
						'callback' => 'admin_surveys::delete',
						'action' => 'delete'
					),
					array(
						'callback' => 'admin_surveys::edit',
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
			self::surveyList();
		}

		public static function surveyList() {
			auth::checkRedirect('admin');
			$surveyModel = mvc::load('surveyModel');
			views::set('surveys', $surveyModel->getAllDetailed());
			views::view('surveyAdmin/surveys/list.cshtml');
		}

		public static function add() {
			$viewBag = array();
			auth::checkRedirect('admin');
			$surveyModel = mvc::load('surveyModel');
			$categoryModel = mvc::load('categoryModel');
			$themeModel = mvc::load('themeModel');
			$languageModel = mvc::load('languageModel');
			$userModel = mvc::load('userModel');

			if(http::$method == 'post') {
				$input = array(
					'title' => http::post('title'),
					'ownerid' => http::post('ownerid'),
					'categoryid' => http::post('categoryid'),
					'languageid' => http::post('languageid'),
					'themeid' => http::post('themeid'),
					'languageid' => http::post('languageid'),
					'description' => http::post('description')
					 );
				$surveyID = http::post('surveyid');
				if($surveyID != null && contracts::isUuid($surveyID)->check()) {
					$viewBag['message'] = $surveyModel->update($surveyID, $input).' Record Updated';
				}
				else {
					$input['surveyid'] = string::generateUuid();
					$viewBag['message'] = $surveyModel->insert($input).' Record Inserted';
				}
			}
			$viewBag['users'] = $userModel->getAll();
			$viewBag['categories'] = $categoryModel->getAll();
			$viewBag['themes'] = $themeModel->getAll();
			$viewBag['languages'] = $languageModel->getAll();

			views::view('surveyAdmin/surveys/add.cshtml', $viewBag);
		}

		public static function delete($actionName, $surveyID) {
			$viewBag = array();
			auth::checkRedirect('admin');
			$surveyModel = mvc::load('surveyModel');
			if(contracts::isUuid($surveyID)->check()) {
				$viewBag['message'] = $surveyModel->delete($surveyID).' Record Deleted';
			}
			$viewBag['surveys'] = $surveyModel->getAllDetailed();
			views::view('surveyAdmin/surveys/list.cshtml', $viewBag);
		}

		public static function edit($actionName, $surveyID) {
			$viewBag = array();
			auth::checkRedirect('admin');
			$surveyModel = mvc::load('surveyModel');
			$categoryModel = mvc::load('categoryModel');
			$themeModel = mvc::load('themeModel');
			$languageModel = mvc::load('languageModel');
			$userModel = mvc::load('userModel');
			$viewBag['survey'] = $surveyModel->get($surveyID);
			$viewBag['users'] = $userModel->getAll();
			$viewBag['categories'] = $categoryModel->getAll();
			$viewBag['themes'] = $themeModel->getAll();
			$viewBag['languages'] = $languageModel->getAll();
			views::view('surveyAdmin/surveys/add.cshtml', $viewBag);
		
		}



	}


?>