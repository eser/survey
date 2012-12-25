<?php

	/**
	 * @ignore
	 */
	class questions extends controller {
		/**
		 * @ignore
		 */
		public function get_index() {
			statics::requireAuthentication(1);

			$this->load('questionModel');

			// gather all question data from model
			$tQuestions = $this->questionModel->getAllByOwner(statics::$user['userid']);

			// assign the user data to view
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

			$input = array(
				'questionid' => string::generateUuid(),
				'ownerid' => statics::$user['userid'],
				'content' => http::post('question'),
				'type' => http::post('type'),
				'typefilter' => http::post('typefilter')
			);

			$this->load('questionModel');
			$this->questionModel->insert($input);

			$options = http::post('options');

			if($input['type'] == '1') {
				foreach($options as &$option){
					$optionInput = array(
						'questionchoiceid' => string::generateUuid(),
						'questionid' => $input['questionid'],
						'content' => $option
					);

					$this->optionModel->insertChoice($optionInput);
				}
			}

			mvc::redirect('questions/edit/' . $input['questionid']);
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

			$input = array(
				'ownerid' => statics::$user['userid'],
				'content' => http::post('question'),
				'type' => http::post('type'),
				'typefilter' => http::post('typefilter')
			);

			$this->load('questionModel');
			$this->questionModel->update($uQuestionId, $input);

//			$this->load('optionModel');
//			$options = http::post('options');

			if($input['type'] == '1') {
				/*
				foreach($options as &$option){
					$optionInput = array(
						'questionchoiceid' => string::generateUuid(),
						'questionid' => $input['questionid'],
						'content' => $option
					);

					$this->optionModel->insert($optionInput);
				}
				*/
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
