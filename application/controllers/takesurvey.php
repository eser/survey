<?php

	/**
	 * @ignore
	 */
	class takesurvey extends controller {
		/**
		 * @ignore
		 */
		public function get_new() {
			statics::requireAuthentication(1);
			$this->load('surveyModel');
			$surveys = $this->surveyModel->getAllByOwner(statics::$user['userid']);
			$this->setRef('surveys', $surveys);
			// render the page
			$this->view();
		}

		/**
		 * @ignore
		 */
		public function post_new() {
			statics::requireAuthentication(1);

			$this->load('publishSurveyModel');

			$input = array(
				'surveypublishid' => string::generateUuid(),
				'surveyid' => http::post('surveyid'),
				'revision' => '1',
				'ownerid' => statics::$user['userid'],
				'startdate' => http::post('startdate'),
				'enddate' => http::post('enddate'),
				'password' => http::post('password'),
				'type' => http::post('type'),
				'enabled' => http::post('enabled')
			);
			$insertSurvey = $this->publishSurveyModel->insert($input);

			if($insertSurvey > 0){
				echo "<script>alert('Survey Added Successfuly');</script>";
			}
			else {
				echo "<script>alert('Unexpected Error.');</script>";
			}

			$this->load('surveyModel');
			$surveys = $this->surveyModel->getAllByOwner(statics::$user['userid']);
			$this->setRef('surveys', $surveys);
			$this->view();
		}

		/**
		 * @ignore
		 */
		public function get_index($uSurveyPublishId) {
			statics::requireAuthentication(1);

			/*
			$checkPast = $this->publishSurveyModel->checkPast(statics::$user['userid'],$uSurveyPublishId);
			if(count($checkPast) > 0) {
				//return ( bu anketi doldurdunuz sayfası.
				exit;
			}
			*/

			$this->load('surveyVisitorModel');
			$tExistingSurveyVisitor = $this->surveyVisitorModel->get(session::$id);
			if($tExistingSurveyVisitor !== false) {
				throw new Exception('dolmus o anket');
			}
			
			// gather all survey data from model
			$this->load('publishSurveyModel');
			$survey = $this->publishSurveyModel->get($uSurveyPublishId);
			$this->load('questionModel');
			$questions = $this->questionModel->getBySurveyID($survey['surveyid']);
			$choices = array();
			foreach($questions as $question) {
				$choices[$question['questionid']] = $this->questionModel->getChoicesByQuestionID($question['questionid']);
			}

			// assign the user data to view
			$this->setRef('surveys', $survey);
			$this->setRef('choices', $choices);
			$this->setRef('questions', $questions);

			// render the page
			$this->view();
		}

		/**
		 * @ignore
		 */
		public function post_index($uSurveyPublishId) {
			statics::requireAuthentication(0);
			
			// check auth-only
			
			$this->load('publishSurveyModel');
			$this->load('surveyVisitorModel');
			$this->load('questionModel');

			// gather all survey data from model
			$survey = $this->publishSurveyModel->get($uSurveyPublishId);
			$tExistingSurveyVisitor = $this->surveyVisitorModel->get(session::$id);
			if($tExistingSurveyVisitor !== false) {
				throw new Exception('dolmus o anket');
			}

			$tSurveyVisitor = array(
				'surveyvisitorid' => session::$id,
				'surveypublishid' => $survey['surveypublishid'],
				'userid' => (!is_null(statics::$user) ? statics::$user['userid'] : null),
				'ip' => $_SERVER['REMOTE_ADDR'],
				'useragent' => $_SERVER['HTTP_USER_AGENT'],
				'recorddate' => time::toDb(time())
			);
			$this->surveyVisitorModel->insert($tSurveyVisitor);

			$questions = $this->questionModel->getBySurveyID($survey['surveyid']);

			$answers = array();
			foreach($questions as $question) {
				$answers[$question['questionid']] = http::post($question['questionid']);
				$answersvalues[$question['questionid']] = http::post($question['questionid'] . 'value');

				if($question['type'] == statics::QUESTION_MULTIPLE) {
					$input = array(
						'surveypublishid' => $uSurveyPublishId,
						'questionid' => $question['questionid'],
						'surveyvisitorid' => $tSurveyVisitor['surveyvisitorid'],
						'questionchoiceid' => $answers[$question['questionid']],
						'value'=> $answersvalues[$question['questionid']]
					);
				} else {
					$input = array(
						'surveyid' => $uSurveyPublishId,
						'questionid' => $question['questionid'],
						'surveyvisitorid' => $tSurveyVisitor['surveyvisitorid'],
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
