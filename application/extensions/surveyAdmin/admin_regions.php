<?php

	/**
	 * Blackmore Extension: Kibrissiparis Admin Panel, region Section
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
	class admin_regions {
		/**
		 * @ignore
		 */
		public static function blackmore_registerModules($uParms) {
			$uParms['modules']['regions'] = array(
				'title' => 'Regions',
				'callback' => 'admin_regions::index',
				'submenus' => true,
				'actions' => array(
					
					array(
						'callback' => 'admin_regions::regionList',
						'menutitle' => 'List',
						'action' => 'regionList'
					),
					array(
						'callback' => 'admin_regions::add',
						'menutitle' => 'Add',
						'action' => 'add'
					),
					array(
						'callback' => 'admin_regions::delete',
						'action' => 'delete'
					),
					array(
						'callback' => 'admin_regions::edit',
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
			self::regionList();
		}

		public static function regionList() {
			auth::checkRedirect('admin');
			$regionModel = mvc::load('regionModel');
			$viewBag = array('regions' => $regionModel->getAllWithParentName());
			views::view('kibrissiparisAdmin/regions/list.cshtml', $viewBag);
		}

		public static function add() {
			$viewBag = array();
			auth::checkRedirect('admin');
			$regionModel = mvc::load('regionModel');
			if(http::$method == 'post') {
				$input = array(
					'name' => http::post('name'),
					'slug' => http::post('slug'),
					'parentid' => http::post('parentid')
					 );
				$regionID = http::post('regionid');
				if($regionID != null && contracts::isUuid($regionID)->check()) {
					$viewBag['message'] = $regionModel->update($regionID, $input).' Record Updated';
				}
				else {
					$input['regionid'] = string::generateUuid();
					$viewBag['message'] = $regionModel->insert($input).' Record Inserted';
				}
			}
			$viewBag['regions'] = $regionModel->getAll();
			views::view('kibrissiparisAdmin/regions/add.cshtml', $viewBag);
		}

		public static function delete($actionName, $regionID) {
			$viewBag = array();
			auth::checkRedirect('admin');
			$regionModel = mvc::load('regionModel');
			if(contracts::isUuid($regionID)->check()) {
				$viewBag['message'] = $regionModel->delete($regionID).' Record Deleted';
			}
			$viewBag = array('regions' => $regionModel->getAllWithParentName());
			views::view('kibrissiparisAdmin/regions/list.cshtml', $viewBag);
		}

		public static function edit($actionName, $regionID) {
			$viewBag = array();
			auth::checkRedirect('admin');
			$regionModel = mvc::load('regionModel');
			$viewBag = array(
				'region' => $regionModel->get($regionID),
				'regions' => $regionModel->getAll()
				);
			views::view('kibrissiparisAdmin/regions/add.cshtml', $viewBag);
			
			
		}


	}


?>