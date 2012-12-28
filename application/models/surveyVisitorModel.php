<?php

	/**
	 * @ignore
	 */
	class surveyVisitorModel extends model {
		/**
		 * @ignore
		 */
		public function insert($input) {
			return $this->db->createQuery()
				->setTable('surveyvisitors')
				->setFields($input)
				->insert()
				->execute();
		}

		/**
		 * @ignore
		 */
		public function get($uId) {
			return $this->db->createQuery()
				->setTable('surveyvisitors')
				->addField('*')
				->setWhere(['surveyvisitorid=:surveyvisitorid'])
				->addParameter('surveyvisitorid', $uId)
				->setLimit(1)
				->get()
				->row();
		}
	}

?>