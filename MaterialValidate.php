<?php

namespace kadam;


class MaterialValidate
{
    public static function onCreate(int $type, array $fields)
    {
        $requireFields = [
            'title',
            'linkUrl',
        ];

        foreach ($requireFields as $field) {
            if(empty($fields[$field])) {
                throw new \Exception("The field \"{$field}\" can not be empty");
            }
        }

        if($type === 30 && empty($fields['text'])) {
            throw new \Exception("The field \"text\" can not be empty");
        }

        if ($type == 70 && empty($fields['size'])) {
            throw new \Exception("The field \"size\" can not be empty");
        }

        return true;
    }
}