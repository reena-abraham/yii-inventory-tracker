<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$this->render('index');
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-Type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if ($model->validate() && $model->login()) {
				$user = Users::model()->findByPk(Yii::app()->user->id);
				$roles = $user->roles;
				if (!empty($roles)) {
					$role = $roles[0];
					if ($role->id == 1) {
						$this->redirect(array('site/adminDashboard'));
					} elseif ($role->id == 2) {
						$this->redirect(array('site/userDashboard'));
					} else {
						$this->redirect(Yii::app()->user->returnUrl);
					}
				} else {
					throw new CHttpException(403, "No role assigned to this user.");
				}
				$this->redirect(Yii::app()->user->returnUrl);
			}
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}



	public function actionRegister()
	{
		if (!Yii::app()->user->isGuest) {
			$this->redirect(Yii::app()->homeUrl);
			return;
		}

		$model = new Users('register');

		if (isset($_POST['Users'])) {
			$model->attributes = $_POST['Users'];

			if ($model->validate()) {
				// Set additional attributes before saving
				$model->password = CPasswordHelper::hashPassword($model->password);
				$model->created_at = date('Y-m-d H:i:s');
				if ($model->save()) {
					Yii::app()->db->createCommand()->insert('user_roles', array(
						'user_id' => $model->id,
						'role_id' => 2,
					));

					Yii::app()->user->setFlash('message', 'Registration successful');
					$this->redirect(array('site/login'));
				} else {
					Yii::app()->user->setFlash('error', 'Registration failed');
				}
			} else {
				// Clear password on validation failure
				$model->password = '';
			}
		}

		$this->render('register', array('model' => $model));
	}

	public function actionAdminDashboard()
	{
		// $this->layout = 'admin';
		// Only allow admin
		if (Yii::app()->user->getState('role') != 1) {
			throw new CHttpException(403, 'Unauthorized access');
		}

		$this->render('admin_dashboard');
	}

	public function actionUserDashboard()
	{
		if (Yii::app()->user->getState('role') != 2) {
			throw new CHttpException(403, 'Unauthorized access');
		}
		$userId = Yii::app()->user->id;
		$user = Users::model()->findByPk($userId);
		$this->render('user_dashboard',array('user'=>$user));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
}