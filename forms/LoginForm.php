<?php

namespace app\forms;

use app\models\User;
use yii\base\Model;

class LoginForm extends Model
{
    public $email;
    public $password;
    public $rememberMe = false;

    /** @var \app\models\User */
    protected $user;
    
    public function attributeLabels()
    {
        return [
            'email'      => 'Email',
            'password'   => 'Пароль',
            'rememberMe' => 'Помнить меня'
        ];
    }
    
    public function beforeValidate()
    {
        if (parent::beforeValidate()) {
            if($this->email){
                $this->user = User::findOne(['email' => $this->email]);
            }
            return true;
        } else {
            return false;
        }
    }
    
    public function rules()
    {
        return [
            [['email', 'password'], 'required'],
            ['email', 'trim'],
            [
                'password',
                function ($attribute) {
                    if ($this->user === null || !\Yii::$app->security->validatePassword($this->password, $this->user->password_hash)) {
                        $this->addError($attribute, "Неверная пара email/пароль");
                    }
                }
            ],
            [
                'email',
                function ($attribute) {
                    if ($this->user !== null) {
                        $confirmationRequired = param('enableConfirmation') && param('enableUnconfirmedLogin');
                        if ($confirmationRequired && !$this->user->getIsConfirmed()) {
                            $this->addError($attribute, 'You need to confirm your email address');
                        }
                        if ($this->user->getIsBlocked()) {
                            $this->addError($attribute, 'Your account has been blocked');
                        }
                    }
                }
            ],
            'rememberMe' => ['rememberMe', 'boolean'],
        ];
    }

    /**
     * Validates form and logs the user in.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return \Yii::$app->getUser()->login($this->user, $this->rememberMe ? param('rememberFor') : 0);
        } else {
            return false;
        }
    }

    public function formName()
    {
        return 'login-form';
    }
}
