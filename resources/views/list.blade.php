@extends('main')
@section('title', 'Содержание телефонной книги')

@section('content')
  <h1>Телефонная книга</h1>
  <div class="form-container">

    @if (!empty($operationResult))
      <p>{{ $operationResult }}</p>
    @endif

    <form action="/" method="POST">
      {{ csrf_field() }}

      <table>
        <tr>
          <th>№</th>
          <th>Фамилия</th>
          <th>Имя</th>
          <th>Отчество</th>
          <th>Телефон</th>
          <th>Дата создания</th>
          <th>Дата обновления</th>
          <th>Действия</th>
        </tr>

        <tr>
          <td></td>
          <td><input type="text" name="last_name" value=""></td>
          <td><input type="text" name="first_name" value=""></td>
          <td><input type="text" name="patronymic_name" value=""></td>
          <td><input type="tel" name="phone_number" value=""></td>
          <td></td>
          <td></td>
          <td>
            <input type="hidden" name="tableName" value="">
            <input type="submit" name="find" value="Найти">
          </td>
        </tr>

        @foreach ($contacts as $contact)
          <tr>
            @if (!empty($editContactID) && ($contact->id == $editContactID))
              <td>{{ $loop->iteration }}</td>
              <td><input type="text" name="last_name" value="{{ $contact->last_name }}"></td>
              <td><input type="text" name="first_name" value="{{ $contact->first_name }}"></td>
              <td><input type="text" name="patronymic_name" value="{{ $contact->patronymic_name }}"></td>
              <td><input type="text" name="phone_number" value="{{ $contact->phone_number }}"></td>
              <td>{{ $contact->created_at }}</td>
              <td>{{ $contact->updated_at }}</td>
              <td>
                <input type="hidden" name="editContactID" value="{{ $contact->id }}">
                <input type="submit" name="save" value="Сохранить">
              </td>
            @else
              <td>{{ $loop->iteration }}</td>
              <td>{{ $contact->last_name }}</td>
              <td>{{ $contact->first_name }}</td>
              <td>{{ $contact->patronymic_name }}</td>
              <td>{{ $contact->phone_number }}</td>
              <td>{{ $contact->created_at }}</td>
              <td>{{ $contact->updated_at }}</td>
              <td>
                <a href='/edit/{{ $contact->id }}'>Изменить</a>
                <a href='/delete/{{ $contact->id }}'>Удалить</a>
              </td>
            @endif
          </tr>
        @endforeach

        @if (empty($editContactID))
          <tr>
            <td></td>
            <td><input type="text" name="last_name" value="" placeholder="Фамилия"></td>
            <td><input type="text" name="first_name" value="" placeholder="Имя"></td>
            <td><input type="text" name="patronymic_name" value="" placeholder="Отчество"></td>
            <td><input type="tel" name="phone_number" value="" placeholder="74950000000"></td>
            <td></td>
            <td></td>
            <td>
              <input type="submit" name="add" value="Добавить контакт"/>
            </td>
          </tr>
        @endif

      </table>
    </form>
@endsection