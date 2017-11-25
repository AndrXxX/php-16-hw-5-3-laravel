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
        /* если была нажата кнопка Добавить контакт */
        if ($request->has('add')) {
            return $this->update($request);
        }

        /* если была нажата кнопка Найти */
        if ($request->has('find')) {
            return $this->find($request);
        }

        return view($this->view, array(
            'contacts' => Contact::all()
        ));
    }

    /**
     * Изменение/добавление контакта
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function update(Request $request, $id = null)
    {
        $resultHintAction = $request->has('save') ? 'изменен' : 'добавлен';
        if ($request->has('last_name') && $request->has('first_name')
            && $request->has('patronymic_name') && $request->has('phone_number')
            && is_numeric((int)$request->input('phone_number'))) {
                $contact = $request->has('save') ? Contact::find($id) : new Contact();
                $contact->fill($request->all('first_name', 'last_name', 'patronymic_name'));
                $contact->phone_number = (int)substr($request->input('phone_number'), 0, 11);
                $result = $contact->save();
        } else {
            $result = false;
        }

        return view($this->view, array(
            'operationResult' => $result === true
                ? ('Контакт ' . $resultHintAction) : ('Контакт не был ' . $resultHintAction),
            'contacts' => Contact::all()
        ));
    }

    /**
     * Вывод формы с полями для изменения контакта
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request, $id)
    {
        return view($this->view, array(
            'editContactID' => $id,
            'contacts' => Contact::all()
        ));
    }

    /**
     * Выводит найденные контакты по фамилии, имени и т.д.
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected function find($request)
    {
        $f_last_name = !$request->has('f_last_name') ? '%' : $request->input('f_last_name'); // Фамилия
        $f_first_name = !$request->has('f_first_name') ? '%' : $request->input('f_first_name'); // Имя
        $f_patronymic_name = !$request->has('f_patronymic_name')
            ? '%' : $request->input('f_patronymic_name'); // Отчество

        $f_phone_number = !$request->has('f_phone_number')
            ? '%' : substr($request->input('f_phone_number'), 0, 11);

        $searchResult = Contact::where('last_name', 'like', '%' . $f_last_name . '%')
            ->where('first_name', 'like', '%' . $f_first_name . '%')
            ->where('patronymic_name', 'like', '%' . $f_patronymic_name . '%')
            ->where('phone_number', 'like', '%' . $f_phone_number . '%')
            ->get();

        return view($this->view, array(
            'f_last_name' => $request->input('f_last_name'),
            'f_first_name' => $request->input('f_first_name'),
            'f_patronymic_name' => $request->input('f_patronymic_name'),
            'f_phone_number' => $request->input('f_phone_number'),
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
