<?php

	/**
	 * Blackmore Extension: Kibrissiparis Admin Panel, firms Section
	 *
	 * @package Kibrissiparis
	 * @subpackage admin_firms
	 * @version 1.0.2
	 *
	 * @scabbia-fwversion 1.0
	 * @scabbia-fwdepends string, resources, validation, http, auth, zmodels
	 * @scabbia-phpversion 5.2.0
	 * @scabbia-phpdepends
	 */
	class admin_firms {
		/**
		 * @ignore
		 */
		public static function blackmore_registerModules($uParms) {
			$uParms['modules']['firms'] = array(
				'title' => 'Firms',
				'callback' => 'admin_firms::index',
				'submenus' => true,
				'actions' => array(
					
					array(
						'callback' => 'admin_firms::firmList',
						'menutitle' => 'List',
						'action' => 'firmList'
					),
					array(
						'callback' => 'admin_firms::add',
						'menutitle' => 'Add',
						'action' => 'add'
					),
					array(
						'callback' => 'admin_firms::delete',
						'action' => 'delete'
					),
					array(
						'callback' => 'admin_firms::edit',
						'action' => 'edit'
					),
					array(
						'callback' => 'admin_firms::regions',
						'action' => 'regions'
					),
					array(
						'callback' => 'admin_firms::addRegion',
						'action' => 'addRegion'
					),
					array(
						'callback' => 'admin_firms::deleteRegion',
						'action' => 'deleteRegion'
					),
					array(
						'callback' => 'admin_firms::products',
						'action' => 'products'
					),
					array(
						'callback' => 'admin_firms::addProduct',
						'action' => 'addProduct'
					),
					array(
						'callback' => 'admin_firms::deleteProduct',
						'action' => 'deleteProduct'
					),
					array(
						'callback' => 'admin_firms::managers',
						'action' => 'managers'
					),
					array(
						'callback' => 'admin_firms::addManager',
						'action' => 'addManager'
					),
					array(
						'callback' => 'admin_firms::deleteManager',
						'action' => 'deleteManager'
					),
					array(
						'callback' => 'admin_firms::images',
						'action' => 'images'
					),
					array(
						'callback' => 'admin_firms::addImage',
						'action' => 'addImage'
					),
					array(
						'callback' => 'admin_firms::deleteImage',
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
			self::firmList();
		}

		public static function firmList() {
			auth::checkRedirect('admin');
			$firmModel = mvc::load('firmModel');
			$viewBag = array('firms' => $firmModel->getAll());
			views::view('kibrissiparisAdmin/firms/list.cshtml', $viewBag);
		}

		public static function add() {
			$viewBag = array();
			auth::checkRedirect('admin');
			$firmModel = mvc::load('firmModel');
			if(http::$method == 'post') {
				/*$input = http::postArray(array('name', 'title', 'taxno', 'taxoffice'))
				$input['firmid'] = string::generateUuid();*/
				$input = array(
					'name' => http::post('name'),
					'title' => http::post('title'),
					'taxno' => http::post('taxno'),
					'taxoffice' => http::post('taxoffice'),
					'address' => http::post('address'),
					'phone' => http::post('phone'),
					'phone2' => http::post('phone2'),
					'gsm' => http::post('gsm'),
					'gsm2' => http::post('gsm2'),
					'email' => http::post('email'),
					'personincharge' => http::post('personincharge'),
					'fax' => http::post('fax'),
					'logo' => http::post('logo'),
					'description' => http::post('description'),
					'averageservicetime' => http::post('averageservicetime'),
					'minprice' => http::post('minprice'),
					'regionid' => http::post('regionid')
					 );
				$firmID = http::post('firmid');
				if($firmID != null && contracts::isUuid($firmID)->check()) {
					
					$viewBag['message'] = $firmModel->update($firmID, $input).' Record Updated';
				}
				else {
					$input['firmid'] = string::generateUuid();
					$viewBag['message'] = $firmModel->insert($input).' Record Inserted';
				}
			}

			$regionModel = mvc::load('regionModel');
			$viewBag['regions'] = $regionModel->getAll();
			views::view('kibrissiparisAdmin/firms/add.cshtml', $viewBag);
		}

		public static function delete($acitonName, $firmID) {
			$viewBag = array();
			auth::checkRedirect('admin');
			$firmModel = mvc::load('firmModel');
			$result = $firmModel->delete($firmID);
			$viewBag['result'] = $result;
			$viewBag['message'] = 'Record Deleted';
			$viewBag = array('firms' => $firmModel->getAll());
			views::view('kibrissiparisAdmin/firms/list.cshtml', $viewBag);
		}

		public static function edit($actionName, $firmID) {
			$viewBag = array();
			auth::checkRedirect('admin');
			$firmModel = mvc::load('firmModel');
			$regionModel = mvc::load('regionModel');
			$viewBag = array(
				'firm' => $firmModel->get($firmID),
				'regions' => $regionModel->getAll()
				);
			views::view('kibrissiparisAdmin/firms/add.cshtml', $viewBag);
			
			
		}
		/*firm region list*/
		public static function regions($acitonName, $firmID) {
			$viewBag = array();
			auth::checkRedirect('admin');
			if(contracts::isUuid($firmID)->check()) {
				$firmModel = mvc::load('firmModel');
				$viewBag['regions'] = $firmModel->getRegionList($firmID);	
			}
			$viewBag['firmID'] = $firmID;
			views::view('kibrissiparisAdmin/firms/regions.cshtml', $viewBag);
		}

		public static function addRegion($acitonName, $firmID) {
			$viewBag = array();
			auth::checkRedirect('admin');
			$firmModel = mvc::load('firmModel');
			if(http::$method == 'post') {
				$input = array('firmid' => http::post('firmid'),
					'regionid' => http::post('regionid'),
					'minprice' => http::post('minprice'),
					'averagepreparationtime' => http::post('averagepreparationtime')
					 );
				if(contracts::isUuid($input['firmid'])->check() && contracts::isUuid($input['regionid'])->check()) {
					
					$viewBag['message'] = $firmModel->addRegion($input).' Record Saved';
				}
				
			}

			$regionModel = mvc::load('regionModel');
			$viewBag['regions'] = $regionModel->getAll();
			$viewBag['firmID'] = $firmID;
			views::view('kibrissiparisAdmin/firms/addRegion.cshtml', $viewBag);	
		}

		public static function deleteRegion($actionName, $firmID, $regionID) {
			$viewBag = array();
			auth::checkRedirect('admin');
			
			if(contracts::isUuid($firmID)->check() && contracts::isUuid($regionID)->check()) {
				$firmModel = mvc::load('firmModel');
				$viewBag['message'] = $firmModel->deleteRegion($firmID, $regionID).' Record deleted';

			}
			$viewBag['firmID'] = $firmID;
			$viewBag['regions'] = $firmModel->getRegionList($firmID);
			views::view('kibrissiparisAdmin/firms/regions.cshtml', $viewBag);
			
		}

		public static function products($actionName, $firmID) {
			$viewBag = array();
			auth::checkRedirect('admin');
			if(contracts::isUuid($firmID)->check()) {
				$firmModel = mvc::load('firmModel');
				$viewBag['products'] = $firmModel->getProductList($firmID);	
				$viewBag['firmID'] = $firmID;
			}
			
			views::view('kibrissiparisAdmin/firms/products.cshtml', $viewBag);
		}

		public static function addProduct($actionName, $firmID) {
			$viewBag = array();
			auth::checkRedirect('admin');
			$firmModel = mvc::load('firmModel');
			$productModel = mvc::load('productModel');
			if(http::$method == 'post') {
				$input = array(
					'firmid' => http::post('firmid'),
					'productid' => http::post('productid'),
					'price' => http::post('price'),
					'quantity' => http::post('quantity'),
					'discount' => http::post('discount')
					 );	
				$viewBag['message'] = $firmModel->addProduct($input).' Record Added';
			}
			$viewBag['firmID'] = $firmID;
			$viewBag['firm'] = $firmModel->get($firmID);
			$viewBag['products'] = $productModel->getAll();

			views::view('kibrissiparisAdmin/firms/addProduct.cshtml', $viewBag);
		}

		public static function deleteProduct($actionName, $firmID, $productID) {
			$viewBag = array();
			auth::checkRedirect('admin');
			
			if(contracts::isUuid($firmID)->check() && contracts::isUuid($productID)->check()) {
				$firmModel = mvc::load('firmModel');
				$viewBag['message'] = $firmModel->deleteProduct($firmID, $productID).' Record deleted';

			}
			$viewBag['firmID'] = $firmID;
			$viewBag['firm'] = $firmModel->get($firmID);
			$viewBag['products'] = $firmModel->getProductList($firmID);	
			views::view('kibrissiparisAdmin/firms/products.cshtml', $viewBag);
			
		}

		public static function managers($actionName, $firmID) {
			$viewBag = array();
			auth::checkRedirect('admin');
			$firmModel = mvc::load('firmModel');
			$viewBag['managers'] = $firmModel->getManagers($firmID);	
			$viewBag['firm'] = $firmModel->get($firmID);	
			views::view('kibrissiparisAdmin/firms/managers.cshtml', $viewBag);
		}

		public static function addManager($actionName, $firmID) {
			$viewBag = array();
			auth::checkRedirect('admin');
			$firmModel = mvc::load('firmModel');
			$userModel = mvc::load('userModel');
			if(http::$method == 'post') {
				$input = array(
					'firmid' => $firmID,
					'userid' => http::post('userid'),
					'caneditprices' => http::post('caneditprices'),
					'caneditquantity' => http::post('caneditquantity'),
					'caneditavailability' => http::post('caneditavailability'),
					'caneditorders' => http::post('caneditorders'),
					'caneditdiscount' => http::post('caneditdiscount')

					 );	
				$viewBag['message'] = $firmModel->addManager($input).' Record Added';
			}
			$viewBag['firm'] = $firmModel->get($firmID);
			$viewBag['users'] = $userModel->getAll();

			views::view('kibrissiparisAdmin/firms/addManager.cshtml', $viewBag);
		}

		public static function deleteManager($actionName, $firmID, $userID) {
			$viewBag = array();
			auth::checkRedirect('admin');
			
			if(contracts::isUuid($firmID)->check() && contracts::isUuid($userID)->check()) {
				$firmModel = mvc::load('firmModel');
				$viewBag['message'] = $firmModel->deleteManager($firmID, $userID).' Record deleted';

			}
			
			$viewBag['firm'] = $firmModel->get($firmID);
			$viewBag['managers'] = $firmModel->getManagers($firmID);	
			views::view('kibrissiparisAdmin/firms/managers.cshtml', $viewBag);
			
		}

		public static function images($actionName, $firmID) {
			auth::checkRedirect('admin');
			//todo root 
			$picturesDir = QPATH_BASE.'writable/uploaded/firms/pictures/'.$firmID;
			$thumbnailsDir = QPATH_BASE.'writable/uploaded/firms/thumbnails/'.$firmID;
			
			if(!is_dir($picturesDir)) {
				mkdir($picturesDir, 0777);
			}
			if(!is_dir($thumbnailsDir)) {
				mkdir($thumbnailsDir, 0777);
			}

			$firmModel = mvc::load('firmModel');
			$viewBag = array();
			$viewBag['images'] = statics::getImages($picturesDir);
			$viewBag['firm'] = $firmModel->get($firmID);
			views::view('kibrissiparisAdmin/firms/images.cshtml', $viewBag);
		}

		public static function addImage($actionName, $productID) {
			auth::checkRedirect('admin');
			$viewBag = array();
			$firmModel = mvc::load('firmModel');
			if(http::$method == 'post') {
				$allowedExts = array("jpg", "jpeg", "gif", "png","JPG", "JPEG", "GIF", "PNG");
				$extensions = explode(".", $_FILES["image"]["name"]);
				$extension = end($extensions);
				if (in_array($extension, $allowedExts)) {
					if ($_FILES["image"]["error"] > 0) {
				    	$viewBag['message'] = "Error: " . $_FILES["image"]["error"];
					}
					else {
						$picturesDir = QPATH_BASE.'writable/uploaded/firms/pictures/'.$productID.'/';
						$thumbnailsDir = QPATH_BASE.'writable/uploaded/firms/thumbnails/'.$productID.'/';
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
			$viewBag['firm'] = $firmModel->get($productID);
			views::view('kibrissiparisAdmin/firms/addImage.cshtml', $viewBag);
		}

		public static function deleteImage($actionName, $firmID, $imageName) {
			auth::checkRedirect('admin');
			$viewBag = array();
			if(contracts::isUuid($firmID)->check()) {
				$picture = QPATH_BASE.'writable/uploaded/firms/pictures/'.$firmID.'/'.$imageName;
				$thumbnail = QPATH_BASE.'writable/uploaded/firms/thumbnails/'.$firmID.'/'.$imageName;
				unlink($picture);
				unlink($thumbnail);
			}
			$firmModel = mvc::load('firmModel');
			$viewBag['images'] = statics::getImages(QPATH_BASE.'writable/uploaded/firms/pictures/'.$firmID);
			$viewBag['firm'] = $firmModel->get($firmID);
			views::view('kibrissiparisAdmin/firms/images.cshtml', $viewBag);
		}


	}


?>