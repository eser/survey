<?php

	/**
	 * Blackmore Extension: Kibrissiparis Admin Panel, Users Section
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
	class admin_users {
		/**
		 * @ignore
		 */
		public static function blackmore_registerModules($uParms) {
			$uParms['modules']['users'] = array(
				'title' => 'Users',
				'callback' => 'admin_users::index',
				'submenus' => true,
				'actions' => array(
					
					array(
						'callback' => 'admin_users::userList',
						'menutitle' => 'List',
						'action' => 'userList'
					),
					array(
						'callback' => 'admin_users::add',
						'menutitle' => 'Add',
						'action' => 'add'
					),
					array(
						'callback' => 'admin_users::delete',
						'action' => 'delete'
					),
					array(
						'callback' => 'admin_users::edit',
						'action' => 'edit'
					),
					array(
						'callback' => 'admin_users::addressList',
						'action' => 'addressList'
					),
					array(
						'callback' => 'admin_users::addAddress',
						'action' => 'addAddress'
					),
					array(
						'callback' => 'admin_users::confirmUser',
						'action' => 'confirmUser'
					),
					array(
						'callback' => 'admin_users::confirmAddress',
						'action' => 'confirmAddress'
					)
				)
			);
		}

		/**
		 * @ignore
		 */
		public static function index() {
			auth::checkRedirect('admin');
			self::userList();
		}

		public static function userList() {
			auth::checkRedirect('admin');
			$userModel = mvc::load('userModel');
			$viewBag = array('users' => $userModel->getAll());
			views::view('kibrissiparisAdmin/users/list.cshtml', $viewBag);
		}

		public static function add() {
			$viewBag = array();
			auth::checkRedirect('admin');
			$userModel = mvc::load('userModel');
			if(http::$method == 'post') {
				$input = array(
					'fullname' => http::post('fullname'),
					'email' => http::post('email'),
					'email2' => http::post('email2'),
					'password' => http::post('password'),
					'mobile' => http::post('mobile'),
					'mobile2' => http::post('mobile2'),
					'lostpasswordkey' => http::post('lostpasswordkey')
					 );
				$userID = http::post('userid');
				if($userID != null && contracts::isUuid($userID)->check()) {
					$viewBag['message'] = $userModel->update($userID, $input).' Record Updated';
				}
				else {
					$input['userid'] = string::generateUuid();
					$viewBag['message'] = $userModel->insert($input).' Record Inserted';
				}
			}

			views::view('kibrissiparisAdmin/users/add.cshtml', $viewBag);
		}

		public static function delete($actionName, $userID) {
			$viewBag = array();
			auth::checkRedirect('admin');
			$userModel = mvc::load('userModel');
			$viewBag['message'] = $userModel->delete($userID).' Record Deleted';
			$viewBag ['users'] = $userModel->getAll();
			views::view('kibrissiparisAdmin/users/list.cshtml', $viewBag);
		}

		public static function edit($actionName, $userID) {
			$viewBag = array();
			auth::checkRedirect('admin');
			$userModel = mvc::load('userModel');
			$viewBag = array('user' => $userModel->get($userID));
			views::view('kibrissiparisAdmin/users/add.cshtml', $viewBag);
		}

		public static function addressList($actionName, $userID) {
			$viewBag = array();
			auth::checkRedirect('admin');
			$userModel = mvc::load('userModel');
			$viewBag['addresses'] = $userModel->getAddressList($userID);
			$viewBag['user'] = $userModel->get($userID);
			/*string::vardump($viewBag);
			exit();*/
			views::view('kibrissiparisAdmin/users/addressList.cshtml', $viewBag);

		}
		
		public static function addAddress($actionName, $userID) {
			$viewBag = array();
			auth::checkRedirect('admin');
			$regionModel = mvc::load('regionModel');
			$userModel = mvc::load('userModel');
			if(http::$method == 'post') {
				$input = array(
					'title' => http::post('title'),
					'addressdescription' => http::post('addressdescription'),
					'address' => http::post('address'),
					'regionid' => http::post('regionid'),
					'status' => '1',
					'userid' => $userID
					);
				
				$addressID = http::post('addressid');
				if($addressID != null && contracts::isUuid($addressID)->check()) {
					$viewBag['message'] = $userModel->updateAddress($addressID, $input).' Record Updated';
				}
				else {
					$input['useraddressid'] = string::generateUuid();
					$viewBag['message'] = $userModel->insertAddress($input).' Record Inserted';
				}

			}
			
			$viewBag['user'] = $userModel->get($userID);
			$viewBag['regions'] = $regionModel->getAll();

			views::view('kibrissiparisAdmin/users/addAddress.cshtml', $viewBag);

		}

		public static function deleteAddress($actionName, $addressID) {
			$viewBag = array();
			auth::checkRedirect('admin');
			$userModel = mvc::load('userModel');
			$viewBag['message'] = $userModel->deleteAddress($addressID).' Record Deleted';
			$viewBag['addresses'] = $userModel->getAddressList($userID);
			$viewBag['user'] = $userModel->get($userID);
			views::view('kibrissiparisAdmin/users/addressList.cshtml', $viewBag);
		}

		public static function confirmUser($actionName, $userID) {
			$viewBag = array();
			auth::checkRedirect('admin');
			$userModel = mvc::load('userModel');
			if($userID != null && contracts::isUuid($userID)->check()) {
				$viewBag['message'] = $userModel->confirm($userID).' Record Updated';
			}
			$viewBag ['users'] = $userModel->getAll();
			views::view('kibrissiparisAdmin/users/list.cshtml', $viewBag);
		}

		public static function confirmAddress($actionName, $userID, $userAddressID) {
			$viewBag = array();
			auth::checkRedirect('admin');
			$userModel = mvc::load('userModel');
			if($userID != null && contracts::isUuid($userAddressID)->check()) {
				$viewBag['message'] = $userModel->confirmAddress($userAddressID).' Record Updated';
			}
			$viewBag['addresses'] = $userModel->getAddressList($userID);
			$viewBag['user'] = $userModel->get($userID);
			views::view('kibrissiparisAdmin/users/addressList.cshtml', $viewBag);
		}



	}


?>