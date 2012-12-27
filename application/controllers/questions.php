<?php

	/**
	 * @ignore
	 */
	class questions extends controller {
		const PAGE_SIZE = statics::DEFAULT_PAGE_SIZE;

		/**
		 * @ignore
		 */
		public function get_index($uPage = '1') {
			statics::requireAuthentication(1);

			$tPage = intval($uPage);
			if($tPage < 1) {
				$tPage = 1;
			}

			$this->load('questionModel');

			// gather all question data from model
			$tOffset = ($tPage - 1) * self::PAGE_SIZE;
			$tQuestions = $this->questionModel->getAllPagedByOwner(statics::$user['userid'], $tOffset, self::PAGE_SIZE);

			// assign the user data to view
			$this->set('pagerTotal', $this->questionModel->countByOwner(statics::$user['userid']));
			$this->setRef('pagerCurrent', $tPage);
			$this->setRef('questions', $tQuestions);

			// render the page
			$this->view();
		}

		/**
		 * @ignore
		 */
		public function get_new() {
			statics::requireAuthentication(1);

			$this->view();
		}

		/**
		 * @ignore
		 */
		public function post_new() {
			statics::requireAuthentication(1);

			$tInput = array(
				'questionid' => string::generateUuid(),
				'ownerid' => statics::$user['userid'],
				'content' => http::post('question'),
				'type' => http::post('type'),
				'typefilter' => http::post('typefilter')
			);

			$this->load('questionModel');
			$this->questionModel->insert($tInput);

			$tOptions = http::post('options');
			$tOptionTypes = http::post('optiontypes');

			if($tInput['type'] == statics::QUESTION_MULTIPLE) {
				foreach($tOptions as $tKey => &$tOption){
					$tOptionInput = array(
						'questionchoiceid' => string::generateUuid(),
						'questionid' => $tInput['questionid'],
						'content' => $tOption,
						'type' => $tOptionTypes[$tKey]
					);

					$this->questionModel->insertChoice($tOptionInput);
				}
			}

			mvc::redirect('questions/edit/' . $tInput['questionid']);
		}

		/**
		 * @ignore
		 */
		public function get_edit($uQuestionId) {
			statics::requireAuthentication(1);

			$this->load('questionModel');

			// gather all question data from model
			$tQuestion = $this->questionModel->get($uQuestionId);

			// gather all question choices from model
			$tQuestionChoices = $this->questionModel->getAllChoices($uQuestionId);

			// assign the user data to view
			$this->setRef('question', $tQuestion);
			$this->setRef('questionchoices', $tQuestionChoices);

			// render the page
			$this->view();
		}

		/**
		 * @ignore
		 */
		public function post_edit($uQuestionId) {
			statics::requireAuthentication(1);

			$tInput = array(
				'ownerid' => statics::$user['userid'],
				'content' => http::post('question'),
				'type' => http::post('type'),
				'typefilter' => http::post('typefilter')
			);

			$this->load('questionModel');
			$this->questionModel->update($uQuestionId, $tInput);

			$tOptions = http::post('options');
			$tOptionTypes = http::post('optiontypes');

			$this->questionModel->truncateChoices($uQuestionId);
			if($tInput['type'] == statics::QUESTION_MULTIPLE) {
				foreach($tOptions as $tKey => &$tOption){
					$tQuestionChoiceId = ((!is_integer($tKey)) ? $tKey : string::generateUuid());

					$tOptionInput = array(
						'questionchoiceid' => $tQuestionChoiceId,
						'questionid' => $uQuestionId,
						'content' => $tOption,
						'type' => $tOptionTypes[$tKey]
					);
					string::vardump($tOptionTypes);

					$this->questionModel->insertChoice($tOptionInput);
				}
			}

			mvc::redirect('questions/edit/' . $uQuestionId);
		}

		/**
		 * @ignore
		 */
		public function get_report($uQuestionId) {
			statics::requireAuthentication(1);

			$this->load('questionModel');

			// gather all question data from model
			$tQuestion = $this->questionModel->get($uQuestionId);
			
			// assign the user data to view
			$this->setRef('question', $tQuestion);

			// render the page
			$this->view();
		}
	}

?>
