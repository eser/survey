<?php

	/**
	 * @ignore
	 */
	class categories extends controller {
		const PAGE_SIZE = statics::DEFAULT_PAGE_SIZE;

		/**
		 * @ignore
		 */
		public function get_index() {
			statics::requireAuthentication(1);

			$this->load('categoryModel');

			// assign the user data to view
			$this->setRef('categories', statics::$categoriesWithCounts);

			// render the page
			$this->view();
		}

		/**
		 * @ignore
		 */
		public function get_list($uCategoryId, $uPage = '1') {
			statics::requireAuthentication(1);

			$tPage = intval($uPage);
			if($tPage < 1) {
				$tPage = 1;
			}

			$tCategory = &statics::$categoriesWithCounts[$uCategoryId];

			$this->load('publishSurveyModel');

			// gather all survey data from model
			$tOffset = ($tPage - 1) * self::PAGE_SIZE;
			$tPublishedSurveys = $this->publishSurveyModel->getAllByCategoryPaged($uCategoryId, $tOffset, self::PAGE_SIZE);

			// assign the user data to view
			$this->set('pagerTotal', $tCategory['count']);
			$this->setRef('pagerCurrent', $tPage);
			$this->setRef('surveys', $tPublishedSurveys);
			$this->setRef('category', $tCategory);

			// render the page
			$this->view();
		}
	}

?>
