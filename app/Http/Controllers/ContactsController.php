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

        return $this->index(
            $request,
            Contact::all()
        );
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

        $request->validate([
            'last_name' => 'required|max:127',
            'first_name' => 'required|max:127',
            'patronymic_name' => 'required|max:127',
            'phone_number' => 'required|integer|max:99999999999'
        ]);

        $contact = $request->has('save') ? Contact::find($id) : new Contact();
        $contact->fill($request->all('first_name', 'last_name', 'patronymic_name'));
        $contact->phone_number = (int)substr($request->phone_number, 0, 11);
        $result = $contact->save();

        $params = array(
            'operationResult' => $result === true
                ? ('Контакт ' . $resultHintAction) : ('Контакт не был ' . $resultHintAction)
        );

        return $this->index(
            $request,
            Contact::all(),
            $params
        );
    }

    /**
     * Вывод формы с полями для изменения контакта
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request, $id)
    {
        return $this->index(
            $request,
            Contact::all(),
            array('editContactID' => $id)
        );
    }

    /**
     * Выводит найденные контакты по фамилии, имени и т.д.
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected function find($request)
    {
        $f_last_name = !$request->has('f_last_name') ? '%' : $request->f_last_name; // Фамилия
        $f_first_name = !$request->has('f_first_name') ? '%' : $request->f_first_name; // Имя
        $f_patronymic_name = !$request->has('f_patronymic_name') ? '%' : $request->f_patronymic_name; // Отчество
        $f_phone_number = !$request->has('f_phone_number')
            ? '%' : substr($request->f_phone_number, 0, 11);

        $searchResult = Contact::where('last_name', 'like', "%$f_last_name%")
            ->where('first_name', 'like', "%$f_first_name%")
            ->where('patronymic_name', 'like', "%$f_patronymic_name%")
            ->where('phone_number', 'like', "%$f_phone_number%")
            ->get();

        $params = array(
            'f_last_name' => $request->f_last_name,
            'f_first_name' => $request->f_first_name,
            'f_patronymic_name' => $request->f_patronymic_name,
            'f_phone_number' => $request->f_phone_number
        );

        return $this->index(
            $request,
            $searchResult,
            $params
        );
    }

    /**
     * Выводит контакт по id
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Request $request, $id)
    {
        return $this->index(
            $request,
            array('contacts' => Contact::findOrFail(substr($id, 0, 8)))
        );
    }

    /**
     * Вывод списка контактов
     * @param Request $request
     * @param null|\Illuminate\Database\Eloquent\Collection|static[] $contacts
     * @param array $params
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request, $contacts = null, $params=[])
    {
        /* если была нажата кнопка Найти */
        if ($request->has('find') && count($params) === 0) {
            return $this->find($request);
        }

        if ($contacts === null) {
            $contacts = Contact::all();
        }
        $params['contacts'] = $contacts;
        return view($this->view, $params);
    }

    /**
     * Удаляет контакт
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function destroy(Request $request, $id)
    {
        $result = Contact::destroy([$id]);
        return $this->index(
            $request,
            Contact::all(),
            array('operationResult' => $result > 0 ? 'Контакт удален' : 'Контакт не был удален')
        );
    }
}
