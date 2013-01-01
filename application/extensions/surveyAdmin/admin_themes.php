<?php

	/**
	 * Blackmore Extension: Kibrissiparis Admin Panel, themes Section
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
	class admin_themes {
		/**
		 * @ignore
		 */
		public static function blackmore_registerModules($uParms) {
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
			auth::checkRedirect('admin');
			self::themeList();
		}

		public static function themeList() {
			auth::checkRedirect('admin');
			$themeModel = mvc::load('themeModel');
			views::set('themes', $themeModel->getAll());
			views::view('surveyAdmin/themes/list.cshtml');
		}

		public static function add() {
			$viewBag = array();
			auth::checkRedirect('admin');
			$themeModel = mvc::load('themeModel');
			if(http::$method == 'post') {
				$input = array(
					'name' => http::post('name'),
					'cssrules' => http::post('cssrules')
					 );
				$themeID = http::post('themeid');
				if($themeID != null && contracts::isUuid($themeID)->check()) {
					$viewBag['message'] = $themeModel->update($themeID, $input).' Record Updated';
				}
				else {
					$input['themeid'] = string::generateUuid();
					$viewBag['message'] = $themeModel->insert($input).' Record Inserted';
				}
			}
			views::view('surveyAdmin/themes/add.cshtml', $viewBag);
		}

		public static function delete($actionName, $themeID) {
			$viewBag = array();
			auth::checkRedirect('admin');
			$themeModel = mvc::load('themeModel');
			if(contracts::isUuid($themeID)->check()) {
				$viewBag['message'] = $themeModel->delete($themeID).' Record Deleted';
			}
			$viewBag['themes'] = $themeModel->getAll();
			views::view('surveyAdmin/themes/list.cshtml', $viewBag);
		}

		public static function edit($actionName, $themeID) {
			$viewBag = array();
			auth::checkRedirect('admin');
			$themeModel = mvc::load('themeModel');
			$viewBag = array(
				'theme' => $themeModel->get($themeID)
				);
			views::view('surveyAdmin/themes/add.cshtml', $viewBag);
		
		}



	}


?>