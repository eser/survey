<?php

	/**
	 * @ignore
	 */
	class userModel extends model {
		/**
		 * @ignore
		 */
		public function getAll() {
			return $this->db->createQuery()
				->setTable('users')
				->addField('*')
				->get()
				->all();
		}

		/**
		 * @ignore
		 */
		public function get($uId) {
			return $this->db->createQuery()
				->setTable('users')
				->addField('*')
				->setWhere(['userid=:userid'])
				->addParameter('userid', $uId)
				->setLimit(1)
				->get()
				->row();
		}

		/**
		 * @ignore
		 */
		public function getByEmail($uEmail) {
			return $this->db->createQuery()
				->setTable('users')
				->addField('*')
				->setWhere(['email=:email'])
				->addParameter('email', $uEmail)
				->setLimit(1)
				->get()
				->row();
		}

		/**
		 * @ignore
		 */
		public function &getAllByIDs($uIds) {
			$tResult = array();

			$tQuery = $this->db->createQuery()
				->setTable('users')
				->addField('*')
				->setWhere(['userid', _IN, $uIds])
				->get();

			foreach($tQuery as $tRow) {
				$tResult[$tRow['userid']] = $tRow;
			}

			$tQuery->close();

			return $tResult;
		}

		/**
		 * @ignore
		 */
		public function getByEmailOrFacebookId($uEmail, $uFacebookId) {
			return $this->db->createQuery()
				->setTable('users')
				->addField('*')
				->setWhere(['email=:email', _OR, 'facebookid=:facebookid'])
				->addParameter('email', $uEmail)
				->addParameter('facebookid', $uFacebookId)
				->setLimit(1)
				->get()
				->row();
		}

		/**
		 * @ignore
		 */
		public function insert($uFields) {
			return $this->db->createQuery()
				->setTable('users')
				->setFields($uFields)
				->insert()
				->execute();
		}

		/**
		 * @ignore
		 */
		public function update($uUserId, $uFields) {
			return $this->db->createQuery()
				->setTable('users')
				->setFields($uFields)
				->setWhere(['userid=:userid'])
				->addParameter('userid', $uUserId)
				->setLimit(1)
				->update()
				->execute();
		}
	}

?>