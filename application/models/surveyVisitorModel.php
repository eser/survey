<?php

	/**
	 * @ignore
	 */
	class surveyvisitorModel extends model {
		/**
		 * @ignore
		 */
		public function insert($input) {
			return $this->db->createQuery()
				->setTable('surveyvisitors')
				->setFields($input)
				->insert()
				->execute();
		}

		/**
		 * @ignore
		 */
		public function get($uId) {
			return $this->db->createQuery()
				->setTable('surveyvisitors')
				->addField('*')
				->setWhere(['surveyvisitorid=:surveyvisitorid'])
				->addParameter('surveyvisitorid', $uId)
				->setLimit(1)
				->get()
				->row();
		}

		/**
		 * @ignore
		 */
		public function getBySurveyPublish($uSurveyVisitorId, $uSurveyPublishId, $uUserId = null) {
			if(!is_null($uUserId)) {
				return $this->db->createQuery()
					->setTable('surveyvisitors')
					->addField('*')
					->setWhere(['surveypublishid=:surveypublishid', _and, ['surveyvisitorid=:surveyvisitorid', _or, 'userid=:userid']])
					->addParameter('surveyvisitorid', $uSurveyVisitorId)
					->addParameter('surveypublishid', $uSurveyPublishId)
					->addParameter('userid', $uUserId)
					->setLimit(1)
					->get()
					->row();
			}

			return $this->db->createQuery()
				->setTable('surveyvisitors')
				->addField('*')
				->setWhere(['surveypublishid=:surveypublishid', _and, 'surveyvisitorid=:surveyvisitorid'])
				->addParameter('surveyvisitorid', $uSurveyVisitorId)
				->addParameter('surveypublishid', $uSurveyPublishId)
				->setLimit(1)
				->get()
				->row();
		}
	}

?>