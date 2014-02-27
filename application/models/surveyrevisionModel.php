<?php

    namespace App\Models;

	use Scabbia\Extensions\Models\Model;

	/**
	 * @ignore
	 */
	class SurveyrevisionModel extends Model {
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