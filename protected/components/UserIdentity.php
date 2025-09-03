<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	private $_id;
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	// public function authenticate()
	// {
	// 	$users=array(
	// 		// username => password
	// 		'demo'=>'demo',
	// 		'admin'=>'admin',
	// 	);
	// 	if(!isset($users[$this->username]))
	// 		$this->errorCode=self::ERROR_USERNAME_INVALID;
	// 	elseif($users[$this->username]!==$this->password)
	// 		$this->errorCode=self::ERROR_PASSWORD_INVALID;
	// 	else
	// 		$this->errorCode=self::ERROR_NONE;
	// 	return !$this->errorCode;
	// }
	public function authenticate()
	{
		$user = Users::model()->with('roles')->findByAttributes(array('email' => $this->username));
		if ($user === null) {
			$this->errorCode = self::ERROR_USERNAME_INVALID;
		} elseif (!CPasswordHelper::verifyPassword($this->password, $user->password)) {
			$this->errorCode = self::ERROR_PASSWORD_INVALID;
		} else {
			$this->_id = $user->id;
			Yii::app()->user->setState('role', $user->roles[0]->id); // Make sure this is set correctly
			$this->errorCode = self::ERROR_NONE;
		}
		return !$this->errorCode;  // Should return true if no error
	}

	public function getId()
	{
		return $this->_id;
	}
}