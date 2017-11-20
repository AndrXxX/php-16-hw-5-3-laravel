<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    const TABLE_NAME = 'phone_book';

    public static function add($last_name, $first_name, $patronymic_name, $phone_number)
    {
        if (!empty($last_name) && !empty($first_name) && !empty($patronymic_name) && !empty($phone_number)) {
            \DB::table(self::TABLE_NAME)->insert([
                'last_name' => $last_name,
                'first_name' => $first_name,
                'patronymic_name' => $patronymic_name,
                'phone_number' => $phone_number,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
            return 'Контакт успешно создан';
        }
        return 'Контакт не был добавлен';
    }

    public static function find($last_name, $first_name, $patronymic_name, $phone_number)
    {
        /*if (empty($last_name)) {
            $last_name = '%';
        }
        if (empty($first_name)) {
            $first_name = '%';
        }
        if (empty($patronymic_name)) {
            $patronymic_name = '%';
        }
        if (empty($phone_number)) {
            $phone_number = '%';
        }*/
        /*в процессе*/
        return \DB::table(self::TABLE_NAME)
            /*->where('last_name', 'LIKE', $last_name)
            ->where('first_name', 'LIKE', $first_name)
            ->where('patronymic_name', 'LIKE', $patronymic_name)
            ->where('phone_number', 'LIKE', $phone_number)
            */->get();
    }

    public static function getAllContacts()
    {
        return \DB::table(self::TABLE_NAME)->get();
    }

    public static function edit($id, $last_name, $first_name, $patronymic_name, $phone_number)
    {
        $updArray = [];
        if (!empty($last_name)) {
            $updArray['last_name'] = $last_name;
        }
        if (!empty($first_name)) {
            $updArray['first_name'] = $first_name;
        }
        if (!empty($patronymic_name)) {
            $updArray['patronymic_name'] = $patronymic_name;
        }
        if (!empty($phone_number)) {
            $updArray['phone_number'] = $phone_number;
        }
        if (count($updArray) > 0) {
            $updArray['updated_at'] = Carbon::now();
            \DB::table(self::TABLE_NAME)->where('id', $id)->update($updArray);
            return 'Контакт успешно изменен';
        }
        return 'Контакт не был изменен';
    }

    public static function del($id)
    {
        if (is_numeric($id)) {
            \DB::table(self::TABLE_NAME)->where('id', $id)->delete();
        }
    }
}
