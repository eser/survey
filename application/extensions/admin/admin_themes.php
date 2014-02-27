<?php

	use Scabbia\Extensions\Mvc\Controllers;

	/**
	 * Blackmore Extension: survey-e-bot Admin Panel, themes Section
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
	class admin_themes {
		/**
		 * @ignore
		 */
		public static function blackmoreRegisterModules($uParms) {
			$uParms['modules']['themes'] = array(
				'title' => 'Themes',
				'callback' => 'admin_themes::index',
				'submenus' => true,
				'actions' => array(
					
					array(
						'callback' => 'admin_themes::themeList',
						'menutitle' => 'List',
						'action' => 'themeList'
					),
					array(
						'callback' => 'admin_themes::add',
						'menutitle' => 'Add',
						'action' => 'add'
					),
					array(
						'callback' => 'admin_themes::delete',
						'action' => 'delete'
					),
					array(
						'callback' => 'admin_themes::edit',
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
			self::themeList();
		}

		public static function themeList() {
			Auth::checkRedirect('admin');
			$themeModel = Controllers::load('App\\Models\\ThemeModel');
			Views::set('themes', $themeModel->getAll());
			Views::viewFile('{app}views/surveyAdmin/themes/list.cshtml');
		}

		public static function add() {
			$viewBag = array();
			Auth::checkRedirect('admin');
			$themeModel = Controllers::load('App\\Models\\ThemeModel');
			if(Http::$method == 'post') {
				$input = array(
					'name' => Request::post('name'),
					'cssrules' => Request::post('cssrules')
					 );
				$themeID = Request::post('themeid');
				if($themeID != null && Contracts::isUuid($themeID)->check()) {
					$viewBag['message'] = $themeModel->update($themeID, $input).' Record Updated';
				}
				else {
					$input['themeid'] = String::generateUuid();
					$viewBag['message'] = $themeModel->insert($input).' Record Inserted';
				}
			}
			Views::viewFile('{app}views/surveyAdmin/themes/add.cshtml', $viewBag);
		}

		public static function delete($actionName, $themeID) {
			$viewBag = array();
			Auth::checkRedirect('admin');
			$themeModel = Controllers::load('App\\Models\\ThemeModel');
			if(Contracts::isUuid($themeID)->check()) {
				$viewBag['message'] = $themeModel->delete($themeID).' Record Deleted';
			}
			$viewBag['themes'] = $themeModel->getAll();
			Views::viewFile('{app}views/surveyAdmin/themes/list.cshtml', $viewBag);
		}

		public static function edit($actionName, $themeID) {
			$viewBag = array();
			Auth::checkRedirect('admin');
			$themeModel = Controllers::load('App\\Models\\ThemeModel');
			$viewBag = array(
				'theme' => $themeModel->get($themeID)
				);
			Views::viewFile('{app}views/surveyAdmin/themes/add.cshtml', $viewBag);
		
		}



	}


?>