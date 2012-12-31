<?php

	/**
	 * @ignore
	 */
	class surveyquestionModel extends model {
		/**
		 * @ignore
		 */
		public function insert($input) {
			return $this->db->createQuery()
				->setTable('surveyquestions')
				->setFields($input)
				->insert()
				->execute();
		}
	}

?>