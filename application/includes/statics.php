<?php

	/**
	 * it's a static class contains static
	 * definition for our survey system
	 */
	class statics {
		// default page sizes for pager-enabled pages
		const DEFAULT_PAGE_SIZE = 20;

		// logged user
		public static $user = null;

		// categories, languages, themes and recent surveys
		public static $categoriesWithCounts = null;
		public static $languagesWithCounts = null;
		public static $themesWithCounts = null;
		public static $recentSurveys;

		// month abbreviations
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

		// question types
		const QUESTION_EVALUATION = '0';
		const QUESTION_MULTIPLE = '1';
		const QUESTION_FILL = '2';

		public static $questiontypes = array(
			self::QUESTION_EVALUATION => 'Evaluation',
			self::QUESTION_MULTIPLE => 'Multiple Choice',
			self::QUESTION_FILL => 'Fill In The Blanks'
		);

		// question type filters
		const QUESTIONFILTER_ANY = '0';
		const QUESTIONFILTER_NUMERIC = '1';
		const QUESTIONFILTER_ALPHANUMERIC = '2';

		public static $questiontypefilters = array(
			self::QUESTIONFILTER_ANY => 'Any',
			self::QUESTIONFILTER_NUMERIC => 'Numeric',
			self::QUESTIONFILTER_ALPHANUMERIC => 'Alphanumeric'
		);

		// survey accessibility
		const SURVEY_AUTHONLY = '0';
		const SURVEY_PUBLIC = '1';

		public static $surveytypes = array(
			 self::SURVEY_AUTHONLY => 'Auth-Only',
			 self::SURVEY_PUBLIC => 'Public'
		);

		// question accessibility
		const SHARE_PRIVATE = '0';
		const SHARE_SHARED = '1';

		public static $sharedboolean = array(
			 self::SHARE_PRIVATE => 'Private',
			 self::SHARE_SHARED => 'Shared'
		);

		// survey status
		const SURVEY_DISABLED = '0';
		const SURVEY_ENABLED = '1';

		public static $surveystatus = array(
			 self::SURVEY_DISABLED => 'Disabled',
			 self::SURVEY_ENABLED => 'Enabled'
		);

		// question optiontypes
		const QUESTIONOPTION_NOINPUT = '0';
		const QUESTIONOPTION_ANY = '1';
		const QUESTIONOPTION_NUMERIC = '2';
		const QUESTIONOPTION_ALPHANUMERIC = '3';

		public static $questionoptiontypes = array(
			self::QUESTIONOPTION_NOINPUT => 'No Input',
			self::QUESTIONOPTION_ANY => 'Any',
			self::QUESTIONOPTION_NUMERIC => 'Numeric',
			self::QUESTIONOPTION_ALPHANUMERIC => 'Alphanumeric'
		);

		// evaluation options
		public static $evaluationoptions = array(
			'1' => 'Strongly Agree',
			'2' => 'Agree',
			'3' => 'Neutral',
			'4' => 'Disagree',
			'5' => 'Strongly Disagree'
		);

		/**
		 * gets the user information from existing session and
		 * redirects user to the login page if user is restricted
		 *
		 * @param $uLevel int required user level
		 */
		public static function requireAuthentication($uLevel) {
			self::$user = & session::get('user', null);

			if($uLevel == -1 && !is_null(self::$user)) {
				throw new Exception('you are not supposed to be in this page.');
			}

			if($uLevel > 0 && is_null(self::$user)) {
				mvc::redirect('users/login');
				return;
			}
		}

		/**
		 * reloads the stored user information in session storage
		 *
		 * @param $uIncludeUser bool gets the user information from database again
		 */
		public static function reloadUserInfo($uIncludeUser = true) {
			self::$user = & session::get('user');

			if($uIncludeUser) {
				$tUserModel = mvc::load('userModel');
				self::$user = $tUserModel->get(self::$user['userid']);

				session::set('user', self::$user);
			}
		}

		/**
		 * prints the date in pretty formatting
		 *
		 * @param $uDate int the timestamp
		 * @param $uShowHours bool whether show time or not
		 * @param $uHumanize bool use meaningful language such as '2 hours ago', 'yesterday'
		 */
		public static function datePrint($uDate, $uShowHours = true, $uHumanize = true) {
			if(is_null($uDate) || strlen($uDate) <= 0) {
				return '-';
			}

			if($uHumanize) {
				return time::humanize(time::convert($uDate, 'Y-m-d H:i:s'), time(), true, $uShowHours);
			}

			return time::convert($uDate, 'Y-m-d H:i:s', (($uShowHours) ? 'd.m.Y H:i' : 'd.m.Y'));
		}

		/**
		 * gets the mostly needed variables from the database
		 * in order to be use in page templates
		 */
		public static function templateBindings() {
			$tCategoryModel = mvc::load('categoryModel');
			self::$categoriesWithCounts = $tCategoryModel->getAllWithCounts();

			$tLanguageModel = mvc::load('languageModel');
			self::$languagesWithCounts = $tLanguageModel->getAllWithCounts();

			$tThemeModel = mvc::load('themeModel');
			self::$themesWithCounts = $tThemeModel->getAllWithCounts();

			$tsurveypublishModel = mvc::load('surveypublishModel');
			self::$recentSurveys = $tsurveypublishModel->getRecent(6);
		}

		/**
		 * a shortcut to show survey categories in a combobox
		 *
		 * @param $uDefault mixed the default selected value
		 */
		public static function selectboxCategories($uDefault = null) {
			return html::selectOptions(self::$categoriesWithCounts, $uDefault, 'name');
		}

		/**
		 * a shortcut to show system languages in a combobox
		 *
		 * @param $uDefault mixed the default selected value
		 */
		public static function selectboxLanguages($uDefault = null) {
			return html::selectOptions(self::$languagesWithCounts, $uDefault, 'name');
		}

		/**
		 * a shortcut to show survey themes in a combobox
		 *
		 * @param $uDefault mixed the default selected value
		 */
		public static function selectboxThemes($uDefault = null) {
			return html::selectOptions(self::$themesWithCounts, $uDefault, 'name');
		}

		/**
		 * gets the specified template file and replace bindings with the user data
		 *
		 * @param $uPath string template file's path
		 * @param $uValues array array of replacements will be made
		 * @param $uArray base array
		 */
		public static function &emailTemplate($uPath, $uValues, $uArray = array()) {
			$tHtmlBody = file_get_contents(QPATH_BASE . $uPath);

			$uArray['{URL}'] = http::baseUrl();
			foreach($uValues as $tKey => &$tValue) {
				$uArray['{' . strtoupper($tKey) . '}'] = $tValue;
			}

			foreach($uArray as $tKey => &$tValue) {
				$tHtmlBody = str_replace($tKey, $tValue, $tHtmlBody);
			}

			return $tHtmlBody;
		}
	}

?>