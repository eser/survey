<?php

	use Scabbia\Extensions\Mvc\Controllers;

	/**
	 * Blackmore Extension: survey-e-bot Admin Panel, categories Section
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
	class admin_categories {
		/**
		 * @ignore
		 */
		public static function blackmoreRegisterModules($uParms) {
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
			Auth::checkRedirect('admin');
			self::categoryList();
		}

		public static function categoryList() {
			Auth::checkRedirect('admin');
			$categoryModel = Controllers::load('App\\Models\\CategoryModel');
			Views::set('categories', $categoryModel->getAll());
			Views::viewFile('{app}views/surveyAdmin/categories/list.cshtml');
		}

		public static function add() {
			$viewBag = array();
			Auth::checkRedirect('admin');
			$categoryModel = Controllers::load('App\\Models\\CategoryModel');
			if(Http::$method == 'post') {
				$input = array(
					'name' => Request::post('name')
					 );
				$categoryID = Request::post('categoryid');
				if($categoryID != null && Contracts::isUuid($categoryID)->check()) {
					$viewBag['message'] = $categoryModel->update($categoryID, $input).' Record Updated';
				}
				else {
					$input['categoryid'] = String::generateUuid();
					$viewBag['message'] = $categoryModel->insert($input).' Record Inserted';
				}
			}
			Views::viewFile('{app}views/surveyAdmin/categories/add.cshtml', $viewBag);
		}

		public static function delete($actionName, $categoryID) {
			$viewBag = array();
			Auth::checkRedirect('admin');
			$categoryModel = Controllers::load('App\\Models\\CategoryModel');
			if(Contracts::isUuid($categoryID)->check()) {
				$viewBag['message'] = $categoryModel->delete($categoryID).' Record Deleted';
			}
			$viewBag['categories'] = $categoryModel->getAll();
			Views::viewFile('{app}views/surveyAdmin/categories/list.cshtml', $viewBag);
		}

		public static function edit($actionName, $categoryID) {
			$viewBag = array();
			Auth::checkRedirect('admin');
			$categoryModel = Controllers::load('App\\Models\\CategoryModel');
			$viewBag = array(
				'category' => $categoryModel->get($categoryID)
				);
			Views::viewFile('{app}views/surveyAdmin/categories/add.cshtml', $viewBag);
		
		}



	}


?>