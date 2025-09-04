<?php

class ProductController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout = '//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array(
				'allow',  // allow all users to perform 'index' and 'view' actions
				'actions' => array('index', 'view'),
				'users' => array('*'),
			),
			array(
				'allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions' => array('create', 'update'),
				'users' => array('@'),
			),
			array(
				'allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions' => array('admin', 'delete', 'bulkUpload'),
				'expression' => 'Yii::app()->user->getState("role") == 1',
			),
			array(
				'allow',  // allow only users with role_id = 2 to access admin
				'actions' => array('list'),
				'users' => array('@'),
				'expression' => 'Yii::app()->user->getState("role") ==2',
			),
			array(
				'deny',  // deny all users
				'users' => array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view', array(
			'model' => $this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model = new Product;
		$categories = CHtml::listData(Category::model()->findAll(), 'id', 'name');
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if (isset($_POST['Product'])) {
			$model->attributes = $_POST['Product'];
			if ($model->save())
				$this->redirect(array('view', 'id' => $model->id));
		}

		$this->render('create', array(
			'model' => $model,
			'categories' => $categories
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model = $this->loadModel($id);
		$categories = CHtml::listData(Category::model()->findAll(), 'id', 'name');

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if (isset($_POST['Product'])) {
			$model->attributes = $_POST['Product'];
			if ($model->save())
				$this->redirect(array('view', 'id' => $model->id));
		}

		$this->render('update', array(
			'model' => $model,
			'categories' => $categories
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if (!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider = new CActiveDataProvider('Product');
		$this->render('index', array(
			'dataProvider' => $dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model = new Product('search');
		$model->unsetAttributes();  // clear any default values
		if (isset($_GET['Product'])) {
			$model->attributes = $_GET['Product'];
		}

		$this->render('admin', array(
			'model' => $model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Product the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model = Product::model()->findByPk($id);
		if ($model === null)
			throw new CHttpException(404, 'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Product $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if (isset($_POST['ajax']) && $_POST['ajax'] === 'product-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}


	//user side
	public function actionList()
	{
		if (!Yii::app()->user->isGuest) {
			$userId = Yii::app()->user->getId();
			$products = Product::model()->findAll();
			$this->render('product_list', array('products' => $products));
		} else {
			$this->redirect(array('site/login'));
		}
	}
	public function actionBulkUpload()
	{
		$model = new Product('csvUpload');

		if (isset($_FILES['Product']) && $_FILES['Product']['error']['csvFile'] === UPLOAD_ERR_OK) {

			$tmpName = $_FILES['Product']['tmp_name']['csvFile'];

			$handle = fopen($tmpName, 'r');
			$first = true;

			while (($row = fgetcsv($handle)) !== false) {
				if ($first) {
					$first = false;
					continue;
				}

				$id = (int) $row[0];
				$stock = (int) $row[1];

				$product = Product::model()->findByPk($id);
				if ($product) {
					$product->quantity += $stock;
					$product->save(false);
				}
			}

			fclose($handle);

			Yii::app()->user->setFlash('success', 'Stock updated.');
			$this->redirect(array('product/admin'));
		}

		$this->render('admin', array(
			'model' => $model,
		));
	}
// 	public function actionBulkUpload()
// {
//     $model = new Product();

//     // Check if the file is uploaded
//     if (isset($_FILES['Product']) && $_FILES['Product']['error']['csvFile'] === UPLOAD_ERR_OK) {
        
//         // Get the uploaded file
//         $model->csvFile = CUploadedFile::getInstance($model, 'csvFile');

//         // Validate the file upload
//         if ($model->validate()) {
//             // File is valid, proceed with processing the CSV

//             $tmpName = $_FILES['Product']['tmp_name']['csvFile'];
//             $handle = fopen($tmpName, 'r');
//             $first = true;

//             // Process each row in the CSV file
//             while (($row = fgetcsv($handle)) !== false) {
//                 if ($first) {
//                     $first = false;
//                     continue; // Skip the first row (header)
//                 }

//                 // Process the product data from the CSV
//                 $id = (int) $row[0];
//                 $stock = (int) $row[1];

//                 // Update the product based on the CSV data
//                 $product = Product::model()->findByPk($id);
//                 if ($product) {
//                     $product->quantity += $stock;
//                     $product->save(false);
//                 }
//             }

//             fclose($handle);

//             // Success message after processing the CSV
//             Yii::app()->user->setFlash('success', 'Stock updated successfully.');
//             $this->redirect(array('product/admin'));
//         } else {
//             // If validation fails, show an error message
//             Yii::app()->user->setFlash('error', 'Please upload a valid CSV file.');
//         }
//     }

//     // Render the form for uploading the CSV
//     $this->render('admin', array('model' => $model));
// }



}
