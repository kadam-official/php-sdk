<?php
/**
 * Created by PhpStorm.
 * User: darkfriend <hi@darkfriend.ru>
 * Date: 20.01.2021
 * Time: 15:30
 */

namespace kadam;


class MaterialValidate
{
    public static function onCreate(int $type, array $fields)
    {
        $requireFields = [
            'title',
            'text',
            'linkUrl',
        ];

        foreach ($requireFields as $field) {
            if(empty($fields[$field])) {
                throw new \Exception("The field \"{$field}\" can not be empty");
            }
        }

        if ($type == 70 && empty($fields['size'])) {
            throw new \Exception("The field \"size\" can not be empty");
        }

        return true;
    }
}