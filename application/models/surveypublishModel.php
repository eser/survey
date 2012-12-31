<?php

	/**
	 * @ignore
	 */
	class surveypublishModel extends model {
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
		public function update($uSurveyPublishsId, $update) {
			return $this->db->createQuery()
				->setTable('surveypublishs')
				->setFields($update)
				->addParameter('surveypublishid', $uSurveyPublishsId)
				->setWhere('surveypublishid=:surveypublishid')
				->setLimit(1)
				->update()
				->execute();
		}

		/**
		 * @ignore
		 */
		public function get($uId) {
			return $this->db->createQuery()
				->setTable('surveypublishs sp')
				->joinTable('surveys s', 's.surveyid=sp.surveyid', 'INNER')
				->addField('sp.*, s.*')
				->setWhere(['sp.surveypublishid=:surveypublishid'])
				->addParameter('surveypublishid', $uId)
				->setLimit(1)
				->get()
				->row();
		}

		/**
		 * @ignore
		 */
		public function getRecent($uLimit, $uIncludeDisabled = false) {
			$tCondition = 'sp.surveyid=s.surveyid AND sp.startdate <= :now AND (sp.enddate IS NULL OR sp.enddate >= :now)';
			if(!$uIncludeDisabled) {
				$tCondition .= ' AND sp.enabled=\'1\'';
			}

			return $this->db->createQuery()
				->setTable('surveys s')
				->joinTable('surveypublishs sp', $tCondition, 'INNER')
				->addField('s.*, sp.*')
				->setOrderBy('sp.startdate', 'DESC')
				->setLimit($uLimit)
				->addParameter('now', time::toDb(time()))
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
				->get()
				->all();
		}

		/**
		 * @ignore
		 */
		public function getAllByOwner($uOwnerId) {
			return $this->db->createQuery()
				->setTable('surveypublishs sp')
				->joinTable('surveys s', 'sp.surveyid=s.surveyid', 'INNER')
				->addField('sp.*, s.title')
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
		public function getAllByCategory($uCategoryId, $uIncludeDisabled = false) {
			$tCondition = 'sp.surveyid=s.surveyid AND sp.startdate <= :now AND (sp.enddate IS NULL OR sp.enddate >= :now)';
			if(!$uIncludeDisabled) {
				$tCondition .= ' AND sp.enabled=\'1\'';
			}
		
			return $this->db->createQuery()
				->setTable('surveys s')
				->joinTable('surveypublishs sp', $tCondition, 'INNER')
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
		public function getAllByCategoryPaged($uCategoryId, $uOffset, $uLimit, $uIncludeDisabled = false) {
			$tCondition = 'sp.surveyid=s.surveyid AND sp.startdate <= :now AND (sp.enddate IS NULL OR sp.enddate >= :now)';
			if(!$uIncludeDisabled) {
				$tCondition .= ' AND sp.enabled=\'1\'';
			}

			return $this->db->createQuery()
				->setTable('surveys s')
				->joinTable('surveypublishs sp', $tCondition, 'INNER')
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