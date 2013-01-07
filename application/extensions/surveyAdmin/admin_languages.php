<?php

	use Scabbia\controllers;

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
			auth::checkRedirect('admin');
			self::languageList();
		}

		public static function languageList() {
			auth::checkRedirect('admin');
			$languageModel = controllers::load('languageModel');
			views::set('languages', $languageModel->getAll());
			views::view('surveyAdmin/languages/list.cshtml');
		}

		public static function add() {
			$viewBag = array();
			auth::checkRedirect('admin');
			$languageModel = controllers::load('languageModel');
			if(http::$method == 'post') {
				$input = array(
					'name' => http::post('name')
					 );
				$updateID = http::post('updateid');
				if($updateID != null) {
					$viewBag['message'] = $languageModel->update($updateID, $input).' Record Updated';
				}
				else {
					$input['languageid'] = http::post('languageid');
					$viewBag['message'] = $languageModel->insert($input).' Record Inserted';
				}
			}
			views::view('surveyAdmin/languages/add.cshtml', $viewBag);
		}

		public static function delete($actionName, $languageID) {
			$viewBag = array();
			auth::checkRedirect('admin');
			$languageModel = controllers::load('languageModel');
			$viewBag['message'] = $languageModel->delete($languageID).' Record Deleted';
			$viewBag['languages'] = $languageModel->getAll();
			views::view('surveyAdmin/languages/list.cshtml', $viewBag);
		}

		public static function edit($actionName, $languageID) {
			$viewBag = array();
			auth::checkRedirect('admin');
			$languageModel = controllers::load('languageModel');
			
			$viewBag = array(
				'language' => $languageModel->get($languageID)
				);
			views::view('surveyAdmin/languages/add.cshtml', $viewBag);
		
		}

	


	}   


?>