<?php

	/**
	 * Blackmore Extension: Kibrissiparis Admin Panel, categories Section
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
	class admin_categories {
		/**
		 * @ignore
		 */
		public static function blackmore_registerModules($uParms) {
			$uParms['modules']['categories'] = array(
				'title' => 'Categories',
				'callback' => 'admin_categories::index',
				'submenus' => true,
				'actions' => array(
					
					array(
						'callback' => 'admin_categories::categoryList',
						'menutitle' => 'List',
						'action' => 'categoryList'
					),
					array(
						'callback' => 'admin_categories::add',
						'menutitle' => 'Add',
						'action' => 'add'
					),
					array(
						'callback' => 'admin_categories::delete',
						'action' => 'delete'
					),
					array(
						'callback' => 'admin_categories::edit',
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
			self::categoryList();
		}

		public static function categoryList() {
			auth::checkRedirect('admin');
			$categoryModel = mvc::load('categoryModel');
			views::set('categories', $categoryModel->getAll());
			views::view('surveyAdmin/categories/list.cshtml');
		}

		public static function add() {
			$viewBag = array();
			auth::checkRedirect('admin');
			$categoryModel = mvc::load('categoryModel');
			if(http::$method == 'post') {
				$input = array(
					'name' => http::post('name')
					 );
				$categoryID = http::post('categoryid');
				if($categoryID != null && contracts::isUuid($categoryID)->check()) {
					$viewBag['message'] = $categoryModel->update($categoryID, $input).' Record Updated';
				}
				else {
					$input['categoryid'] = string::generateUuid();
					$viewBag['message'] = $categoryModel->insert($input).' Record Inserted';
				}
			}
			views::view('surveyAdmin/categories/add.cshtml', $viewBag);
		}

		public static function delete($actionName, $categoryID) {
			$viewBag = array();
			auth::checkRedirect('admin');
			$categoryModel = mvc::load('categoryModel');
			if(contracts::isUuid($categoryID)->check()) {
				$viewBag['message'] = $categoryModel->delete($categoryID).' Record Deleted';
			}
			$viewBag['categories'] = $categoryModel->getAll();
			views::view('surveyAdmin/categories/list.cshtml', $viewBag);
		}

		public static function edit($actionName, $categoryID) {
			$viewBag = array();
			auth::checkRedirect('admin');
			$categoryModel = mvc::load('categoryModel');
			$viewBag = array(
				'category' => $categoryModel->get($categoryID)
				);
			views::view('surveyAdmin/categories/add.cshtml', $viewBag);
		
		}



	}


?>