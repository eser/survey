<?php

    namespace App\Models;

	use Scabbia\Extensions\Models\Model;

	/**
	 * @ignore
	 */
	class ThemeModel extends Model {
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
				->get()
				->all();
		}
		
		public function insert($input) {
			return $this->db->createQuery()
				->setTable('themes')
				->setFields($input)
				->insert()
				->execute();
		}

		public function update($themeID, $input) {
			return $this->db->createQuery()
				->setTable('themes')
				->setFields($input)
				->addParameter('themeid', $themeID)
				->setWhere(['themeid=:themeid'])
				->setLimit(1)
				->update()
				->execute();
		}

		public function delete($themeID) {
			return $this->db->createQuery()
				->setTable('themes')
				->addParameter('themeid', $themeID)
				->setWhere('themeid=:themeid')
				->setLimit(1)
				->delete()
				->execute();
		}

	}

?>