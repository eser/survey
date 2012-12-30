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
		public function update($surveyid, $input) {
			return $this->db->createQuery()
				->setTable('surveys')
				->setFields($input)
				->addParameter('surveyid', $surveyid)
				->setWhere(['surveyid=:surveyid'])
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
				->setTable('surveys s')
				->addField('s.*, (SELECT MAX(sr.revision) FROM surveyrevisions sr WHERE sr.surveyid=s.surveyid) AS lastrevision')
				->setWhere(['s.surveyid=:surveyid'])
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
				->setTable('surveys s')
				->joinTable('categories c', 'c.categoryid=s.categoryid', 'INNER')
				->joinTable('languages l', 'l.languageid=s.languageid', 'INNER')
				->addField('s.*, c.name, l.name AS languagename')
				->setWhere(['s.surveyid=:surveyid'])
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
				->setOrderBy('createdate', 'DESC')
				->setLimit($uLimit)
				->get()
				->all();
		}

		/**
		 * @ignore
		 */
		public function getAll() {
			return $this->db->createQuery()
				->setTable('surveys s')
				->addField('s.*, (SELECT MAX(sr.revision) FROM surveyrevisions sr WHERE sr.surveyid=s.surveyid) AS lastrevision')
				->get()
				->all();
		}

		/**
		 * @ignore
		 */
		public function getAllPaged($uOffset, $uLimit) {
			return $this->db->createQuery()
				->setTable('surveys s')
				->addField('s.*, (SELECT MAX(sr.revision) FROM surveyrevisions sr WHERE sr.surveyid=s.surveyid) AS lastrevision')
				->setOffset($uOffset)
				->setLimit($uLimit)
				->get()
				->all();
		}

		/**
		 * @ignore
		 */
		public function &getAllByOwner($uOwnerId) {
			$tResult = array();

			$tQuery = $this->db->createQuery()
				->setTable('surveys s')
				->addField('s.*, (SELECT MAX(sr.revision) FROM surveyrevisions sr WHERE sr.surveyid=s.surveyid) AS lastrevision')
				->setWhere(['s.ownerid=:ownerid'])
				->addParameter('ownerid', $uOwnerId)
				->get();

			foreach($tQuery as $tRow) {
				$tResult[$tRow['surveyid']] = $tRow;
			}

			$tQuery->close();

			return $tResult;
		}
		/**
		 * @ignore
		 */
		public function getAllPagedByOwner($uOwnerId, $uOffset, $uLimit) {
			return $this->db->createQuery()
				->setTable('surveys s')
				->addField('s.*, (SELECT MAX(sr.revision) FROM surveyrevisions sr WHERE sr.surveyid=s.surveyid) AS lastrevision')
				->setOffset($uOffset)
				->setLimit($uLimit)
				->setWhere(['s.ownerid=:ownerid'])
				->setOrderBy('s.categoryid ASC')
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