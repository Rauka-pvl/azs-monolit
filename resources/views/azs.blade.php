@extends('layouts.app')

@section('content')
    <style>
        .station-list {
            margin-top: 20px;
        }

        .station-card {
            border: 1px solid #008188;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .station-card h5 {
            color: #004E6B;
        }

        .station-card p {
            margin: 0 0 0.1em 0;
        }

        .btn-primary {
            background-color: #004E6B;
            border-color: #004E6B;
        }

        .btn-secondary {
            background-color: #008188;
            border-color: #008188;
        }

        #data-container-edit input {
            margin: 0 0 1em 0;
        }

        #data-container-add input {
            margin: 0 0 1em 0;
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
    <div class="container station-list mt-4">
        <button class="btn btn-primary mb-3" onclick="addOpen()">Добавить АЗС</button>
        <div class="row">
            @foreach ($azs as $a)
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <div class="station-card">
                        <h5>{{ $a->name }}</h5>
                        <p>Населенный пункт: {{ $a->zone_name }}</p>
                        <p>Адрес: {{ $a->address }}</p>
                        <p>Сотрудников: {{ $a->count_staff }}</p>
                        <p style="display: flex; align-items: center;">
                            Рейтинг: {{ number_format($a->avg_grade, 1) }}
                            <img src="{{ Storage::url('icons/star_yellow.png') }}" width="18" alt="Рейтинг">
                        </p>
                        <p>Время пересменки: {{ $a->time }}</p>
                        <button class="btn" id="staff"
                            onclick="location.href = '{{ route('staff', ['azs' => $a->id]) }}'">
                            <img src="{{ Storage::url('icons/staff.png') }}" width="20" alt="Сотрудники">
                        </button>
                        <button class="btn" onclick="editOpen({{ $a->id }})">
                            <img src="{{ Storage::url('icons/edit.png') }}" width="20" alt="Редактировать">
                        </button>
                        <button class="btn" onclick="deleteOpen({{ $a->id }})">
                            <img src="{{ Storage::url('icons/delete.png') }}" width="20" alt="Удалить">
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    {{-- Модалка для добавление --}}
    <div class="modal fade" id="dataModalAdd" tabindex="-1" aria-labelledby="dataModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="dataModalLabel">Добавление АЗС</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="data-container-add">
                        <div id="alertMessageTrueAdd" class="alertMessageTrue"></div>
                        <div id="alertMessageFalseAdd" class="alertMessageFalse"></div>
                        <label for="addName">Название АЗС</label>
                        <input type="text" class="form-control" id="addName" placeholder="Название АЗС">
                        <label for="addZone">Населенный пункт АЗС</label>
                        <select class="form-control" id="addZone" style="margin: 0 0 1em 0">
                            <option value="" disabled selected>Выберите Населенный пункт!</option>
                            @foreach (App\Models\Zone::where('delete', '=', 0)->get() as $zone)
                                <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                            @endforeach
                        </select>
                        <label for="addAddress">Адрес АЗС</label>
                        <input type="text" class="form-control" id="addAddress" placeholder="Адрес АЗС">
                        <label for="addTime">Пересменка АЗС</label>
                        <input type="time" class="form-control" id="addTime" placeholder="Пересменка АЗС"
                            style="max-width: 6.25em;">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" onclick="add()">Сохранить</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
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
                        <label for="editName">Название АЗС</label>
                        <input type="text" class="form-control" id="editName" placeholder="Название АЗС">
                        <label for="editZone">Населенный пункт АЗС</label>
                        <select class="form-control" id="editZone" style="margin: 0 0 1em 0">
                            @foreach (App\Models\Zone::where('delete', '=', 0)->get() as $zone)
                                <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                            @endforeach
                        </select>
                        <label for="editAddress">Адрес АЗС</label>
                        <input type="text" class="form-control" id="editAddress" placeholder="Адрес АЗС">
                        <label for="editTime">Пересменка АЗС</label>
                        <input type="time" class="form-control" id="editTime" placeholder="Пересменка АЗС"
                            style="max-width: 6.25em;">
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
                        <h3>Вы уверены что хотите удалить АЗС?</h3>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="delete_id"
                        onclick="deleteAZS()">Сохранить</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        function addOpen() {
            $('#dataModalAdd').modal('show');
            $('#addName').val('')
            $('#addZone').val('')
            $('#addAddress').val('')
            $('#addTime').val('')
        }

        function add() {
            let name = $('#addName').val().trim();
            let zone = $('#addZone').val();
            let address = $('#addAddress').val().trim();
            let time = $('#addTime').val().trim();

            if (!name || !zone || !address || !time) {
                let errorMessage = 'Заполните все поля перед сохранением.';
                $('#alertMessageFalseAdd').text(errorMessage).css('display', 'block');
                return false;
            }

            $.ajax({
                url: 'api/azs/add',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Authorization': "Bearer {{ $token }}"
                },
                data: {
                    name: name,
                    zone: zone,
                    address: address,
                    time: time
                },
                success: function(response) {
                    $('#alertMessageTrueAdd').text(response['success']).css('display', 'block');
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                },
                error: function(xhr) {
                    $('#alertMessageFalseAdd').text(response['success'] + " | " + response['error']).css(
                        'display', 'block');
                }
            });
        }

        function editOpen(azs) {
            $('#dataModalEdit').modal('show');
            $('.preloader-edit').show();
            $('#data-container-edit').hide();

            $.ajax({
                url: '/api/azs',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Authorization': "Bearer {{ $token }}"
                },
                data: {
                    id: azs
                },
                success: function(azs) {
                    $('.preloader-edit').hide();
                    $('#data-container-edit').show();
                    $('#editName').val(azs['name']);
                    $('#editZone').val(azs['zone']);
                    $('#editAddress').val(azs['address']);
                    $('#edit_id').val(azs['id']);

                    let timeParts = azs['time'].split(':');
                    if (timeParts[0].length === 1) {
                        timeParts[0] = '0' + timeParts[0];
                    }
                    $('#editTime').val(timeParts.join(':'));
                },
                error: function() {
                    $('.preloader-edit').hide();
                    $('#data-container-edit').html('<p>Ошибка при загрузке данных.</p>').show();
                }
            });
        }

        function edit() {
            let name = $('#editName').val().trim();
            let zone = $('#editZone').val();
            let address = $('#editAddress').val().trim();
            let time = $('#editTime').val().trim();
            let id = $('#edit_id').val().trim();

            if (!name || !zone || !address || !time) {
                let errorMessage = 'Заполните все поля перед сохранением.';
                $('#alertMessageFalseEdit').text(errorMessage).css('display', 'block');
                return false;
            }

            $.ajax({
                url: 'api/azs/edit',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Authorization': "Bearer {{ $token }}"
                },
                data: {
                    name: name,
                    zone: zone,
                    address: address,
                    time: time,
                    id: id,
                },
                success: function(response) {
                    $('#alertMessageTrueEdit').text(response['success']).css('display', 'block');
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                },
                error: function(xhr) {
                    $('#alertMessageFalseEdit').text(response['error']).css('display', 'block');
                }
            });
        }

        function deleteOpen(azs) {
            $('#dataModalDelete').modal('show');
            $('.preloader-delete').show();
            $('#data-container-delete').hide();

            $.ajax({
                url: '/api/azs',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Authorization': "Bearer {{ $token }}"
                },
                data: {
                    id: azs
                },
                success: function(azs) {
                    $('.preloader-delete').hide();
                    $('#data-container-delete').show();
                    $('#delete_id').val(azs['id']);
                },
                error: function() {
                    $('.preloader-delete').hide();
                    $('#data-container-delete').html('<p>Ошибка при загрузке данных.</p>').show();
                }
            });
        }

        function deleteAZS() {
            let id = $('#delete_id').val().trim();
            $.ajax({
                url: 'api/azs/delete',
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
