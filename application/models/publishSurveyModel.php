<?php

	/**
	 * @ignore
	 */
	class publishSurveyModel extends model {
		/**
		 * @ignore
		 */
		public function insert($input) {
			$tTime = time::toDb(time());
			return $this->db->createQuery()
				->setTable('surveypublishs')
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
				->setTable('surveypublishs')
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
		public function getRecent($uLimit) {
			return $this->db->createQuery()
				->setTable('surveypublishs')
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
				->joinTable('surveypublishs s', 's.surveyid=sp.surveyid', 'INNER')
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
				->setTable('surveypublishs')
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
				->setTable('surveypublishs')
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
				->setTable('surveypublishs AS sp INNER JOIN surveys AS s ON sp.surveyid=s.surveyid')
				->addField('sp.*,s.title')
				->setWhere(['sp.ownerid=:ownerid'])
				->addParameter('ownerid', $uOwnerId)
				->get()
				->all();
		}

		/**
		 * @ignore
		 */
		public function getAllPagedByOwner($uOwnerId, $uOffset, $uLimit) {
			return $this->db->createQuery()
				->setTable('surveypublishs')
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
		public function getAllBySurvey($uSurveyIds) {
			return $this->db->createQuery()
				->setTable('surveypublishs')
				->addField('*')
				->setWhere(['surveyid', _in, $uSurveyIds])
				->get()
				->all();
		}

		/**
		 * @ignore
		 */
		public function getAllByCategory($uCategoryId) {
			return $this->db->createQuery()
				->setTable('surveys s')
				->joinTable('surveypublishs sp', 'sp.surveyid=s.surveyid AND sp.startdate <= :now AND (sp.enddate IS NULL OR sp.enddate >= :now)', 'INNER')
				->addField('s.*, sp.*')
				->setWhere('s.categoryid=:categoryid')
				->addParameter('now', time::toDb(time()))
				->addParameter('categoryid', $uCategoryid)
				->get()
				->all();
		}

		/**
		 * @ignore
		 */
		public function getAllByCategoryPaged($uCategoryId, $uOffset, $uLimit) {
			return $this->db->createQuery()
				->setTable('surveys s')
				->joinTable('surveypublishs sp', 'sp.surveyid=s.surveyid AND sp.startdate <= :now AND (sp.enddate IS NULL OR sp.enddate >= :now)', 'INNER')
				->addField('s.*, sp.*')
				->setWhere('s.categoryid=:categoryid')
				->setOffset($uOffset)
				->setLimit($uLimit)
				->addParameter('now', time::toDb(time()))
				->addParameter('categoryid', $uCategoryId)
				->get()
				->all();
		}
	}

?>