@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <main class="content">
                <div class="profile-card">
                    <h2>Профиль сотрудника</h2>
                    <img src="{{ Storage::url('/photo/' . auth()->user()->photo ?? 'pupil.png') }}" alt="Фотография профиля"
                        style="max-width: 230px; max-height: 230px;" height='100%' width='100%'>
                    <p>Имя: {{ auth()->user()->name }}</p>
                    <p>Должность: {{ App\Models\Role::find(auth()->user()->role)->name }}</p>
                </div>
            </main>
        </div>
    </div>
@endsection
