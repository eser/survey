<?php

	/**
	 * @ignore
	 */
	class languageModel extends model {
		/**
		 * @ignore
		 */
		public function get($uId) {
			return $this->db->createQuery()
				->setTable('languages')
				->addField('*')
				->setWhere(['languageid=:languageid'])
				->addParameter('languageid', $uId)
				->setLimit(1)
				->get()
				->row();
		}

		/**
		 * @ignore
		 */
		public function getAll() {
			return $this->db->createQuery()
				->setTable('languages')
				->addField('*')
				->get()
				->all();
		}

		/**
		 * @ignore
		 */
		public function &getAllWithCounts() {
			$tReturn = array();

			$tQuery = $this->db->createQuery()
				->setTable('languages l')
				->joinTable('surveys s', 's.languageid=l.languageid', 'LEFT')
				->addField('l.*')
				->addField('COUNT(s.*) AS count')
				->setGroupBy('l.languageid')
				->get();

			foreach($tQuery as $tRow) {
				$tReturn[$tRow['languageid']] = $tRow;
			}

			$tQuery->close();

			return $tReturn;
		}

		/**
		 * @ignore
		 */
		public function getAllPaged($uOffset, $uLimit) {
			return $this->db->createQuery()
				->setTable('languages')
				->addField('*')
				->setOffset($uOffset)
				->setLimit($uLimit)
				->get()
				->all();
		}
	}

?>