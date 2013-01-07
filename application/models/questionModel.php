<?php

	use Scabbia\model;

	/**
	 * @ignore
	 */
	class questionModel extends model {
		/**
		 * @ignore
		 */
		public function insert($input) {
			return $this->db->createQuery()
				->setTable('questions')
				->setFields($input)
				->insert()
				->execute();
		}

		/**
		 * @ignore
		 */
		public function update($questionid, $input) {
			return $this->db->createQuery()
				->setTable('questions')
				->setFields($input)
				->addParameter('questionid', $questionid)
				->setWhere(['questionid=:questionid'])
				->setLimit(1)
				->update()
				->execute();
		}

		/**
		 * @ignore
		 */
		public function get($uId) {
			return $this->db->createQuery()
				->setTable('questions')
				->addField('*')
				->setWhere(['questionid=:questionid'])
				->addParameter('questionid', $uId)
				->setLimit(1)
				->get()
				->row();
		}

		/**
		 * @ignore
		 */
		public function getBySurveyID($uId, $uRevision) {
			return $this->db->createQuery()
				->setTable('surveyquestions sq')
				->joinTable('questions q', 'sq.questionid=q.questionid', 'INNER')
				->addField('*')
				->setWhere(['sq.surveyid=:surveyid', _AND, 'sq.revision=:revision'])
				->addParameter('surveyid', $uId)
				->addParameter('revision', $uRevision)
				->get()
				->all();
		}

		/**
		 * @ignore
		 */
		public function getChoicesByQuestionID($uId) {
			return $this->db->createQuery()
				->setTable('questionchoices')
				->addField('*')
				->setWhere(['questionid=:questionid'])
				->addParameter('questionid', $uId)
				->get()
				->all();
		}

		/**
		 * @ignore
		 */
		public function &getChoicesByQuestionIDs($uIds) {
			$tResult = array();

			$tQuery = $this->db->createQuery()
				->setTable('questionchoices')
				->addField('*')
				->setWhere(['questionid', _IN, $uIds])
				->get();

			foreach($tQuery as $tRow) {
				if(!isset($tResult[$tRow['questionid']])) {
					$tResult[$tRow['questionid']] = array();
				}
				$tResult[$tRow['questionid']][] = $tRow;
			}

			$tQuery->close();

			return $tResult;
		}

		/**
		 * @ignore
		 */
		public function getRecent($uLimit) {
			return $this->db->createQuery()
				->setTable('questions')
				->addField('*')
				->setWhere(['enabled=\'1\''])
				->setOrderBy('startdate DESC')
				->setLimit($uLimit)
				->get()
				->all();
		}

		/**
		 * @ignore
		 */
		public function getAll() {
			return $this->db->createQuery()
				->setTable('questions')
				->addField('*')
				->get()
				->all();
		}

		/**
		 * @ignore
		 */
		public function getAllAccessible($uOwnerId) {
			return $this->db->createQuery()
				->setTable('questions')
				->addField('*')
				->setWhere([['ownerid=:ownerid', _OR, 'isshared=\'1\''], _AND, 'enabled=\'1\''])
				->addParameter('ownerid', $uOwnerId)
				->get()
				->all();
		}

		/**
		 * @ignore
		 */
		public function getAllAccessibleExcept($uOwnerId, $uSurveyId, $uRevision) {
			return $this->db->createQuery()
				->setTable('questions')
				->addField('*')
				->setWhere([['ownerid=:ownerid', _OR, 'isshared=\'1\''], _AND, 'questionid NOT IN (SELECT questionid FROM surveyquestions WHERE surveyid=:surveyid AND revision=:revision)', _AND, 'enabled=\'1\''])
				->addParameter('ownerid', $uOwnerId)
				->addParameter('surveyid', $uSurveyId)
				->addParameter('revision', $uRevision)
				->get()
				->all();
		}

		/**
		 * @ignore
		 */
		public function getAllPaged($uOffset, $uLimit) {
			return $this->db->createQuery()
				->setTable('questions')
				->addField('*')
				->setOrderBy('enabled DESC')
				->setOffset($uOffset)
				->setLimit($uLimit)
				->get()
				->all();
		}

		/**
		 * @ignore
		 */
		public function getAllByOwner($uOwnerId) {
			return $this->db->createQuery()
				->setTable('questions')
				->addField('*')
				->setWhere(['ownerid=:ownerid'])
				->setOrderBy('enabled DESC')
				->addParameter('ownerid', $uOwnerId)
				->get()
				->all();
		}

		/**
		 * @ignore
		 */
		public function getAllPagedByOwner($uOwnerId, $uOffset, $uLimit) {
			return $this->db->createQuery()
				->setTable('questions')
				->addField('*')
				->setOffset($uOffset)
				->setLimit($uLimit)
				->setWhere(['ownerid=:ownerid'])
				->setOrderBy('enabled DESC')
				->addParameter('ownerid', $uOwnerId)
				->get()
				->all();
		}

		/**
		 * @ignore
		 */
		public function countByOwner($uOwnerId) {
			return $this->db->createQuery()
				->setTable('questions')
				->addField('*')
				->setWhere(['ownerid=:ownerid'])
				->addParameter('ownerid', $uOwnerId)
				->calculate('COUNT');
		}

		/**
		 * @ignore
		 */
		public function insertChoice($input) {
			return $this->db->createQuery()
				->setTable('questionchoices')
				->setFields($input)
				->insert()
				->execute();
		}

		/**
		 * @ignore
		 */
		public function getAllChoices($uQuestionId) {
			return $this->db->createQuery()
				->setTable('questionchoices')
				->addField('*')
				->setWhere(['questionid=:questionid'])
				->addParameter('questionid', $uQuestionId)
				->get()
				->all();
		}

		/**
		 * @ignore
		 */
		public function truncateChoices($uQuestionId) {
			return $this->db->createQuery()
				->setTable('questionchoices')
				->setWhere(['questionid=:questionid'])
				->addParameter('questionid', $uQuestionId)
				->delete()
				->execute();
		}

		/**
		 * @ignore
		 */
		public function insertAnswer($input) {
			return $this->db->createQuery()
				->setTable('answers')
				->setFields($input)
				->insert()
				->execute();
		}

		/**
		 * @ignore
		 */
		public function getAnswersByPublishID($uSurveyPublishId) {
			return $this->db->createQuery()
				->setTable('answers a')
				->addField('a.surveypublishid, a.questionid, a.questionchoiceid, a.value, COUNT(a.*)')
				->setWhere(['a.surveypublishid=:surveypublishid'])
				->setGroupBy('a.surveypublishid, a.questionid, a.questionchoiceid, a.value')
				->addParameter('surveypublishid', $uSurveyPublishId)
				->get()
				->all();
		}

		/**
		 * @ignore
		 */
		public function delete($uQuestionId) {
			return $this->db->createQuery()
				->setTable('questions')
				->addParameter('questionid', $uQuestionId)
				->setWhere('questionid=:questionid')
				->setLimit(1)
				->delete()
				->execute();
		}

		/**
		 * @ignore
		 */
		public function updateChoice($questionChoiceID, $input) {
			return $this->db->createQuery()
				->setTable('questionchoices')
				->setFields($input)
				->addParameter('questionchoiceid', $questionChoiceID)
				->setWhere(['questionchoiceid=:questionchoiceid'])
				->setLimit(1)
				->update()
				->execute();
		
		}

		/**
		 * @ignore
		 */
		public function getChoice($questionChoiceID) {
			return $this->db->createQuery()
				->setTable('questionchoices')
				->addField('*')
				->setWhere(['questionchoiceid=:questionchoiceid'])
				->addParameter('questionchoiceid', $questionChoiceID)
				->setLimit(1)
				->get()
				->row();
		
		}

		/**
		 * @ignore
		 */
		public function deleteChoice($questionChoiceID) {
			return $this->db->createQuery()
				->setTable('questionchoices')
				->addParameter('questionchoiceid', $questionChoiceID)
				->setWhere('questionchoiceid=:questionchoiceid')
				->setLimit(1)
				->delete()
				->execute();
		}
	}

?>