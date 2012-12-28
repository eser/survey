<?php

	/**
	 * categories controller
	 * action methods for all categories/* urls
	 */
	class categories extends controller {
		/**
		 * number of surveys per page
		 */
		const PAGE_SIZE = statics::DEFAULT_PAGE_SIZE;

		/**
		 * lists all categories available
		 */
		public function get_index() {
			// load and validate session data
			statics::requireAuthentication(1);

			// pass the previously loaded category to the view
			$this->load('categoryModel');
			$this->setRef('categories', statics::$categoriesWithCounts);

			// render the page
			$this->view();
		}

		/**
		 * lists available surveys in specific category
		 *
		 * @param $uCategoryId string the uuid represents category id
		 * @param $uPage int number of page which is going to be displayed
		 */
		public function get_list($uCategoryId, $uPage = '1') {
			// load and validate session data
			statics::requireAuthentication(1);

			// #1 validate the request: page numbers
			$tPage = intval($uPage);
			contracts::isMinimum($tPage, 1)->exception('invalid page number');
			$tOffset = ($tPage - 1) * self::PAGE_SIZE;

			// #2 validate the request: category id
			contracts::isUuid($uCategoryId)->exception('invalid category id format');
			contracts::inKeys($uCategoryId, statics::$categoriesWithCounts)->exception('invalid category id');
			$tCategory = &statics::$categoriesWithCounts[$uCategoryId];

			// #1 pass pager data to view
			$this->setRef('pagerTotal', $tCategory['count']);
			$this->setRef('pagerCurrent', $tPage);

			// #2 pass category data to view
			$this->setRef('category', $tCategory);

			// #3 gather all survey data from model
			$this->load('publishSurveyModel');
			$tPublishedSurveys = $this->publishSurveyModel->getAllByCategoryPaged($uCategoryId, $tOffset, self::PAGE_SIZE);

			// #3 pass survey data to view
			$this->setRef('surveys', $tPublishedSurveys);

			// render the page
			$this->view();
		}
	}

?>
