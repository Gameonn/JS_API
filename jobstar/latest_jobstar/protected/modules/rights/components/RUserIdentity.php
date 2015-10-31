<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class RUserIdentity extends CUserIdentity
{
    public $userType = 'Back';
    /**
     * Authenticates a user.
     * The example implementation makes sure if the username and password
     * are both 'demo'.
     * In practical applications, this should be changed to authenticate
     * against some persistent user identity storage (e.g. database).
     * @return boolean whether authentication succeeds.
     */
    public function authenticate()
    {
            $user = User::model()->findByAttributes(array('email' => $this->username,'superuser'=>1));
            //echo "<pre>";
            //print_r($user);
            //die('innn');
                if ($user === null)
                    $this->errorCode = self::ERROR_USERNAME_INVALID;
                elseif (md5($this->password) !== $user->password)
                    $this->errorCode = self::ERROR_PASSWORD_INVALID;
                else
                {
                    //$this->username = $user->username;
                    $this->setState('__name', $user->first_name);
                    $this->username = $user->first_name;
                    $this->setState('__name', $user->first_name);
                    $this->setState('__id', $user->id);
                    $this->errorCode = self::ERROR_NONE;
                    
                }
            return!$this->errorCode;
         



}

}
