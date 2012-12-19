<?php

	/**
	 * @ignore
	 */
	class statics {
		public static $user = null;

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
	}

?>