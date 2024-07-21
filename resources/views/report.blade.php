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
    </style>

    <div class="container">
        <div class="card">
            <div class="card-header">
                Отчеты
            </div>
            <div class="card-body">
                <form action="/reportPDF" method="POST">
                    @csrf
                    <h2 class="mb-4">Формирование отчета</h2>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="startDate">Дата начала</label>
                            <input type="date" class="form-control" name="startDate" id="startDate" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="endDate">Дата окончания</label>
                            <input type="date" class="form-control" name="endDate" id="endDate" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="azs">АЗС</label>
                        <select class="form-control" id="azs" name="azs">
                            <option value="">Все АЗС</option>
                            @foreach (App\Models\azs::where('delete', '=', '0')->get() as $azs)
                                <option value="{{ $azs->id }}">
                                    {{ $azs->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="employeeSelect">Сотрудники</label>
                        <select class="form-control" id="employeeSelect" name="staff[]" multiple>
                            @foreach (App\Models\User::where('delete', '=', null)->get() as $azs)
                                <option value="{{ $azs->id }}">
                                    {{ $azs->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button class="btn btn-primary">Сформировать отчет</button>
                </form>
            </div>
        </div>
        <script>
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            $(document).ready(function() {
                $('#azs').on('change', function() {
                    var azsId = $(this).val();
                    $.ajax({
                        url: '/api/staff/getAllStaff',
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Authorization': "Bearer {{ $token }}"
                        },
                        data: {
                            azs_id: azsId
                        },
                        success: function(data) {
                            $('#employeeSelect').empty();
                            if (azsId === "") {
                                data.allEmployees.forEach(function(employee) {
                                    $('#employeeSelect').append(new Option(employee.name,
                                        employee.id));
                                });
                            } else {
                                data.employees.forEach(function(employee) {
                                    $('#employeeSelect').append(new Option(employee.name,
                                        employee.id));
                                });
                            }
                        },
                        error: function(xhr) {
                            console.log(xhr.responseText);
                        }
                    });
                });
            });
        </script>
    @endsection
