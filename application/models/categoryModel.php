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
		public function &getAllWithCounts() {
			$tReturn = array();

			$tQuery = $this->db->createQuery()
				->setTable('categories c')
				->joinTable('surveys s', 's.categoryid=c.categoryid', 'LEFT')
				->addField('c.*')
				->addField('COUNT(s.*) AS count')
				// ->setWhere(['deletedate IS NULL'])
				->setGroupBy('c.categoryid')
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