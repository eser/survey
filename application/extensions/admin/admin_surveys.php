<?php

	use Scabbia\Extensions\Mvc\Controllers;

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
			Auth::checkRedirect('admin');
			self::surveyList();
		}

		public static function surveyList() {
			Auth::checkRedirect('admin');
			$surveyModel = Controllers::load('App\\Models\\SurveyModel');
			Views::set('surveys', $surveyModel->getAllDetailed());
			Views::viewFile('{app}views/surveyAdmin/surveys/list.cshtml');
		}

		public static function add() {
			$viewBag = array();
			Auth::checkRedirect('admin');
			$surveyModel = Controllers::load('App\\Models\\surveyModel');
			$categoryModel = Controllers::load('App\\Models\\CategoryModel');
			$themeModel = Controllers::load('App\\Models\\ThemeModel');
			$languageModel = Controllers::load('App\\Models\\LanguageModel');
			$userModel = Controllers::load('App\\Models\\UserModel');

			if(Http::$method == 'post') {
				$input = array(
					'title' => Request::post('title'),
					'ownerid' => Request::post('ownerid'),
					'categoryid' => Request::post('categoryid'),
					'languageid' => Request::post('languageid'),
					'themeid' => Request::post('themeid'),
					'languageid' => Request::post('languageid'),
					'description' => Request::post('description')
					 );
				$surveyID = Request::post('surveyid');
				if($surveyID != null && Contracts::isUuid($surveyID)->check()) {
					$viewBag['message'] = $surveyModel->update($surveyID, $input).' Record Updated';
				}
				else {
					$input['surveyid'] = String::generateUuid();
					$viewBag['message'] = $surveyModel->insert($input).' Record Inserted';
				}
			}
			$viewBag['users'] = $userModel->getAll();
			$viewBag['categories'] = $categoryModel->getAll();
			$viewBag['themes'] = $themeModel->getAll();
			$viewBag['languages'] = $languageModel->getAll();

			Views::viewFile('{app}views/surveyAdmin/surveys/add.cshtml', $viewBag);
		}

		public static function delete($actionName, $surveyID) {
			$viewBag = array();
			Auth::checkRedirect('admin');
			$surveyModel = Controllers::load('App\\Models\\SurveyModel');
			if(Contracts::isUuid($surveyID)->check()) {
				$viewBag['message'] = $surveyModel->delete($surveyID).' Record Deleted';
			}
			$viewBag['surveys'] = $surveyModel->getAllDetailed();
			Views::viewFile('{app}views/surveyAdmin/surveys/list.cshtml', $viewBag);
		}

		public static function edit($actionName, $surveyID) {
			$viewBag = array();
			Auth::checkRedirect('admin');
			$surveyModel = Controllers::load('App\\Models\\SurveyModel');
			$categoryModel = Controllers::load('App\\Models\\CategoryModel');
			$themeModel = Controllers::load('App\\Models\\ThemeModel');
			$languageModel = Controllers::load('App\\Models\\LanguageModel');
			$userModel = Controllers::load('App\\Models\\UserModel');
			$viewBag['survey'] = $surveyModel->get($surveyID);
			$viewBag['users'] = $userModel->getAll();
			$viewBag['categories'] = $categoryModel->getAll();
			$viewBag['themes'] = $themeModel->getAll();
			$viewBag['languages'] = $languageModel->getAll();
			Views::viewFile('{app}views/surveyAdmin/surveys/add.cshtml', $viewBag);
		
		}



	}


?>