@extends('main')
@section('title', 'Содержание телефонной книги')

@section('content')
  <div class="form-container">
    <h1>Телефонная книга</h1>
    <h3><a href='{{ route('contacts.index') }}'>Показать весь список</a></h3>
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
    </table>

    <form action="{{ route('contacts.index') }}" method="POST">
      {{ csrf_field() }}
      <table>
        <tr>
          <th colspan="8">Поиск по контактам</th>
        </tr>
        <tr>
          <td></td>
          <td><input type="text" name="f_last_name" value="{{ $f_last_name or '' }}" placeholder="Фамилия"></td>
          <td><input type="text" name="f_first_name" value="{{ $f_first_name or '' }}" placeholder="Имя"></td>
          <td><input type="text" name="f_patronymic_name" value="{{ $f_patronymic_name or '' }}" placeholder="Отчество">
          </td>
          <td><input type="tel" name="f_phone_number" value="{{ $f_phone_number or '' }}" placeholder="74950000000">
          </td>
          <td></td>
          <td></td>
          <td><input class="btn" type="submit" name="find" value="Найти"></td>
        </tr>
      </table>
    </form>

    <table>
      <tr>
        <th colspan="8">Вывод списка контактов</th>
      </tr>
      @foreach ($contacts as $contact)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $contact->last_name }}</td>
          <td>{{ $contact->first_name }}</td>
          <td>{{ $contact->patronymic_name }}</td>
          <td>{{ $contact->phone_number }}</td>
          <td>{{ $contact->created_at }}</td>
          <td>{{ $contact->updated_at }}</td>
          <td>
            <a class="btn" href='{{ route('contacts.edit', ['id' => $contact->id]) }}'>Изменить</a>
            <form class="br" action="{{ route('contacts.destroy', ['id' => $contact->id]) }}" method="POST">
              {{ csrf_field() }}
              {{ method_field('DELETE') }}
              <input class="btn" type="submit" name="delete" value="Удалить">
            </form>
          </td>
        </tr>
      @endforeach
    </table>

    @if (!empty($editContactID))
      <form action="{{ route('contacts.update', ['id' => $editContactID]) }}" method="POST">
        {{ csrf_field() }}
        {{ method_field('PUT') }}
        <table>
          <tr>
            <th colspan="8">Изменение контакта</th>
          </tr>
          <tr>
            @foreach ($contacts as $contact)
              @if ($contact->id == $editContactID)
                <td></td>
                <td><input type="text" name="last_name" value="{{ $contact->last_name }}"></td>
                <td><input type="text" name="first_name" value="{{ $contact->first_name }}"></td>
                <td><input type="text" name="patronymic_name" value="{{ $contact->patronymic_name }}"></td>
                <td><input type="tel" name="phone_number" value="{{ $contact->phone_number }}"></td>
                <td>{{ $contact->created_at }}</td>
                <td>{{ $contact->updated_at }}</td>
                <td>
                  <input type="hidden" name="editContactID" value="{{ $contact->id }}">
                  <input class="btn" type="submit" name="save" value="Сохранить">
                </td>
              @endif
            @endforeach
          </tr>
        </table>
      </form>

    @else
      <form action="{{ route('contacts.index') }}" method="POST">
        {{ csrf_field() }}
        <table>
          <tr>
            <th colspan="8">Добавление нового контакта</th>
          </tr>
          <tr>
            <td></td>
            <td><input type="text" name="last_name" value="" placeholder="Фамилия"></td>
            <td><input type="text" name="first_name" value="" placeholder="Имя"></td>
            <td><input type="text" name="patronymic_name" value="" placeholder="Отчество"></td>
            <td><input type="tel" name="phone_number" value="" placeholder="74950000000"></td>
            <td></td>
            <td></td>
            <td><input class="btn" type="submit" name="add" value="Добавить контакт"/></td>
          </tr>
        </table>
      </form>
    @endif

    @if (!empty($operationResult))
      <p>{{ $operationResult }}</p>
    @endif
  </div>
@endsection