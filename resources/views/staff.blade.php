@extends('layouts.app')

@section('content')
    <style>
        .card {
            background-color: #008188;
            color: #FFFFFF;
            margin-bottom: 20px;
        }

        .card-header {
            background-color: #004E6B;
            border-color: #004E6B;
        }

        .form-control {
            background-color: #FFFFFF;
            color: #004E6B;
        }

        .photo_img {
            margin-right: 1.25rem;
            /* Отступ для изображений */
            max-width: 100px;
            /* Ограничение ширины изображения */
            height: auto;
            /* Сохранение пропорций */
        }

        .list-group-item {
            border-color: #004E6B;
            /* Цвет рамки элемента списка */
        }

        .list-group-item .d-flex {
            align-items: center;
            /* Выравнивание по центру по вертикали */
        }

        .list-group-item .w-100 {
            flex: 1;
            /* Занимать всю доступную ширину */
        }

        .grade_img {
            width: 18px;
            margin: 0;
        }

        .alertMessageFalse {
            display: none;
            width: 100%;
            border: 1px solid red;
            margin: 0 0 1em 0;
            padding: 5px 1em;
            color: red;
            text-align: center;
        }

        .alertMessageTrue {
            display: none;
            width: 100%;
            border: 1px solid green;
            margin: 0 0 1em 0;
            padding: 5px 1em;
            color: green;
            text-align: center;
        }
    </style>

    <div class="container">
        <div class="card">
            <div class="card-header">
                Сотрудники
            </div>
            <div class="card-body">
                <!-- Поиск и фильтры -->
                <form id="" action="{{ route('staff') }}" method="GET">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" name="search" class="form-control" value="{{ request('search') }}"
                                    placeholder="Поиск...">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <select name="azs" class="form-control">
                                    <option value="">Все АЗС</option>
                                    @foreach (App\Models\azs::select('azs.*')->join('azs_to_user', 'azs_to_user.azs_id', '=', 'azs.id')->distinct()->get() as $azs)
                                        <option value="{{ $azs->id }}"
                                            {{ request('azs') == $azs->id ? 'selected' : '' }}>
                                            @if ($azs->delete == 0)
                                                {{ $azs->name }}
                                            @else
                                                {{ $azs->name . ' (Удалён)' }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md" style="margin: 0 0 1em 0;">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <button type="button" onclick="addOpen()" class="btn btn-primary" data-toggle="modal"
                                        data-target="#addEmployeeModal">
                                        Добавить сотрудника
                                    </button>
                                </div>
                                <div>
                                    <button type="submit" class="btn btn-primary">Поиск</button>
                                    <a href="{{ route('staff') }}" class="btn btn-secondary">Сбросить</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <!-- Список сотрудников -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="list-group">
                            @foreach ($users as $user)
                                <div class="list-group-item list-group-item-action">
                                    <div class="d-flex">
                                        <!-- Изображение сотрудника -->
                                        <div style="text-align: center;">
                                            <img src="{{ Storage::url('photo/' . $user->photo) }}" alt="Фото сотрудника"
                                                class="photo_img">
                                            <p>Код: {{ $user->code }}</p>
                                        </div>
                                        <div class="w-100">
                                            <!-- Информация о сотруднике -->
                                            <div class="d-flex w-100 justify-content-between">
                                                <h5 class="mb-1">Имя: {{ $user->name }}</h5>
                                                <small>АЗС: @if ($user->azs_delete == 0)
                                                        {{ $user->azs_name }}
                                                    @else
                                                        {{ $user->azs_name }} (Удалён)
                                                    @endif
                                                </small>
                                            </div>
                                            <div class="d-flex w-100 justify-content-between">
                                                <p class="mb-1">Должность: {{ App\Models\Role::find($user->role)->name }}
                                                </p>
                                                <div>
                                                    <p class="mb-1">
                                                        Рейтинг: {{ number_format($user->avg_grade, 1) }}
                                                        <img src="{{ Storage::url('icons/star_yellow.png') }}"
                                                            alt="Рейтинг" class="grade_img">
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="d-flex w-100 justify-content-between">
                                                <small>Номер телефона: {{ $user->phone }}</small>
                                                <div>
                                                    <button class="btn" onclick="editOpen({{ $user->id }})">
                                                        <img src="{{ Storage::url('icons/edit.png') }}" width="20"
                                                            alt="Редактировать">
                                                    </button>
                                                    <button class="btn" onclick="open_delete({{ $user->id }})">
                                                        <img src="{{ Storage::url('icons/delete.png') }}" width="20"
                                                            alt="Удалить">
                                                    </button>
                                                    <a href="/staff/review/{{ $user->id }}" class="btn"><img
                                                            src="{{ Storage::url('icons/review.png') }}" width="20"
                                                            alt="Посмотреть отзывы"></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Пагинация -->
        <div class="row">
            <div class="col-md-12">
                <div class="d-flex justify-content-center">
                    {{ $users->appends(request()->query())->links() }}
                </div>
            </div>
        </div>

        <!-- Модальное окно добавления сотрудника -->
        <div class="modal fade" id="addEmployeeModal" tabindex="-1" role="dialog" aria-labelledby="addEmployeeModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addEmployeeModalLabel">Добавление нового сотрудника</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="alertMessageTrueAdd" class="alertMessageTrue"></div>
                        <div id="alertMessageFalseAdd" class="alertMessageFalse"></div>
                        <div class="form-group">
                            <label for="add_name">Имя сотрудника</label>
                            <input type="text" class="form-control" placeholder="Имя сотрудника" id="add_name" required>
                        </div>
                        <div class="form-group">
                            <label for="add_role">Должность</label>
                            <select name="role" id='add_role' class="form-control" required>
                                <option value="" disabled selected>Выберите Должность!</option>
                                @foreach (App\Models\Role::where('id', '<>', 1)->get() as $role)
                                    <option value="{{ $role->id }}">
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="add_phone">Номер телефона</label>
                            <input type="phone" class="form-control" id="add_phone" required
                                data-inputmask="'mask': '+7 (999) 999-99-99'" placeholder="+7 (___) ___-__-__"
                                autocomplete="phone">
                        </div>
                        <div class="form-group">
                            <label for="add_azs">АЗС</label>
                            <select name="azs" id='add_azs' class="form-control" required>
                                <option value="" disabled selected>Выберите АЗС!</option>
                                @foreach (App\Models\azs::where('delete', '=', '0')->get() as $azs)
                                    <option value="{{ $azs->id }}">
                                        {{ $azs->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="button" onclick="add_staff()" class="btn btn-primary">Добавить</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Модалка для редактирование -->
    <div class="modal fade" id="dataModalEdit" tabindex="-1" aria-labelledby="dataModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="dataModalLabel">Редактирование</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="preloader-edit">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                    <div id="data-container-edit" style="display: none;">
                        <div id="alertMessageTrueEdit" class="alertMessageTrue"></div>
                        <div id="alertMessageFalseEdit" class="alertMessageFalse"></div>
                        <div class="form-group">
                            <label for="edit_name">Имя сотрудника</label>
                            <input type="text" class="form-control" placeholder="Имя сотрудника" id="edit_name"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="edit_role">Должность</label>
                            <select name="role" id='edit_role' class="form-control" required>
                                <option value="" disabled selected>Выберите Должность!</option>
                                @foreach (App\Models\Role::where('id', '<>', 1)->get() as $role)
                                    <option value="{{ $role->id }}">
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit_phone">Номер телефона</label>
                            <input type="phone" class="form-control" id="edit_phone" required
                                data-inputmask="'mask': '+7 (999) 999-99-99'" placeholder="+7 (___) ___-__-__"
                                autocomplete="phone">
                        </div>
                        <div class="form-group">
                            <label for="edit_azs">АЗС</label>
                            <select name="azs" id='edit_azs' class="form-control" required>
                                <option value="" disabled selected>Выберите АЗС!</option>
                                @foreach (App\Models\azs::where('delete', '=', '0')->get() as $azs)
                                    <option value="{{ $azs->id }}">
                                        {{ $azs->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="edit_id" onclick="edit()">Сохранить</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Модалка для Удаление -->
    <div class="modal fade" id="dataModalDelete" tabindex="-1" aria-labelledby="dataModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="dataModalLabel">Удаление</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="preloader-delete">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                    <div id="data-container-delete" style="display: none;">
                        <div id="alertMessageTrueDelete" class="alertMessageTrue"></div>
                        <div id="alertMessageFalseDelete" class="alertMessageFalse"></div>
                        <h3>Вы уверены что хотите удалить Сотрудника?</h3>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="delete_id"
                        onclick="delete_staff()">Сохранить</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        $(document).ready(function() {
            $('#add_phone').inputmask();
            $('#edit_phone').inputmask();
        });

        function addOpen() {
            $('#add_name').prop('disabled', false);
            $('#add_role').prop('disabled', false);
            $('#add_phone').prop('disabled', false);
            $('#add_azs').prop('disabled', false);

            $('#add_name').val('');
            $('#add_role').val('');
            $('#add_phone').val('');
            $('#add_azs').val('');

            $('#alertMessageFalseAdd').hide();
            $('#alertMessageTrueAdd').hide();
        }

        function add_staff() {
            $('#alertMessageFalseAdd').hide();
            $('#alertMessageTrueAdd').hide();


            let name = $('#add_name').val();
            let role = $('#add_role').val();
            let phone = $('#add_phone').val();
            let azs = $('#add_azs').val();

            let phonePattern = /^\+7 \(\d{3}\) \d{3}-\d{2}-\d{2}$/;

            if (name == '' || role == null || phone == '' || azs == null) {
                $('#alertMessageFalseAdd').show().text('Заполниет все поля!');
                return false;
            }

            if (!phonePattern.test(phone)) {
                $('#alertMessageFalseAdd').show().text('Введите корректный номер телефона!');
                return false;
            }

            $.ajax({
                url: 'api/staff/add',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Authorization': "Bearer {{ $token }}"
                },
                data: {
                    name: name,
                    role: role,
                    phone: phone,
                    azs: azs
                },
                success: function(response) {
                    $('#alertMessageTrueAdd').text(response['success']).css('display', 'block');
                    // setTimeout(() => {
                    //     location.reload();
                    // }, 1000);
                    $('#add_name').prop('disabled', true);
                    $('#add_role').prop('disabled', true);
                    $('#add_phone').prop('disabled', true);
                    $('#add_azs').prop('disabled', true);
                },
                error: function(xhr) {
                    $('#alertMessageFalseAdd').text(response['error'] + " | " + response['message']).css(
                        'display', 'block');
                }
            });
        }

        function editOpen(staff) {
            $('#dataModalEdit').modal('show');
            $('.preloader-edit').show();
            $('#data-container-edit').hide();

            $.ajax({
                url: '/api/staff',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Authorization': "Bearer {{ $token }}"
                },
                data: {
                    id: staff
                },
                success: function(staff) {
                    $('.preloader-edit').hide();
                    $('#data-container-edit').show();
                    $('#edit_name').val(staff['name']);
                    $('#edit_role').val(staff['role']);
                    $('#edit_phone').val(staff['phone']);
                    $('#edit_azs').val(staff['azs']);
                    $('#edit_id').val(staff['id']);
                },
                error: function() {
                    $('.preloader-edit').hide();
                    $('#data-container-edit').html('<p>Ошибка при загрузке данных.</p>').show();
                }
            });
        }

        function edit() {
            let name = $('#edit_name').val().trim();
            let role = $('#edit_role').val();
            let phone = $('#edit_phone').val().trim();
            let azs = $('#edit_azs').val();
            let id = $('#edit_id').val().trim();

            if (!name || !role || !phone || !azs) {
                let errorMessage = 'Заполните все поля перед сохранением.';
                $('#alertMessageFalseEdit').text(errorMessage).css('display', 'block');
                return false;
            }

            $.ajax({
                url: 'api/staff/edit',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Authorization': "Bearer {{ $token }}"
                },
                data: {
                    name: name,
                    role: role,
                    phone: phone,
                    azs: azs,
                    id: id,
                },
                success: function(response) {
                    $('#alertMessageTrueEdit').text(response['success']).css('display', 'block');
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                },
                error: function(xhr) {
                    $('#alertMessageFalseEdit').text(response['error']).css('display', 'block');
                }
            });
        }

        function open_delete(staff) {
            $('#dataModalDelete').modal('show');
            $('.preloader-delete').show();
            $('#data-container-delete').hide();

            $.ajax({
                url: '/api/staff',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Authorization': "Bearer {{ $token }}"
                },
                data: {
                    id: staff,
                },
                success: function(staff) {
                    $('.preloader-delete').hide();
                    $('#data-container-delete').show();
                    $('#delete_id').val(staff['id']);
                },
                error: function(xhr) {
                    $('.preloader-delete').hide();
                    let errorMessage = 'Произошла ошибка';

                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        errorMessage = xhr.responseJSON.error;
                    } else if (xhr.statusText) {
                        errorMessage = xhr.statusText;
                    }

                    $('#data-container-delete').html('<p>' + errorMessage + '</p>').show();
                }
            });
        }

        function delete_staff() {
            let id = $('#delete_id').val().trim();
            $.ajax({
                url: 'api/staff/delete',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Authorization': "Bearer {{ $token }}"
                },
                data: {
                    id: id
                },
                success: function(response) {
                    $('#alertMessageTrueDelete').text(response['success']).css('display', 'block');
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                },
                error: function(xhr) {
                    $('#alertMessageFalseDelete').text(response['success'] + " | " + response['error']).css(
                        'display', 'block');
                }
            });
        }
    </script>
@endsection
