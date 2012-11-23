<?php

	/**
	 * @ignore
	 */
	class userModel extends model {
		/**
		 * @ignore
		 */
		public function getAll() {
			return $this->db->createQuery()
				->setTable('users')
				->addField('*')
				// ->setWhere(['deletedate IS NULL'])
				->get()
				->all();
		}

		/**
		 * @ignore
		 */
		public function getByEmail($uEmail) {
			return $this->db->createQuery()
				->setTable('users')
				->addField('*')
				->setWhere(['email=:email'])
				->addParameter('email', $uEmail)
				->setLimit(1)
				->get()
				->row();
		}
	}

?>