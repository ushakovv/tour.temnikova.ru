<?php
namespace admin\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user = false;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     */
    public function validatePassword()
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError('password', 'Incorrect username or password.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
	public function login()
	{
		$user = $this->getUser();

		if ($user && $user->count_login_errors >= 10) {
			$this->addError('username', "Sorry, your account is blocked");
			return false;
		} else {
			if ($this->validate()) {
				return Yii::$app->user->login($user, 3600 *3 );
			} else {
				if ($user) {
					$user->count_login_errors = $user->count_login_errors + 1;
					$user->save();
				}
				return false;
			}
		}
	}

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = AdminUser::findByUsername($this->username);
        }

        return $this->_user;
    }

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'username' => 'User name',
			'password' => 'Password',
			'rememberMe' => 'Remember me'
		];
	}
}
