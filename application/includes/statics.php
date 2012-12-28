<?php

	/**
	 * @ignore
	 */
	class statics {
		const DEFAULT_PAGE_SIZE = 20;

		const QUESTION_EVALUATION = '0';
		const QUESTION_MULTIPLE = '1';
		const QUESTION_FILL = '2';

		public static $user = null;
		public static $categoriesWithCounts = null;
		public static $languagesWithCounts = null;
		public static $themesWithCounts = null;
		public static $recentSurveys;

		public static $months = array(
			 1 => 'Jan',
			 2 => 'Feb',
			 3 => 'Mar',
			 4 => 'Apr',
			 5 => 'May',
			 6 => 'Jun',
			 7 => 'Jul',
			 8 => 'Aug',
			 9 => 'Sep',
			10 => 'Oct',
			11 => 'Nov',
			12 => 'Dec'
		);

		public static $questiontypes = array(
			self::QUESTION_EVALUATION => 'Evaluation',
			self::QUESTION_MULTIPLE => 'Multiple Choice',
			self::QUESTION_FILL => 'Fill In The Blanks'
		);

		public static $questiontypefilters = array(
			'0' => 'Any',
			'1' => 'Numeric',
			'2' => 'Alphanumeric'
		);
		
		public static $questionoptiontypes = array(
			'0' => 'No Input',
			'1' => 'Any',
			'2' => 'Numeric',
			'3' => 'Alphanumeric'
		);

		public static $evaluationoptions = array(
			'1' => 'Strongly Agree',
			'2' => 'Agree',
			'3' => 'Neutral',
			'4' => 'Disagree',
			'5' => 'Strongly Disagree'
		);

		public static $surveytypes = array(
			 '0' => 'Auth-Only',
			 '1' => 'Public'
		);

		public static $surveystatus = array(
			 '0' => 'Disabled',
			 '1' => 'Enabled'
		);

		/**
		 * @ignore
		 */
		public static function requireAuthentication($uLevel) {
			self::$user = & session::get('user', null);

			if($uLevel == -1 && !is_null(self::$user)) {
				// todo: you're already authorized page.
			}

			if($uLevel > 0 && is_null(self::$user)) {
				mvc::redirect('users/login');
				return;
			}
		}

		/**
		 * @ignore
		 */
		public static function reloadUserInfo($includeUser = true) {
			self::$user = & session::get('user');

			if($includeUser) {
				$tUserModel = mvc::load('userModel');
				self::$user = $tUserModel->get(self::$user['userid']);

				session::set('user', self::$user);
			}
		}

		/**
		 * @ignore
		 */
		public static function datePrint($uDate, $uShowHours = true, $uHumanize = true) {
			if($uHumanize) {
				return time::humanize(time::convert($uDate, 'Y-m-d H:i:s'), time(), true);
			}

			if(is_null($uDate)) {
				return '-';
			}

			return time::convert($uDate, 'Y-m-d H:i:s', (($uShowHours) ? 'd.m.Y H:i' : 'd.m.Y'));
		}

		/**
		 * @ignore
		 */
		public static function templateBindings() {
			$tCategoryModel = mvc::load('categoryModel');
			self::$categoriesWithCounts = $tCategoryModel->getAllWithCounts();

			$tLanguageModel = mvc::load('languageModel');
			self::$languagesWithCounts = $tLanguageModel->getAllWithCounts();

			$tThemeModel = mvc::load('themeModel');
			self::$themesWithCounts = $tThemeModel->getAllWithCounts();

			$tSurveyModel = mvc::load('surveyModel');
			self::$recentSurveys = $tSurveyModel->getPublishedRecent(6);
		}

		/**
		 * @ignore
		 */
		public static function selectboxCategories($uDefault = null) {
			return html::selectOptions(self::$categoriesWithCounts, $uDefault, 'name');
		}

		/**
		 * @ignore
		 */
		public static function selectboxLanguages($uDefault = null) {
			return html::selectOptions(self::$languagesWithCounts, $uDefault, 'name');
		}

		/**
		 * @ignore
		 */
		public static function selectboxThemes($uDefault = null) {
			return html::selectOptions(self::$themesWithCounts, $uDefault, 'name');
		}
	}

?>