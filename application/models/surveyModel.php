<?php

	/**
	 * @ignore
	 */
	class surveyModel extends model {
		/**
		 * @ignore
		 */
		public function insert($input) {
			$tTime = time::toDb(time());
			return $this->db->createQuery()
				->setTable('surveys')
				->setFields($input)
				->addField('createdate', $tTime)
				->insert()
				->execute();
		}

		/**
		 * @ignore
		 */
		public function get($uId) {
			return $this->db->createQuery()
				->setTable('surveys')
				->addField('*')
				->setWhere(['surveyid=:surveyid'])
				->addParameter('surveyid', $uId)
				->setLimit(1)
				->get()
				->row();
		}
		/**
		 * @ignore
		 */
		public function getDetail($uId) {
			return $this->db->createQuery()
				->setTable('surveys AS s INNER JOIN categories AS c ON s.categoryid=c.categoryid INNER JOIN languages AS l ON s.languageid=l.languageid')
				->addField('s.*,c.name,l.name as languagename')
				->setWhere(['surveyid=:surveyid'])
				->addParameter('surveyid', $uId)
				->setLimit(1)
				->get()
				->row();
		}

		/**
		 * @ignore
		 */
		public function getRecent($uLimit) {
			return $this->db->createQuery()
				->setTable('surveys')
				->addField('*')
				// ->setWhere(['deletedate IS NULL'])
				->setOrderBy('createdate', 'DESC')
				->setLimit($uLimit)
				->get()
				->all();
		}

		/**
		 * @ignore
		 */
		public function getPublishedRecent($uLimit) {
			return $this->db->createQuery()
				->setTable('surveypublishs sp')
				->joinTable('surveys s', 's.surveyid=sp.surveyid', 'INNER')
				->addField('s.*')
				// ->setWhere(['deletedate IS NULL'])
				->setOrderBy('sp.startdate', 'DESC')
				->setLimit($uLimit)
				->get()
				->all();
		}

		/**
		 * @ignore
		 */
		public function getAll() {
			return $this->db->createQuery()
				->setTable('surveys')
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
				->setTable('surveys')
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
				->setTable('surveys')
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
				->setTable('surveys')
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
				->setTable('surveys')
				->addField('*')
				->setWhere(['ownerid=:ownerid'])
				->addParameter('ownerid', $uOwnerId)
				->calculate('COUNT');
		}

		/**
		 * @ignore
		 */
		public function &getAllNamesByOwner($uOwnerId) {
			$tResult = array();

			$tQuery = $this->db->createQuery()
				->setTable('surveys')
				->addField('surveyid, title')
				->setWhere(['ownerid=:ownerid'])
				->addParameter('ownerid', $uOwnerId)
				->get();

			foreach($tQuery as $tRow) {
				$tResult[$tRow['surveyid']] = $tRow;
			}

			$tQuery->close();

			return $tResult;
		}
	}

?>