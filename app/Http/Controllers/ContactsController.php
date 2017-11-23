<?php

namespace App\Http\Controllers;

use App\Contact;
use DB;
use Illuminate\Http\Request;

class ContactsController extends Controller
{
    protected $view = 'list'; // название шаблона

    /**
     * Обработка POST запросов
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function store(Request $request)
    {
        /* если была нажата кнопка Сохранить */
        if (isset($request['save'])) {
            return $this->edit($request, $request['editContactID']);
        }

        /* если была нажата кнопка Добавить контакт */
        if (isset($request['add'])) {
            return $this->add($request);
        }

        /* если была нажата кнопка Найти */
        if (isset($request['find'])) {
            return $this->find($request);
        }

        return view($this->view, array(
            'contacts' => Contact::all()
        ));
    }

    /**
     * Изменение контакта
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request, $id)
    {
        $resultHint = '';
        if (isset($request['save']) && !empty($request['last_name']) && !empty($request['first_name'])
            && !empty($request['patronymic_name']) && !empty($request['phone_number'])
            && !empty((int)$request['phone_number'])) {
            $contact = Contact::find($id);
            $contact->last_name = $request['last_name']; // Фамилия
            $contact->first_name = $request['first_name']; // Имя
            $contact->patronymic_name = $request['patronymic_name']; // Отчество
            $contact->phone_number = (int)substr($request['phone_number'], 0, 11);
            $result = $contact->save();
            $resultHint = $result === true ? 'Контакт изменен' : 'Контакт не был изменен';
        }
        $editContactID = !isset($request['save']) ? $id : '';
        return view($this->view, array(
            'operationResult' => $resultHint,
            'editContactID' => $editContactID,
            'contacts' => Contact::all()
        ));
    }

    /**
     * Добавление нового контакта
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected function add(Request $request)
    {
        if (!empty($request['last_name']) && !empty($request['first_name']) && !empty($request['patronymic_name'])
            && !empty($request['phone_number']) && !empty((int)$request['phone_number'])) {
            $contact = new Contact();
            $contact->last_name = $request['last_name']; // Фамилия
            $contact->first_name = $request['first_name']; // Имя
            $contact->patronymic_name = $request['patronymic_name']; // Отчество
            $contact->phone_number = (int)substr($request['phone_number'], 0, 11);
            $result = $contact->save();
        } else {
            $result = false;
        }

        return view($this->view, array(
            'operationResult' => $result === true ? 'Контакт добавлен' : 'Контакт не был добавлен',
            'contacts' => Contact::all()
        ));
    }

    /**
     * Поиск контакта
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected function find($request)
    {
        $f_last_name = empty($request['f_last_name']) ? '%' : $request['f_last_name']; // Фамилия
        $f_first_name = empty($request['f_first_name']) ? '%' : $request['f_first_name']; // Имя
        $f_patronymic_name = empty($request['f_patronymic_name']) ? '%' : $request['f_patronymic_name']; // Отчество
        $f_phone_number = empty($request['f_phone_number']) ? '%' : substr($request['f_phone_number'], 0, 11);

        $searchResult = Contact::where('last_name', 'like', '%' . $f_last_name . '%')
            ->where('first_name', 'like', '%' . $f_first_name . '%')
            ->where('patronymic_name', 'like', '%' . $f_patronymic_name . '%')
            ->where('phone_number', 'like', '%' . $f_phone_number . '%')
            ->get();

        return view($this->view, array(
            'f_last_name' => $request['f_last_name'],
            'f_first_name' => $request['f_first_name'],
            'f_patronymic_name' => $request['f_patronymic_name'],
            'f_phone_number' => $request['f_phone_number'],
            'contacts' => $searchResult
        ));
    }

    /**
     * Выводит контакт по id
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Request $request, $id)
    {
        $id = substr($id, 0, 8);
        return view($this->view, array(
            'contacts' => Contact::where('id', $id)->get()
        ));
    }

    /**
     * Вывод списка контактов
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        return view($this->view, array(
            'contacts' => Contact::all()
        ));
    }

    /**
     * Удаляет контакт
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function delete(Request $request, $id)
    {
        $result = Contact::destroy([$id]);
        return view($this->view, array(
            'operationResult' => $result > 0 ? 'Контакт удален' : 'Контакт не был удален',
            'contacts' => Contact::all()
        ));
    }
}
