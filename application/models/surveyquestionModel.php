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

		/**
		 * @ignore
		 */
		public function transferQuestions($uSurveyId, $uFromRevision, $uToRevision) {
			return $this->db->query(
				'INSERT INTO surveyquestions SELECT surveyid, :torevision, questionid FROM surveyquestions WHERE surveyid=:surveyid AND revision=:fromrevision',
				array(
					'surveyid' => $uSurveyId,
					'fromrevision' => $uFromRevision,
					'torevision' => $uToRevision
				)
			)->execute();
		}
	}

?>