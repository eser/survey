<?php

	/**
	 * Blackmore Extension: Kibrissiparis Admin Panel, products Section
	 *
	 * @package Kibrissiparis
	 * @subpackage admin_users
	 * @version 1.0.2
	 *
	 * @scabbia-fwversion 1.0
	 * @scabbia-fwdepends string, resources, validation, http, auth, zmodels
	 * @scabbia-phpversion 5.2.0
	 * @scabbia-phpdepends
	 */
	class admin_products {
		/**
		 * @ignore
		 */
		public static function blackmore_registerModules($uParms) {
			$uParms['modules']['products'] = array(
				'title' => 'Products',
				'callback' => 'admin_products::index',
				'submenus' => true,
				'actions' => array(
					
					array(
						'callback' => 'admin_products::productList',
						'menutitle' => 'List',
						'action' => 'productList'
					),
					array(
						'callback' => 'admin_products::add',
						'menutitle' => 'Add',
						'action' => 'add'
					),
					array(
						'callback' => 'admin_products::delete',
						'action' => 'delete'
					),
					array(
						'callback' => 'admin_products::edit',
						'action' => 'edit'
					),
					array(
						'callback' => 'admin_products::images',
						'action' => 'images'
					),
					array(
						'callback' => 'admin_products::addImage',
						'action' => 'addImage'
					),
					array(
						'callback' => 'admin_products::deleteImage',
						'action' => 'deleteImage'
					)
				)
			);
		}

		/**
		 * @ignore
		 */
		public static function index() {
			auth::checkRedirect('admin');
			// views::view('kibrissiparisAdmin/products/index.cshtml');
			// $file = media::open('C:\\inetpub\\wwwroot\\kibristayim\\res\\images\\404page.png');
			// $file->resize('300', '240', 'fit'); // fit, crop, stretch
			// $file->output();
			// $file->save('C:\\inetpub\\wwwroot\\kibristayim\\res\\images\\404resized.png');
			self::productList();
		}

		public static function productList() {
			auth::checkRedirect('admin');
			$viewBag = array();
			$productModel = mvc::load('productModel');
			$dietTypeModel = mvc::load('dietTypeModel');
			$categoryModel = mvc::load('categoryModel');
			$viewBag['diettypes'] = $dietTypeModel->getAll();
			$viewBag['categories'] = $categoryModel->getAll();
			$viewBag['products'] = $productModel->getList();
			views::view('kibrissiparisAdmin/products/list.cshtml', $viewBag);
		}

		public static function add() {
			$viewBag = array();
			auth::checkRedirect('admin');
			
			$dietTypeModel = mvc::load('dietTypeModel');
			$categoryModel = mvc::load('categoryModel');
			
			if(http::$method == 'post') {
				$productModel = mvc::load('productModel');

				$input = array(
					'name' => http::post('name'),
					'description' => http::post('description'),
					'diettypeid' => http::post('diettypeid'),
					'categoryid' => http::post('categoryid')
					 );
				$productID = http::post('productid');
				if($productID != null && contracts::isUuid($productID)->check()) {

					$viewBag['message'] = $productModel->update($productID, $input).' Record Updated';
				}
				else {
					$input['productid'] = string::generateUuid();
					$viewBag['message'] = $productModel->insert($input).' Record Inserted';
				}
			}
			$viewBag['diettypes'] = $dietTypeModel->getAll();
			$viewBag['categories'] = $categoryModel->getAll();
			views::view('kibrissiparisAdmin/products/add.cshtml', $viewBag);
		}

		public static function delete($actionName, $productID) {
			$viewBag = array();
			auth::checkRedirect('admin');
			$productModel = mvc::load('productModel');
			
			if(contracts::isUuid($productID)->check()) {
				$viewBag['message'] = $productModel->delete($productID).' Record Deleted';
			}
			$viewBag['products'] = $productModel->getList();
			views::view('kibrissiparisAdmin/products/list.cshtml', $viewBag);
		}

		public static function edit($actionName, $productID) {
			$viewBag = array();
			auth::checkRedirect('admin');
			$productModel = mvc::load('productModel');
			$dietTypeModel = mvc::load('dietTypeModel');
			$categoryModel = mvc::load('categoryModel');

			$viewBag = array(
				'product' => $productModel->get($productID),
				'diettypes' => $dietTypeModel->getAll(),
				'categories' => $categoryModel->getAll()
				);
				views::view('kibrissiparisAdmin/products/add.cshtml', $viewBag);
		}

		public static function images($actionName, $productID) {
			auth::checkRedirect('admin');
			//todo root 
			$picturesDir = QPATH_BASE.'writable/uploaded/products/pictures/'.$productID;
			$thumbnailsDir = QPATH_BASE.'writable/uploaded/products/thumbnails/'.$productID;
			
			if(!is_dir($picturesDir)) {
				mkdir($picturesDir, 0777);
			}
			if(!is_dir($thumbnailsDir)) {
				mkdir($thumbnailsDir, 0777);
			}

			$productModel = mvc::load('productModel');
			$viewBag = array();
			$viewBag['images'] = statics::getImages($picturesDir);
			$viewBag['product'] = $productModel->get($productID);
			views::view('kibrissiparisAdmin/products/images.cshtml', $viewBag);
		}

		public static function addImage($actionName, $productID) {
			auth::checkRedirect('admin');
			$viewBag = array();
			$productModel = mvc::load('productModel');
			if(http::$method == 'post') {
				$allowedExts = array("jpg", "jpeg", "gif", "png","JPG", "JPEG", "GIF", "PNG");
				$extensions = explode(".", $_FILES["image"]["name"]);
				$extension = end($extensions);
				if (in_array($extension, $allowedExts)) {
					if ($_FILES["image"]["error"] > 0) {
				    	$viewBag['message'] = "Error: " . $_FILES["image"]["error"];
					}
					else {
						$picturesDir = QPATH_BASE.'writable/uploaded/products/pictures/'.$productID.'/';
						$thumbnailsDir = QPATH_BASE.'writable/uploaded/products/thumbnails/'.$productID.'/';
						$fileName = io::sanitize($_FILES["image"]["name"], false, true);
				    	move_uploaded_file($_FILES["image"]["tmp_name"], $picturesDir.$fileName);
				    	//copy($picturesDir.$fileName, $thumbnailsDir.$fileName);
				    	$file = media::open($picturesDir.$fileName);
						$file->resize(statics::IMAGE_W, statics::IMAGE_H, 'fit'); // fit, crop, stretch
						$file->save($thumbnailsDir.$fileName);
				    }
				}
				else {
					$viewBag['message'] = "Invalid file";
				}
			}
			$viewBag['product'] = $productModel->get($productID);
			views::view('kibrissiparisAdmin/products/addImage.cshtml', $viewBag);
		}

		public static function deleteImage($actionName, $productID, $imageName) {
			auth::checkRedirect('admin');
			$viewBag = array();
			if(contracts::isUuid($productID)->check()) {
				$picture = QPATH_BASE.'writable/uploaded/products/pictures/'.$productID.'/'.$imageName;
				$thumbnail = QPATH_BASE.'writable/uploaded/products/thumbnails/'.$productID.'/'.$imageName;
				unlink($picture);
				unlink($thumbnail);
			}
			$productModel = mvc::load('productModel');
			$viewBag['images'] = statics::getImages(QPATH_BASE.'writable/uploaded/products/pictures/'.$productID);
			$viewBag['product'] = $productModel->get($productID);
			views::view('kibrissiparisAdmin/products/images.cshtml', $viewBag);
		}




	}


?>