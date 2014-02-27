<?php

    namespace App\Controllers;

    use App\Includes\Statics;
    use App\Includes\SurveyController;
	use Scabbia\Extensions\Mvc\Controller;
	use Scabbia\Extensions\Validation\Contracts;
	use Scabbia\Extensions\Helpers\Arrays;
    use Scabbia\Request;

	/**
	 * categories controller
	 * action methods for all categories/* urls
	 */
	class Categories extends SurveyController {
		/**
		 * number of surveys per page
		 */
		const PAGE_SIZE = Statics::DEFAULT_PAGE_SIZE;

		/**
		 * lists all categories available
		 */
		public function get_index() {
			// load and validate session data
			Statics::requireAuthentication(0);

			// pass the previously loaded category to the view
			$this->load('App\\Models\\CategoryModel');
			$this->setRef('categories', Statics::$categoriesWithCounts);

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
			Statics::requireAuthentication(0);

			// #1 validate the request: page numbers
			$tPage = intval($uPage);
			Contracts::isMinimum($tPage, 1)->exception('invalid page number');
			$tOffset = ($tPage - 1) * self::PAGE_SIZE;

			// #2 validate the request: category id
			Contracts::isUuid($uCategoryId)->exception('invalid category id format');
			Contracts::inKeys($uCategoryId, Statics::$categoriesWithCounts)->exception('invalid category id');
			$tCategory = &Statics::$categoriesWithCounts[$uCategoryId];

			// #1 pass pager data to view
			$this->setRef('pagerTotal', $tCategory['count']);
			$this->setRef('pagerCurrent', $tPage);

			// #2 pass category data to view
			$this->setRef('category', $tCategory);

			// #3 gather all survey data from model
			$this->load('App\\Models\\SurveypublishModel');
			$tPublishedSurveys = $this->surveypublishModel->getAllByCategoryPaged($uCategoryId, $tOffset, self::PAGE_SIZE);

			// #3 pass survey data to view
			$this->setRef('surveys', $tPublishedSurveys);

			// #4 get survey-related users
			$tUserIDs = arrays::column($tPublishedSurveys, 'ownerid');
			$this->load('App\\Models\\UserModel');
			$tUsers = $this->userModel->getAllByIDs($tUserIDs);
			$this->setRef('users', $tUsers);

			// render the page
			$this->view();
		}
	}

?>
