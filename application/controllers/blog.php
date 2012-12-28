<?php

	/**
	 * blog controller
	 * action methods for all blog/* urls
	 */
	class blog extends controller {
		/**
		 * number of blog entries per page
		 */
		const PAGE_SIZE = statics::DEFAULT_PAGE_SIZE;

		/**
		 * lists all categories available
		 *
		 * @param $uPage int number of page which is going to be displayed
		 */
		public function get_index($uPage = '1') {
			// load and validate session data - guests allowed
			statics::requireAuthentication(0);

			// validate the request: page numbers
			$tPage = intval($uPage);
			contracts::isMinimum($tPage, 1)->exception('invalid page number');
			$tOffset = ($tPage - 1) * self::PAGE_SIZE;

			// pass pager data to view
			$this->set('pagerTotal', $this->postModel->count());
			$this->setRef('pagerCurrent', $tPage);

			// pass post data to view
			$this->load('postModel');
			$this->set('posts', $this->postModel->getAllPaged($tOffset, self::PAGE_SIZE));

			// render the page
			$this->view();
		}

		/**
		 * displays specified blog post
		 *
		 * @param $uPostId string the uuid represents post id
		 */
		public function get_post($uPostId) {
			// load and validate session data - guests allowed
			statics::requireAuthentication(0);

			// validate the request: post id
			contracts::isUuid($uPostId)->exception('invalid post id format');

			$this->load('postModel');
			$tPost = $this->postModel->get($uPostId);
			contracts::isNotFalse($tPost)->exception('invalid post id');

			// pass post data to view
			$this->setRef('post', $tPost);

			// render the page
			$this->view();
		}
	}

?>
