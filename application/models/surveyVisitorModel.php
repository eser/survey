<?php

    namespace App\Models;

	use Scabbia\Extensions\Models\Model;

	/**
	 * @ignore
	 */
	class SurveyvisitorModel extends Model {
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
		public function countBySurveyPublish($uSurveyPublishId) {
			return $this->db->createQuery()
				->setTable('surveyvisitors')
				->addField('*')
				->setWhere(['surveypublishid=:surveypublishid'])
				->addParameter('surveypublishid', $uSurveyPublishId)
				->aggregate('COUNT');
		}

		/**
		 * @ignore
		 */
		public function getBySurveyPublish($uSurveyVisitorId, $uSurveyPublishId, $uUserId = null) {
			if(!is_null($uUserId)) {
				return $this->db->createQuery()
					->setTable('surveyvisitors')
					->addField('*')
					->setWhere(['surveypublishid=:surveypublishid', _AND, ['surveyvisitorid=:surveyvisitorid', _OR, 'userid=:userid']])
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
				->setWhere(['surveypublishid=:surveypublishid', _AND, 'surveyvisitorid=:surveyvisitorid'])
				->addParameter('surveyvisitorid', $uSurveyVisitorId)
				->addParameter('surveypublishid', $uSurveyPublishId)
				->setLimit(1)
				->get()
				->row();
		}
	}

?>