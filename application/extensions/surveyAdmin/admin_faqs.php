<?php

	/**
	 * Blackmore Extension: Kibrissiparis Admin Panel, faqs Section
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
	class admin_faqs {
		/**
		 * @ignore
		 */
		public static function blackmore_registerModules($uParms) {
			$uParms['modules']['faqs'] = array(
				'title' => 'Faqs',
				'callback' => 'admin_faqs::index',
				'submenus' => true,
				'actions' => array(
					
					array(
						'callback' => 'admin_faqs::faqList',
						'menutitle' => 'List',
						'action' => 'faqList'
					),
					array(
						'callback' => 'admin_faqs::add',
						'menutitle' => 'Add',
						'action' => 'add'
					),
					array(
						'callback' => 'admin_faqs::delete',
						'action' => 'delete'
					),
					array(
						'callback' => 'admin_faqs::edit',
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
			// views::view('kibrissiparisAdmin/faqs/index.cshtml');
			// $file = media::open('C:\\inetpub\\wwwroot\\kibristayim\\res\\images\\404page.png');
			// $file->resize('300', '240', 'fit'); // fit, crop, stretch
			// $file->output();
			// $file->save('C:\\inetpub\\wwwroot\\kibristayim\\res\\images\\404resized.png');
			self::faqList();
		}

		public static function faqList() {
			auth::checkRedirect('admin');
			$viewBag = array();
			$faqModel = mvc::load('faqModel');
			$viewBag['faqs'] = $faqModel->getList();

			views::view('kibrissiparisAdmin/faqs/list.cshtml', $viewBag);
		}

		public static function add() {
			$viewBag = array();
			auth::checkRedirect('admin');
			if(http::$method == 'post') {
				$faqModel = mvc::load('faqModel');
				$input = array(
					'question' => http::post('question'),
					'answer' => http::post('answer'),
					'language' => http::post('language')
					 );
				$faqID = http::post('faqid');
				if($faqID != null && contracts::isUuid($faqID)->check()) {

					$viewBag['message'] = $faqModel->update($faqID, $input).' Record Updated';
				}
				else {
					$input['faqid'] = string::generateUuid();
					$viewBag['message'] = $faqModel->insert($input).' Record Inserted';
				}
			}
			views::view('kibrissiparisAdmin/faqs/add.cshtml', $viewBag);
		}

		public static function delete($actionName, $faqID) {
			$viewBag = array();
			auth::checkRedirect('admin');
			$faqModel = mvc::load('faqModel');
			
			if(contracts::isUuid($faqID)->check()) {
				$viewBag['message'] = $faqModel->delete($faqID).' Record Deleted';
			}
			$viewBag['faqs'] = $faqModel->getList();
			views::view('kibrissiparisAdmin/faqs/list.cshtml', $viewBag);
		}

		public static function edit($actionName, $faqID) {
			$viewBag = array();
			auth::checkRedirect('admin');
			$faqModel = mvc::load('faqModel');
			$viewBag = array('faq' => $faqModel->get($faqID));
			views::view('kibrissiparisAdmin/faqs/add.cshtml', $viewBag);
		}

	


	}


?>