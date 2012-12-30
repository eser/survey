<?php

	/**
	 * Blackmore Extension: Kibrissiparis Admin Panel, orders Section
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
	class admin_orders {
		/**
		 * @ignore
		 */
		public static function blackmore_registerModules($uParms) {
			$uParms['modules']['orders'] = array(
				'title' => 'Orders',
				'callback' => 'admin_orders::index',
				'submenus' => true,
				'actions' => array(
					
					array(
						'callback' => 'admin_orders::orderList',
						'menutitle' => 'List',
						'action' => 'orderList'
					),
					array(
						'callback' => 'admin_orders::orderProducts',
						'action' => 'orderProducts'
					)
				)
			);
		}

		/**
		 * @ignore
		 */
		public static function index() {
			auth::checkRedirect('admin');
			self::orderList();
		}

		public static function orderList() {
			auth::checkRedirect('admin');
			$orderModel = mvc::load('orderModel');
			$firmModel = mvc::load('firmModel');

			$viewBag = array(
				'orders' => $orderModel->getList(),
				'firms' => $firmModel->getAll());
			views::view('kibrissiparisAdmin/orders/list.cshtml', $viewBag);
		}

		public static function orderProducts($actionName, $orderID) {
			auth::checkRedirect('admin');
			$orderModel = mvc::load('orderModel');
			$viewBag = array(
				'products' => $orderModel->getOrderProducts($orderID)
				);
			views::view('kibrissiparisAdmin/orders/orderProducts.cshtml', $viewBag);
		}

	}


?>