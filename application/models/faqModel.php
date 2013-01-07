<?php

	use Scabbia\model;

	/**
	 * @ignore
	 */
	class faqModel extends model {
		/**
		 * @ignore
		 */
		public function getAll() {
			return $this->db->createQuery()
				->setTable('faqcontents fcon')
				->joinTable('faqcategories fcat', 'fcat.faqcategoryid=fcon.faqcategoryid', 'INNER')
				->addField('fcon.*, fcat.*')
				->setOrderBy('fcon.faqcategoryid ASC')
				->get()
				->all();
		}
	}

?>