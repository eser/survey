<?php

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
				// ->addField('updatedate', time::toDb(time()))
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
				->setWhere(['sq.surveyid=:surveyid', _and, 'sq.revision=:revision'])
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
		public function getRecent($uLimit) {
			return $this->db->createQuery()
				->setTable('questions')
				->addField('*')
				->setOrderBy('startdate', 'DESC')
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
				->setWhere(['ownerid=:ownerid', _or, 'isshared=\'1\''])
				->addParameter('ownerid', $uOwnerId)
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
		public function getByPublishID($uSurveyPublishId, $uQuestionIds) {
			return $this->db->createQuery()
				->setTable('answers a')
				->addField('a.questionid, a.questionchoiceid, a.value, COUNT(a.*)')
				->setWhere([['a.questionid', _in, $uQuestionIds], _and, 'a.surveypublishid=:surveypublishid'])
				->setGroupBy('a.questionid, a.questionchoiceid, a.value')
				->addParameter('surveypublishid', $uSurveyPublishId)
				->get()
				->all();
		}

		public function delete($uQuestionId) {
			return $this->db->createQuery()
				->setTable('questions')
				->addParameter('questionid', $uQuestionId)
				->setWhere('questionid=:questionid')
				->setLimit(1)
				->delete()
				->execute();
		}

		public  function updateChoice($questionChoiceID, $input) {
			return $this->db->createQuery()
				->setTable('questionchoices')
				->setFields($input)
				->addParameter('questionchoiceid', $questionChoiceID)
				->setWhere(['questionchoiceid=:questionchoiceid'])
				->setLimit(1)
				->update()
				->execute();
		
		}

		public  function getChoice($questionChoiceID) {
			return $this->db->createQuery()
				->setTable('questionchoices')
				->addField('*')
				->setWhere(['questionchoiceid=:questionchoiceid'])
				->addParameter('questionchoiceid', $questionChoiceID)
				->setLimit(1)
				->get()
				->row();
		
		}

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