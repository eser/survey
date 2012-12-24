<?php

	/**
	 * @ignore
	 */
	class themeModel extends model {
		/**
		 * @ignore
		 */
		public function get($uId) {
			return $this->db->createQuery()
				->setTable('themes')
				->addField('*')
				->setWhere(['themeid=:themeid'])
				->addParameter('themeid', $uId)
				->setLimit(1)
				->get()
				->row();
		}

		/**
		 * @ignore
		 */
		public function getAll() {
			return $this->db->createQuery()
				->setTable('themes')
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
				->setTable('themes t')
				->joinTable('surveys s', 's.themeid=t.themeid', 'LEFT')
				->addField('t.*')
				->addField('COUNT(s.*) AS count')
				// ->setWhere(['deletedate IS NULL'])
				->setGroupBy('t.themeid')
				->get();

			foreach($tQuery as $tRow) {
				$tReturn[$tRow['themeid']] = $tRow;
			}

			$tQuery->close();

			return $tReturn;
		}

		/**
		 * @ignore
		 */
		public function getAllPaged($uOffset, $uLimit) {
			return $this->db->createQuery()
				->setTable('themes')
				->addField('*')
				->setOffset($uOffset)
				->setLimit($uLimit)
				// ->setWhere(['deletedate IS NULL'])
				->get()
				->all();
		}
	}

?>