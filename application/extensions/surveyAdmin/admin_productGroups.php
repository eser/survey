<?php

	/**
	 * Blackmore Extension: Kibrissiparis Admin Panel, Groups Section
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
	class admin_productGroups {
		/**
		 * @ignore
		 */
		public static function blackmore_registerModules($uParms) {
			$uParms['modules']['productGroups'] = array(
				'title' => 'Product Groups',
				'callback' => 'admin_productGroups::index',
				'submenus' => true,
				'actions' => array(
					
					array(
						'callback' => 'admin_productGroups::productGroupList',
						'menutitle' => 'List',
						'action' => 'productGroupList'
					),
					array(
						'callback' => 'admin_productGroups::add',
						'menutitle' => 'Add',
						'action' => 'add'
					),
					array(
						'callback' => 'admin_productGroups::delete',
						'action' => 'delete'
					),
					array(
						'callback' => 'admin_productGroups::edit',
						'action' => 'edit'
					),
					array(
						'callback' => 'admin_productGroups::productList',
						'action' => 'productList'
					),
					array(
						'callback' => 'admin_productGroups::addProduct',
						'action' => 'addProduct'
					),
					array(
						'callback' => 'admin_productGroups::deleteProduct',
						'action' => 'deleteProduct'
					),
					array(
						'callback' => 'admin_productGroups::viewOptions',
						'action' => 'viewOptions'
					)
				)
			);
		}

		/**
		 * @ignore
		 */
		public static function index() {
			auth::checkRedirect('admin');
			self::productGroupList();
		}

		public static function productGroupList() {
			auth::checkRedirect('admin');
			$productGroupModel = mvc::load('productGroupModel');
			views::set('productGroups', $productGroupModel->getAll());
			views::view('kibrissiparisAdmin/productGroups/list.cshtml');
		}

		public static function add() {
			$viewBag = array();
			auth::checkRedirect('admin');
			$productGroupModel = mvc::load('productGroupModel');
			if(http::$method == 'post') {
				/*clear empty keys*/
				
				$options = http::post('options');
				$options = array_values($options);
			
				for($i = 0; $i<count($options); $i++) {
					$options[$i]['values'] = array_values($options[$i]['values']);
				}
				/*string::vardump($options);
				exit();*/
				$input = array(
					'name' => http::post('name'),
					'options' => json_encode($options)
					 );

				$productGroupID = http::post('productGroupid');
				if($productGroupID != null && contracts::isUuid($productGroupID)->check()) {
					$viewBag['message'] = $productGroupModel->update($productGroupID, $input).' Record Updated';
					$viewBag['productGroup'] = $productGroupModel->get($productGroupID);
				}
				else {
					$input['productgroupid'] = string::generateUuid();
					$viewBag['message'] = $productGroupModel->insert($input).' Record Inserted';
				}
			}
			views::view('kibrissiparisAdmin/productGroups/add.cshtml', $viewBag);
		}

		public static function delete($actionName, $productGroupID) {
			$viewBag = array();
			auth::checkRedirect('admin');
			$productGroupModel = mvc::load('productGroupModel');
			if(contracts::isUuid($productGroupID)->check()) {
				
				$viewBag['message'] = $productGroupModel->delete($productGroupID).' Record Deleted';
			}
			$viewBag ['productGroups'] = $productGroupModel->getAll();
			views::view('kibrissiparisAdmin/productGroups/list.cshtml', $viewBag);
		}

		public static function edit($actionName, $productGroupID) {
			$viewBag = array();
			auth::checkRedirect('admin');
			$productGroupModel = mvc::load('productGroupModel');
			$viewBag['productGroup'] = $productGroupModel->get($productGroupID);
			views::view('kibrissiparisAdmin/productGroups/add.cshtml', $viewBag);
			
		}

		public static function productList($actionName, $productGroupID) {
			$viewBag = array();
			auth::checkRedirect('admin');
			$productGroupModel = mvc::load('productGroupModel');

			$viewBag['products'] = $productGroupModel->getProducts($productGroupID);
			$viewBag['productGroup'] = $productGroupModel->get($productGroupID);

			views::view('kibrissiparisAdmin/productGroups/productList.cshtml', $viewBag);
			
		}

		public static function addProduct($actionName, $productGroupID) {
			$viewBag = array();
			auth::checkRedirect('admin');
			$productGroupModel = mvc::load('productGroupModel');
			$productModel = mvc::load('productModel');
			if(http::$method == 'post') {
				$input = array(
					'productgroupid' => $productGroupID,
					'productid' => http::post('productid')
					 );
				$viewBag['message'] = $productGroupModel->addProduct($input).' Record Inserted';
			}
			$viewBag['productGroup'] = $productGroupModel->get($productGroupID);
			$viewBag['products'] = $productModel->getAll();

			views::view('kibrissiparisAdmin/productGroups/addProduct.cshtml', $viewBag);
		}

		public static function deleteProduct($actionName, $productGroupID, $productID) {
			$viewBag = array();
			auth::checkRedirect('admin');
			$productGroupModel = mvc::load('productGroupModel');
			if(contracts::isUuid($productGroupID)->check() && contracts::isUuid($productID)->check()) {
				
				$viewBag['message'] = $productGroupModel->deleteProduct($productGroupID, $productID).' Record Deleted';
			}
			
			$viewBag['products'] = $productGroupModel->getProducts($productGroupID);
			$viewBag['productGroup'] = $productGroupModel->get($productGroupID);
			views::view('kibrissiparisAdmin/productGroups/productList.cshtml', $viewBag);
		}

		public static function viewOptions($actionName, $productGroupID) {
			$viewBag = array();
			auth::checkRedirect('admin');
			$productGroupModel = mvc::load('productGroupModel');
			$viewBag['productGroup'] = $productGroupModel->get($productGroupID);
			views::view('kibrissiparisAdmin/productGroups/viewOptions.cshtml', $viewBag);
			
		}


	}


?>