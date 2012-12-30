<?php

	/**
	 * @ignore
	 */
	class postModel extends model {
		/**
		 * @ignore
		 */
		public function get($uId) {
			return $this->db->createQuery()
				->setTable('posts')
				->addField('*')
				->setWhere(['postid=:postid'])
				->addParameter('postid', $uId)
				->setLimit(1)
				->get()
				->row();
		}

		/**
		 * @ignore
		 */
		public function getAll() {
			return $this->db->createQuery()
				->setTable('posts')
				->addField('*')
				->get()
				->all();
		}

		/**
		 * @ignore
		 */
		public function getAllPaged($uOffset, $uLimit) {
			return $this->db->createQuery()
				->setTable('posts')
				->addField('*')
				->setOffset($uOffset)
				->setLimit($uLimit)
				->get()
				->all();
		}

		/**
		 * @ignore
		 */
		public function count() {
			return $this->db->createQuery()
				->setTable('posts')
				->addField('*')
				->calculate('COUNT');
		}
	}

?>