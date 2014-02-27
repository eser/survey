<?php

    namespace App\Models;

	use Scabbia\Extensions\Models\Model;

	/**
	 * @ignore
	 */
	class LanguageModel extends Model {
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

		public function insert($input) {
			return $this->db->createQuery()
				->setTable('languages')
				->setFields($input)
				->insert()
				->execute();
		}

		public function update($languageID, $input) {
			return $this->db->createQuery()
				->setTable('languages')
				->setFields($input)
				->addParameter('languageid', $languageID)
				->setWhere(['languageid=:languageid'])
				->setLimit(1)
				->update()
				->execute();
		}

		public function delete($languageID) {
			return $this->db->createQuery()
				->setTable('languages')
				->addParameter('languageid', $languageID)
				->setWhere('languageid=:languageid')
				->setLimit(1)
				->delete()
				->execute();
		}
	}

?>