<?php

	/**
	 * @ignore
	 */
	class answersModel extends model {
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
				// ->setWhere(['deletedate IS NULL'])
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
				// ->setWhere(['deletedate IS NULL'])
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
				// ->setWhere(['deletedate IS NULL'])
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
	}

?>