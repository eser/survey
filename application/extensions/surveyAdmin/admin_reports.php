<?php

	/**
	 * Blackmore Extension: Kibrissiparis Admin Panel, reports Section
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
	class admin_reports {
		/**
		 * @ignore
		 */
		public static function blackmore_registerModules($uParms) {
			$uParms['modules']['reports'] = array(
				'title' => 'Reports',
				'callback' => 'admin_reports::index',
				'submenus' => true,
				'actions' => array(
					
					
				)
			);
		}

		/**
		 * @ignore
		 */
		public static function index() {
			auth::checkRedirect('admin');
			$viewBag = array();
			
			$products = array();
			$firms = array();
			
			$productModel = mvc::load('productModel');
			$firmModel = mvc::load('firmModel');

			$data = $productModel->getReport();
			$i = 0;
			foreach ($data as $row) {
				$product['label'] = $row['name'];
				$product['data'] = array(array($i, $row['sale']));
				array_push($products, $product);
				$i++;
			}

			$data = $firmModel->getReport();
			$i = 0;
			foreach ($data as $row) {
				$firm['label'] = $row['name'];
				$firm['data'] = array(array($i, $row['sale']));
				array_push($firms, $firm);
				$i++;
			}

			/*string::vardump($data);
			exit();*/
			$viewBag['products'] = json_encode($products);
			$viewBag['firms'] = json_encode($firms);

			views::view('kibrissiparisAdmin/reports/index.cshtml', $viewBag);

		}

	
	

	}


?>