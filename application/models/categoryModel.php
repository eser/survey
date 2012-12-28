<?php

	/**
	 * @ignore
	 */
	class categoryModel extends model {
		/**
		 * @ignore
		 */
		public function get($uId) {
			return $this->db->createQuery()
				->setTable('categories')
				->addField('*')
				->setWhere(['categoryid=:categoryid'])
				->addParameter('categoryid', $uId)
				->setLimit(1)
				->get()
				->row();
		}

		/**
		 * @ignore
		 */
		public function getAll() {
			return $this->db->createQuery()
				->setTable('categories')
				->addField('*')
				// ->setWhere(['deletedate IS NULL'])
				->get()
				->all();
		}

		/**
		 * @ignore
		 */
		public function &getAllWithCounts($uIncludeDisabled = false) {
			$tCondition = 'sp.surveyid=s.surveyid AND sp.startdate <= :now AND (sp.enddate IS NULL OR sp.enddate >= :now)';
			if(!$uIncludeDisabled) {
				$tCondition .= ' AND sp.enabled=\'1\'';
			}

			$tReturn = array();

			$tQuery = $this->db->createQuery()
				->setTable('categories c')
				->joinTable('surveys s', 's.categoryid=c.categoryid', 'LEFT')
				->joinTable('surveypublishs sp', $tCondition, 'LEFT')
				->addField('c.*')
				->addField('COUNT(sp.*) AS count')
				->setGroupBy('c.categoryid')
				->addParameter('now', time::toDb(time()))
				->get();

			foreach($tQuery as $tRow) {
				$tReturn[$tRow['categoryid']] = $tRow;
			}

			$tQuery->close();

			return $tReturn;
		}

		/**
		 * @ignore
		 */
		public function getAllPaged($uOffset, $uLimit) {
			return $this->db->createQuery()
				->setTable('categories')
				->addField('*')
				->setOffset($uOffset)
				->setLimit($uLimit)
				// ->setWhere(['deletedate IS NULL'])
				->get()
				->all();
		}
	}

?>