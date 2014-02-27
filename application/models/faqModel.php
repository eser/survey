<?php

    namespace App\Models;

	use Scabbia\Extensions\Models\Model;

	/**
	 * @ignore
	 */
	class FaqModel extends Model {
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