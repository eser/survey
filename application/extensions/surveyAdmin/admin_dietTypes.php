<?php

	/**
	 * Blackmore Extension: Kibrissiparis Admin Panel, diettypes Section
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
	class admin_dietTypes {
		/**
		 * @ignore
		 */
		public static function blackmore_registerModules($uParms) {
			$uParms['modules']['dietTypes'] = array(
				'title' => 'Diet Types',
				'callback' => 'admin_dietTypes::index',
				'submenus' => true,
				'actions' => array(
					
					array(
						'callback' => 'admin_dietTypes::dietTypeList',
						'menutitle' => 'List',
						'action' => 'dietTypeList'
					),
					array(
						'callback' => 'admin_dietTypes::add',
						'menutitle' => 'Add',
						'action' => 'add'
					),
					array(
						'callback' => 'admin_dietTypes::delete',
						'action' => 'delete'
					),
					array(
						'callback' => 'admin_dietTypes::edit',
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
			self::dietTypeList();
		}

		public static function dietTypeList() {
			auth::checkRedirect('admin');
			$dietTypeModel = mvc::load('dietTypeModel');
			views::set('dietTypes', $dietTypeModel->getAll());
			views::view('kibrissiparisAdmin/dietTypes/list.cshtml');
		}

		public static function add() {
			$viewBag = array();
			auth::checkRedirect('admin');
			$dietTypeModel = mvc::load('dietTypeModel');
			if(http::$method == 'post') {
				$input = array(
					'name' => http::post('name'),
					'slug' => http::post('slug')
					 );
				$dietTypeID = http::post('diettypeid');
				if($dietTypeID != null && contracts::isUuid($dietTypeID)->check()) {
					$viewBag['message'] = $dietTypeModel->update($dietTypeID, $input).' Record Updated';
				}
				else {
					$input['diettypeid'] = string::generateUuid();
					$viewBag['message'] = $dietTypeModel->insert($input).' Record Inserted';
				}
			}
			$viewBag['dietTypes'] = $dietTypeModel->getAll();
			views::view('kibrissiparisAdmin/dietTypes/add.cshtml', $viewBag);
		}

		public static function delete($actionName, $dietTypeID) {
			$viewBag = array();
			auth::checkRedirect('admin');
			$dietTypeModel = mvc::load('dietTypeModel');
			if(contracts::isUuid($dietTypeID)->check()) {
				$result = $dietTypeModel->delete($dietTypeID);
				$viewBag['result'] = $result;
				$viewBag['message'] = 'Record Deleted';
			}
			$viewBag = array('dietTypes' => $dietTypeModel->getAll());
			views::view('kibrissiparisAdmin/dietTypes/list.cshtml', $viewBag);
		}

		public static function edit($actionName, $dietTypeID) {
			$viewBag = array();
			auth::checkRedirect('admin');
			$dietTypeModel = mvc::load('dietTypeModel');
			$viewBag = array(
				'dietType' => $dietTypeModel->get($dietTypeID),
				'dietTypes' => $dietTypeModel->getAll()
				);
			views::view('kibrissiparisAdmin/dietTypes/add.cshtml', $viewBag);
			
			
		}


	}


?>