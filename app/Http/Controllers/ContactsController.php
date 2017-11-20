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
        if (isset($request['save'])) {
            return $this->edit($request);
        }

        if (isset($request['add'])) {
            return $this->add($request);
        }

        if (isset($request['find'])) {
            return $this->find($request);
        }

        return view($this->view, array(
            'contacts' => Contact::getAllContacts()
        ));
    }

    /**
     * Изменение контакта
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request)
    {
        $result = Contact::edit(
            $request['editContactID'],
            $request['last_name'], /* Фамилия */
            $request['first_name'], /* Имя */
            $request['patronymic_name'], /* Отчество */
            (int)$request['phone_number']
        );
        return view($this->view, array(
            'operationResult' => $result,
            'contacts' => Contact::getAllContacts()
        ));
    }

    /**
     * Поиск контакта
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function find(Request $request)
    {
        $result = Contact::find(
                $request['last_name'], /* Фамилия */
                $request['first_name'], /* Имя */
                $request['patronymic_name'], /* Отчество */
                (int)$request['phone_number']
            );

        return view($this->view, array(
            'contacts' => $result
            ));
    }

    /**
     * Добавление нового контакта
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function add(Request $request)
    {
        $result = Contact::add(
            $request['last_name'], /* Фамилия */
            $request['first_name'], /* Имя */
            $request['patronymic_name'], /* Отчество */
            (int)$request['phone_number']
        );
        return view($this->view, array(
            'operationResult' => $result,
            'contacts' => Contact::getAllContacts()
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
            'contacts' => Contact::getAllContacts()
        ));
    }

    /**
     * Удаляет контакт
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function delete(Request $request)
    {
        Contact::del($request['id']);
        return redirect('');
    }

    /**
     * Выводит форму для изменения контакта
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showEdit(Request $request)
    {
        return view($this->view, array(
            'editContactID' => $request['id'],
            'contacts' => Contact::getAllContacts()
        ));
    }
}
