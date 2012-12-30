<?php

	/**
	 * Blackmore Extension: Kibrissiparis Admin Panel, showcase Section
	 *
	 * @package Kibrissiparis
	 * @subpackage admin_showcase
	 * @version 1.0.2
	 *
	 * @scabbia-fwversion 1.0
	 * @scabbia-fwdepends string, resources, validation, http, auth, zmodels
	 * @scabbia-phpversion 5.2.0
	 * @scabbia-phpdepends
	 */
	class admin_showcase {
		/**
		 * @ignore
		 */
		public static function blackmore_registerModules($uParms) {
			$uParms['modules']['showcase'] = array(
				'title' => 'Showcase',
				'callback' => 'admin_showcase::index',
				'submenus' => true,
				'actions' => array(
					array(
						'callback' => 'admin_showcase::productList',
						'menutitle' => 'Product List',
						'action' => 'productList'
					),
					array(
						'callback' => 'admin_showcase::firmList',
						'menutitle' => 'Firm List',
						'action' => 'firmList'
					),
					array(
						'callback' => 'admin_showcase::addProduct',
						'menutitle' => 'Add Product',
						'action' => 'addProduct'
					),
					array(
						'callback' => 'admin_showcase::addFirm',
						'menutitle' => 'Add firm',
						'action' => 'addFirm'
					),
					array(
						'callback' => 'admin_showcase::deleteFirm',
						'action' => 'deleteFirm'
					),
					array(
						'callback' => 'admin_showcase::deleteProduct',
						'action' => 'deleteProduct'
					),
					array(
						'callback' => 'admin_showcase::editFirm',
						'action' => 'editFirm'
					),
					array(
						'callback' => 'admin_showcase::editProduct',
						'action' => 'editProduct'
					)

				)
			);
		}

		/**
		 * @ignore
		 */
		public static function index() {
			auth::checkRedirect('admin');
			self::productList();
		}

		public static function productList() {
			auth::checkRedirect('admin');
			$showcaseModel = mvc::load('showcaseModel');
			$viewBag = array('showcaseProducts' => $showcaseModel->getProducts());
			views::view('kibrissiparisAdmin/showcase/productList.cshtml', $viewBag);
		}

		public static function addProduct() {
			$viewBag = array();
			auth::checkRedirect('admin');
			$firmModel = mvc::load('firmModel');
			$showcaseModel = mvc::load('showcaseModel');

			if(http::$method == 'post') {
				$firmID = http::post('firmid');
				$productID = http::post('productid');
				$startdate = strtotime(http::post('startdate'));
				$finishdate = strtotime(http::post('finishdate'));
				if(contracts::isUuid($productID)->check() && contracts::isUuid($firmID)->check())
				{
					$input = array(
						'firmid' => $firmID,
						'productid' => $productID,
						'startdate' => time::toDb($startdate),
						'finishdate' => time::toDb($finishdate),
						'text' => http::post('text'),
						'category' => 'Product',
						'oldprice' => http::post('oldprice'),
						'newprice' => http::post('newprice'),
						'reason' => http::post('reason'),
						'path' => http::post('path'),
						'zorder' => http::post('zorder'),
						'status' => '1',
						'type' => http::post('type')
						);

					$showcaseID = http::post('showcaseid');
					if($showcaseID != null && contracts::isUuid($showcaseID)->check()) {
						$viewBag['message'] = $showcaseModel->update($showcaseID, $input).' Record Updated';
					}
					else {
						$input['showcaseid'] = string::generateUuid();
						//string::vardump($input);
						$viewBag['message'] = $showcaseModel->insert($input) .' Record Inserted';
					}
				}
				else {
					$viewBag['message'] ='Error : Select a product';
				}
			}

			
			$viewBag['firms'] = $firmModel->getFirmsHaveProducts();
			views::view('kibrissiparisAdmin/showcase/addProduct.cshtml', $viewBag);
		}

		public static function addFirm() {
			$viewBag = array();
			auth::checkRedirect('admin');
			$firmModel = mvc::load('firmModel');
			$showcaseModel = mvc::load('showcaseModel');
			
			//to-do controll all fileds
			if(http::$method == 'post') {
				$startdate = strtotime(http::post('startdate'));
				$finishdate = strtotime(http::post('finishdate'));
				$input = array(
					'firmid' => http::post('firmid'),
					'startdate' =>  time::toDb($startdate),
					'finishdate' =>  time::toDb($finishdate),
					'text' => http::post('text'),
					'category' => 'Firm',
					'reason' => http::post('reason'),
					'path' => http::post('path'),
					'zorder' => http::post('zorder'),
					'type' => http::post('type')
					);

				$showcaseID = http::post('showcaseid');
				

				if($showcaseID != null && contracts::isUuid($showcaseID)->check()) {
					$viewBag['message'] = $showcaseModel->update($showcaseID, $input).' Record Updated';
				}
				else {
					$input['showcaseid'] = string::generateUuid();
					$viewBag['message'] = $showcaseModel->insert($input).' Record Inserted ';
				}
			}

			
			$viewBag['firms'] = $firmModel->getAll();
			views::view('kibrissiparisAdmin/showcase/addFirm.cshtml', $viewBag);
		}

		public static function firmList() {
			auth::checkRedirect('admin');
			$showcaseModel = mvc::load('showcaseModel');
			$viewBag = array('showcaseFirms' => $showcaseModel->getFirms());
			views::view('kibrissiparisAdmin/showcase/firmList.cshtml', $viewBag);
		}
		
		public static function deleteProduct($actionName, $showcaseID) {
			auth::checkRedirect('admin');
			$viewBag = array();
			$showcaseModel = mvc::load('showcaseModel');
			if(contracts::isUuid($showcaseID)->check()) {
				
				$viewBag['message'] = $showcaseModel->delete($showcaseID).' Record deleted';
			}

			$viewBag['showcaseProducts'] = $showcaseModel->getProducts();
			views::view('kibrissiparisAdmin/showcase/productList.cshtml', $viewBag);

		}


		public static function deleteFirm($actionName, $showcaseID) {
			auth::checkRedirect('admin');
			$viewBag = array();
			$showcaseModel = mvc::load('showcaseModel');
			if(contracts::isUuid($showcaseID)->check()) {
				$viewBag['message'] = $showcaseModel->delete($showcaseID).' Record deleted';
			}

			$viewBag['showcaseFirms'] = $showcaseModel->getFirms();
			views::view('kibrissiparisAdmin/showcase/firmList.cshtml', $viewBag);

		}

		public static function editFirm($actionName, $showcaseID) {
			auth::checkRedirect('admin');
			$viewBag = array();
			$showcaseModel = mvc::load('showcaseModel');
			$firmModel = mvc::load('firmModel');

			$viewBag['firms'] = $firmModel->getAll();
			$viewBag['showcaseFirm'] = $showcaseModel->get($showcaseID);
			$viewBag['showcaseFirm']['startdate'] = date('d-m-Y', strtotime($viewBag['showcaseFirm']['startdate']));
			$viewBag['showcaseFirm']['finishdate'] = date('d-m-Y', strtotime($viewBag['showcaseFirm']['finishdate']));
			//string::vardump($viewBag);
			views::view('kibrissiparisAdmin/showcase/addFirm.cshtml', $viewBag);
		}

		/*to do*/
		public static function editProduct($actionName, $showcaseID) {
			auth::checkRedirect('admin');
			$viewBag = array();
			$showcaseModel = mvc::load('showcaseModel');
			$firmModel = mvc::load('firmModel');

			$viewBag['showcaseProduct'] = $showcaseModel->get($showcaseID);

			$viewBag['firms'] = $firmModel->getFirmsHaveProducts();
			$viewBag['products'] = $firmModel->getProducts($viewBag['showcaseProduct']['firmid']);
			
			$viewBag['showcaseProduct']['startdate'] = date('d-m-Y', strtotime($viewBag['showcaseProduct']['startdate']));
			$viewBag['showcaseProduct']['finishdate'] = date('d-m-Y', strtotime($viewBag['showcaseProduct']['finishdate']));
			/*string::vardump($viewBag);
			exit();*/
			views::view('kibrissiparisAdmin/showcase/addProduct.cshtml', $viewBag);
		}


	}


?>