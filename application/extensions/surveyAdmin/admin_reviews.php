<?php

	/**
	 * Blackmore Extension: Kibrissiparis Admin Panel, reviews Section
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
	class admin_reviews {
		/**
		 * @ignore
		 */
		public static function blackmore_registerModules($uParms) {
			$uParms['modules']['reviews'] = array(
				'title' => 'Reviews',
				'callback' => 'admin_reviews::index',
				'submenus' => true,
				'actions' => array(
					
					array(
						'callback' => 'admin_reviews::reviewList',
						'menutitle' => 'List',
						'action' => 'reviewList'
					),
					array(
						'callback' => 'admin_reviews::confirm',
						'action' => 'confirm'
					),
					array(
						'callback' => 'admin_reviews::delete',
						'action' => 'delete'
					),
					array(
						'callback' => 'admin_reviews::edit',
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
			self::reviewList();
		}

		public static function reviewList() {
			auth::checkRedirect('admin');
			$reviewModel = mvc::load('reviewModel');
			$viewBag = array('reviews' => $reviewModel->getList());
			views::view('kibrissiparisAdmin/reviews/list.cshtml', $viewBag);
		}

		public static function confirm($actionName, $reviewID) {
			$viewBag = array();
			auth::checkRedirect('admin');
			$reviewModel = mvc::load('reviewModel');
			if(contracts::isUuid($reviewID)->check()) {
				$viewBag['message'] = $reviewModel->confirm($reviewID).' Record Updated';
			}
			$viewBag['reviews'] = $reviewModel->getList();
			views::view('kibrissiparisAdmin/reviews/list.cshtml', $viewBag);

		}

		public static function delete($actionName, $reviewID) {
			$viewBag = array();
			auth::checkRedirect('admin');
			$reviewModel = mvc::load('reviewModel');
			if(contracts::isUuid($reviewID)->check()) {
				$viewBag['message'] = $reviewModel->delete($reviewID).' Record Deleted';
			}
			$viewBag['reviews'] = $reviewModel->getList();
			views::view('kibrissiparisAdmin/reviews/list.cshtml', $viewBag);
		}

		public static function edit($actionName, $reviewID) {
			$viewBag = array();
			auth::checkRedirect('admin');
			$reviewModel = mvc::load('reviewModel');
			if(http::$method == 'post') {
				$input = array(
					'comment' => http::post('comment')
					 );
				
				$viewBag['message'] = $reviewModel->updateReview($reviewID, $input).' Record Updated';
			}
			$viewBag['review'] = $reviewModel->getReview($reviewID);
			views::view('kibrissiparisAdmin/reviews/edit.cshtml', $viewBag);

		}


	}


?>