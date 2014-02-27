<?php

	use Scabbia\Extensions\Mvc\Controllers;

	/**
	 * Blackmore Extension: survey-e-bot Admin Panel, languages Section
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
	class admin_languages {
		/**
		 * @ignore
		 */
		public static function blackmoreRegisterModules($uParms) {
			$uParms['modules']['languages'] = array(
				'title' => 'Languages',
				'callback' => 'admin_languages::index',
				'submenus' => true,
				'actions' => array(
					
					array(
						'callback' => 'admin_languages::languageList',
						'menutitle' => 'List',
						'action' => 'languageList'
					),
					array(
						'callback' => 'admin_languages::add',
						'menutitle' => 'Add',
						'action' => 'add'
					),
					array(
						'callback' => 'admin_languages::delete',
						'action' => 'delete'
					),
					array(
						'callback' => 'admin_languages::edit',
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
			self::languageList();
		}

		public static function languageList() {
			Auth::checkRedirect('admin');
			$languageModel = Controllers::load('App\\Models\\LanguageModel');
			Views::set('languages', $languageModel->getAll());
			Views::viewFile('{app}views/surveyAdmin/languages/list.cshtml');
		}

		public static function add() {
			$viewBag = array();
			Auth::checkRedirect('admin');
			$languageModel = Controllers::load('App\\Models\\LanguageModel');
			if(Http::$method == 'post') {
				$input = array(
					'name' => Request::post('name')
					 );
				$updateID = Request::post('updateid');
				if($updateID != null) {
					$viewBag['message'] = $languageModel->update($updateID, $input).' Record Updated';
				}
				else {
					$input['languageid'] = Request::post('languageid');
					$viewBag['message'] = $languageModel->insert($input).' Record Inserted';
				}
			}
			Views::viewFile('{app}views/surveyAdmin/languages/add.cshtml', $viewBag);
		}

		public static function delete($actionName, $languageID) {
			$viewBag = array();
			Auth::checkRedirect('admin');
			$languageModel = Controllers::load('App\\Models\\LanguageModel');
			$viewBag['message'] = $languageModel->delete($languageID).' Record Deleted';
			$viewBag['languages'] = $languageModel->getAll();
			Views::viewFile('{app}views/surveyAdmin/languages/list.cshtml', $viewBag);
		}

		public static function edit($actionName, $languageID) {
			$viewBag = array();
			Auth::checkRedirect('admin');
			$languageModel = Controllers::load('App\\Models\\LanguageModel');
			
			$viewBag = array(
				'language' => $languageModel->get($languageID)
				);
			Views::viewFile('{app}views/surveyAdmin/languages/add.cshtml', $viewBag);
		
		}

	


	}   


?>