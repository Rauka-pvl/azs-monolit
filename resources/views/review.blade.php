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
                Обратная свзяь!
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="list-group">
                            @foreach ($review as $r)
                                <div class="list-group-item list-group-item-action">
                                    <div class="d-flex">
                                        <div class="w-100">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h5 class="mb-1">Имя: {{ $r->name }}</h5>
                                                <small>АЗС: @if (App\Models\azs::find($r->azs_id)->delete == 0)
                                                        {{ App\Models\azs::find($r->azs_id)->name }}
                                                    @else
                                                        {{ App\Models\azs::find($r->azs_id)->name }} (Удалён)
                                                    @endif
                                                </small>
                                            </div>
                                            <div class="d-flex w-100 justify-content-between">
                                                <p class="mb-1">Номер телефона: {{ $r->phone }}</p>
                                                <div>
                                                    <p class="mb-1">
                                                        Оценка: {{ $r->grade }}
                                                        <img src="{{ Storage::url('icons/star_yellow.png') }}"
                                                            alt="Рейтинг" class="grade_img">
                                                    </p>
                                                </div>
                                            </div>
                                            <small>Дата: {{ $r->created_at }}</small>
                                        </div>
                                    </div>
                                    <div class="w-100">
                                        <p>Комментарии:: {{ $r->comment }}</p>
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
                    {{ $review->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
