<?php

	/**
	 * @ignore
	 */
	class reports extends controller {
		/**
		 * @ignore
		 */
		public function get_index($uSurveyPublishId) {
			statics::requireAuthentication(1);
			$this->load('publishSurveyModel');

			// gather all survey data from model
			$survey = $this->publishSurveyModel->get($uSurveyPublishId);

			$this->load('questionModel');
			$questions = $this->questionModel->getBySurveyID($survey['surveyid'], $survey['revision']);
			$questionids = arrays::column($questions, 'questionid');

			$this->load('answersModel');
			$answers = $this->answersModel->getByPublishID($uSurveyPublishId, $questionids);
			string::vardump($answers);

			// assign the user data to view
			$this->setRef('reports', $categorize);
			$this->setRef('surveys', $survey);
			$this->setRef('questions', $questionsTest);
			$this->setRef('choices', $choices);

			// render the page
			$this->view();
		}

		public function post_index($uSurveyPublishId) {
			statics::requireAuthentication(1);
			
			$this->load('publishSurveyModel');
			$this->load('questionModel');

			// gather all survey data from model
			$survey = $this->publishSurveyModel->get($uSurveyPublishId);
			$questions = $this->questionModel->getBySurveyID($survey['surveyid']);

			$answers = array();
			foreach($questions as $question) {
				$answers[$question['questionid']] = http::post($question['questionid']);
				$answersvalues[$question['questionid']] = http::post($question['questionid'].'value');
				if($question['type'] == statics::QUESTION_MULTIPLE){
					$input = array(
						'surveypublishid' => $uSurveyPublishId,
						'questionid' => $question['questionid'],
						'userid' => statics::$user['userid'],
						'questionchoiceid' => $answers[$question['questionid']],
						'value'=> $answersvalues[$question['questionid']]
					);
				} else {
					$input = array(
						'surveyid' => $uSurveyPublishId,
						'questionid' => $question['questionid'],
						'userid' => statics::$user['userid'],
						'questionchoiceid' => null,
						'value'=> $answers[$question['questionid']]
					);
				}
				$this->questionModel->insertAnswer($input);
			}
			//Anketi Doldurdunuz uyarısı flash filan koyulmalı.
			mvc::redirect('home/index');
			// render the page
			$this->view();
		}

		/**
		 * @ignore
		 */
		public function get_edit($uSurveyPublishsId) {
			statics::requireAuthentication(1);
			$this->load('publishSurveyModel');
			// gather all survey data from model
			$tSurvey = $this->publishSurveyModel->get($uSurveyPublishsId);

			// assign the user data to view
			$this->set('publishSurveys', $tSurvey);
			// render the page
			$this->view();
		}

		/**
		 * @ignore
		 */
		public function post_edit($uSurveyPublishId) {
			statics::requireAuthentication(1);
			$this->load('publishSurveyModel');
			$update = array(
				'revision' => '1',
				'ownerid' => statics::$user['userid'],
				'startdate' => http::post('startdate'),
				'enddate' => http::post('enddate'),
				'password' => http::post('password'),
				'type' => http::post('type'),
				'enabled' => http::post('enabled')
			);
			// gather all survey data from model
			$tEditPublish = $this->publishSurveyModel->update($uSurveyPublishId, $update);
			if($tEditPublish > 0){
				echo "<script> alert('Record updated successfuly.');</script>";
			}
			else{
				echo "<script> alert('Unexpected Error. Try Again Later.');</script>";
			}
				$tSurvey = $this->publishSurveyModel->get($uSurveyPublishId);
			// assign the user data to view

			$this->set('publishSurveys', $tSurvey);
			// render the page
			$this->view();
		}

		/**
		 * @ignore
		 */
		public function get_report($uSurveyId) {
			statics::requireAuthentication(1);

			$this->load('surveyModel');

			// gather all survey data from model
			$tSurvey = $this->surveyModel->get($uSurveyId);
			
			// assign the user data to view
			$this->setRef('survey', $tSurvey);

			// render the page
			$this->view();
		}
	}

?>
