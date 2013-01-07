<?php

	use Scabbia\model;

	/**
	 * @ignore
	 */
	class surveyrevisionModel extends model {
		/**
		 * @ignore
		 */
		public function insert($input) {
			return $this->db->createQuery()
				->setTable('surveyrevisions')
				->setFields($input)
				->insert()
				->execute();
		}
	}

?>