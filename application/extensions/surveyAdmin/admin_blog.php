<?php

	/**
	 * Blackmore Extension: survey-e-bot Admin Panel, blog Section
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
	class admin_blog {
		/**
		 * @ignore
		 */
		public static function blackmore_registerModules($uParms) {
			$uParms['modules']['blog'] = array(
				'title' => 'Blog',
				'callback' => 'admin_blog::index',
				'submenus' => true,
				'actions' => array(
					array(
						'callback' => 'admin_blog::postList',
						'menutitle' => 'List',
						'action' => 'postList'
					),
					array(
						'callback' => 'admin_blog::add',
						'menutitle' => 'Add',
						'action' => 'add'
					),
					array(
						'callback' => 'admin_blog::delete',
						'action' => 'delete'
					),
					array(
						'callback' => 'admin_blog::edit',
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
			self::postList();
		}

		public static function postList() {
			auth::checkRedirect('admin');
			$postModel = mvc::load('postModel');
			views::set('posts', $postModel->getAllDetailed());
			views::view('surveyAdmin/blog/list.cshtml');
		}

		public static function add() {
			$viewBag = array();
			auth::checkRedirect('admin');
			$postModel = mvc::load('postModel');
			$userModel = mvc::load('userModel');
			$viewBag['users'] = $userModel->getAll();

			if(http::$method == 'post') {
				$input = array(
					'title' => http::post('title'),
					'content' => http::post('content'),
					'ownerid' => http::post('ownerid')
					 );
				$postID = http::post('postid');
				if($postID != null && contracts::isUuid($postID)->check()) {
					$viewBag['message'] = $postModel->update($postID, $input).' Record Updated';
				}
				else {
					$input['postid'] = string::generateUuid();
					$input['createdate'] = time::toDb(time());
					$viewBag['message'] = $postModel->insert($input).' Record Inserted';
				}
			}
			views::view('surveyAdmin/blog/add.cshtml', $viewBag);
		}

		public static function delete($actionName, $postID) {
			$viewBag = array();
			auth::checkRedirect('admin');
			$postModel = mvc::load('postModel');
			if(contracts::isUuid($postID)->check()) {
				$viewBag['message'] = $postModel->delete($postID).' Record Deleted';
			}
			$viewBag['posts'] = $postModel->getAllDetailed();
			views::view('surveyAdmin/blog/list.cshtml', $viewBag);
		}

		public static function edit($actionName, $postID) {
			$viewBag = array();
			auth::checkRedirect('admin');
			$postModel = mvc::load('postModel');
			$viewBag = array(
				'post' => $postModel->get($postID)
			);
			$userModel = mvc::load('userModel');
			$viewBag['users'] = $userModel->getAll();
			views::view('surveyAdmin/blog/add.cshtml', $viewBag);
		
		}



	}


?>