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
		public function delete($uSurveyId, $uRevision, $uQuestionId) {
			return $this->db->createQuery()
				->setTable('surveyquestions')
				->setWhere(['surveyid=:surveyid', _and, 'revision=:revision', _and, 'questionid=:questionid'])
				->setLimit(1)
				->addParameter('surveyid', $uSurveyId)
				->addParameter('revision', $uRevision)
				->addParameter('questionid', $uQuestionId)
				->delete()
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