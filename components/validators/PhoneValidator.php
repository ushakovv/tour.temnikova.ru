<?php
/**
 * Created by PhpStorm.
 * User: Павел
 * Date: 24.08.2017
 * Time: 15:52
 */

namespace app\components\validators;

use yii\validators\Validator;


class PhoneValidator extends Validator
{
    const PHONE_MASK = '/^\+79[0-9]{9}$/';

    public function validateAttribute($model, $attribute)
    {
        if(!preg_match(self::PHONE_MASK, $model->$attribute)) {
            $this->addError($model, $attribute, 'Пожалуйста, укажите Ваш телефон, например +79161234567');
        }
    }
}