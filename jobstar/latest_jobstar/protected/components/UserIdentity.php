<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
        public $superuser;

    /**
     * Authenticates a user.
     * The example implementation makes sure if the username and password
     * are both 'demo'.
     * In practical applications, this should be changed to authenticate
     * against some persistent user identity storage (e.g. database).
     * @return boolean whether authentication succeeds.
     */

    //Must need to add
    public function __construct($username, $password, $superuser)
    {
        $this->username = $username;
        $this->password = $password;
        $this->superuser = $superuser;
    }
    public function authenticate()
    {
        //echo $this->superuser;
        //die('jkkkk');
        if($this->superuser=='0') // This is front login
        {
            $user = User::model()->findByAttributes(array('email' => $this->username));
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
         
         if($this->superuser=='1')// This is admin login
        {
            // check if login details exists in database
            $record=User::model()->findByAttributes(array('email'=>$this->username,'superuser'=>1));  // here I use Email as user name which comes from database
            if($record===null)
            { 
                $this->errorCode=self::ERROR_USERNAME_INVALID;
            }
            elseif (md5($this->password) !== $record->password)
            { 
                $this->errorCode=self::ERROR_PASSWORD_INVALID;
            }
            else
            {  
               // die('sss');
                $this->setState('isAdmin',1);
                $this->setState('userId',$record->id);
                $this->setState('name', $record->first_name);
                $this->username = $record->first_name;
                $this->setState('__name', $record->first_name);
                $this->setState('__id', $record->id);
                $this->errorCode=self::ERROR_NONE;
            }
            return !$this->errorCode;
        }
         



}

}
