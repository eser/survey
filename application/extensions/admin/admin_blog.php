<?php

	use Scabbia\Extensions\Mvc\Controllers;

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
		public static function blackmoreRegisterModules($uParms) {
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
			Auth::checkRedirect('admin');
			self::postList();
		}

		public static function postList() {
			Auth::checkRedirect('admin');
			$postModel = Controllers::load('App\\Models\\PostModel');
			Views::set('posts', $postModel->getAllDetailed());
			Views::viewFile('{app}views/surveyAdmin/blog/list.cshtml');
		}

		public static function add() {
			$viewBag = array();
			Auth::checkRedirect('admin');
			$postModel = Controllers::load('App\\Models\\PostModel');
			$userModel = Controllers::load('App\\Models\\UserModel');
			$viewBag['users'] = $userModel->getAll();

			if(Http::$method == 'post') {
				$input = array(
					'title' => Request::post('title'),
					'content' => Request::post('content'),
					'ownerid' => Request::post('ownerid')
					 );
				$postID = Request::post('postid');
				if($postID != null && Contracts::isUuid($postID)->check()) {
					$viewBag['message'] = $postModel->update($postID, $input).' Record Updated';
				}
				else {
					$input['postid'] = String::generateUuid();
					$input['createdate'] = Date::toDb(time());
					$viewBag['message'] = $postModel->insert($input).' Record Inserted';
				}
			}
			Views::viewFile('{app}views/surveyAdmin/blog/add.cshtml', $viewBag);
		}

		public static function delete($actionName, $postID) {
			$viewBag = array();
			Auth::checkRedirect('admin');
			$postModel = Controllers::load('App\\Models\\PostModel');
			if(Contracts::isUuid($postID)->check()) {
				$viewBag['message'] = $postModel->delete($postID).' Record Deleted';
			}
			$viewBag['posts'] = $postModel->getAllDetailed();
			Views::viewFile('{app}views/surveyAdmin/blog/list.cshtml', $viewBag);
		}

		public static function edit($actionName, $postID) {
			$viewBag = array();
			Auth::checkRedirect('admin');
			$postModel = Controllers::load('App\\Models\\PostModel');
			$viewBag = array(
				'post' => $postModel->get($postID)
			);
			$userModel = Controllers::load('App\\Models\\UserModel');
			$viewBag['users'] = $userModel->getAll();
			Views::viewFile('{app}views/surveyAdmin/blog/add.cshtml', $viewBag);
		
		}



	}


?>