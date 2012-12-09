<?php

	/**
	 * @ignore
	 */
	class statics {
		public static $user = null;

		/**
		 * @ignore
		 */
		public static function requireAuthentication($uLevel) {
			self::$user = & session::get('user', null);

			if($uLevel > 0 && is_null(self::$user)) {
				mvc::redirect('users/login');
				return;
			}
		}
	}

?>