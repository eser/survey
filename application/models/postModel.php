<?php

    namespace App\Models;

	use Scabbia\Extensions\Models\Model;

	/**
	 * @ignore
	 */
	class PostModel extends Model {
		/**
		 * @ignore
		 */
		public function insert($input) {
			return $this->db->createQuery()
				->setTable('posts')
				->setFields($input)
				->insert()
				->execute();
		}

		/**
		 * @ignore
		 */
		public function update($postid, $input) {
			return $this->db->createQuery()
				->setTable('posts')
				->setFields($input)
				->addParameter('postid', $postid)
				->setWhere(['postid=:postid'])
				->setLimit(1)
				->update()
				->execute();
		}

		/**
		 * @ignore
		 */
		public function delete($uPostId) {
			return $this->db->createQuery()
				->setTable('posts')
				->addParameter('postid', $uPostId)
				->setWhere('postid=:postid')
				->setLimit(1)
				->delete()
				->execute();
		}

		/**
		 * @ignore
		 */
		public function get($uId) {
			return $this->db->createQuery()
				->setTable('posts')
				->addField('*')
				->setWhere(['postid=:postid'])
				->addParameter('postid', $uId)
				->setLimit(1)
				->get()
				->row();
		}

		/**
		 * @ignore
		 */
		public function getAll() {
			return $this->db->createQuery()
				->setTable('posts')
				->addField('*')
				->setOrderBy('createdate DESC')
				->get()
				->all();
		}

		/*For admin*/
		public function getAllDetailed() {
			return $this->db->createQuery()
				->setTable('posts p')
				->joinTable('users u', 'u.userid=p.ownerid', 'INNER')
				->addField('p.*, u.displayname')
				->get()
				->all();
		}

		/**
		 * @ignore
		 */
		public function getAllPaged($uOffset, $uLimit) {
			return $this->db->createQuery()
				->setTable('posts')
				->addField('*')
				->setOrderBy('createdate DESC')
				->setOffset($uOffset)
				->setLimit($uLimit)
				->get()
				->all();
		}

		/**
		 * @ignore
		 */
		public function count() {
			return $this->db->createQuery()
				->setTable('posts')
				->addField('*')
				->aggregate('COUNT');
		}
	}

?>