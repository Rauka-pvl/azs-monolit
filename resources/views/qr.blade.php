<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Monolit') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    {{-- @vite(['resources/sass/app.scss', 'resources/js/app.js', 'resources/css/app.css']) --}}

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous">
    </script>

    <!-- Подключение jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Подключение Inputmask -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/imask/7.6.0/imask.min.js"
        integrity="sha512-nTNcq3y76KV0waC+4blkE81acF83+Q0wmdNlDfpXgzpswh6FbhemEYoIV3TH+tOhadNeviCA+WPD5FEuXeF6mQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

</head>

<style>
    .center-img {
        text-align: center;
        margin: 2em 0 1em 0;
        color: white;
    }

    .center-img img {
        border-radius: 50%;
        width: 100px;
        height: 100px;
        overflow: hidden;
        object-fit: cover;
    }

    .grades {
        justify-content: space-evenly;
        display: flex;
        margin: 2em 0;
        max-width: 500px;
        margin: 0 auto;
    }

    .center {
        margin: 1em auto;
        text-align: center;
    }

    .navbar {
        position: fixed;
        top: 0;
        width: 100%;
        z-index: 1000;
    }

    body {
        padding-top: 40px;
    }

    label {
        color: white;
    }
</style>

<body style="background-color: #008286;">
    <div>
        <div id="app">
            <main>
                <nav class="navbar bg-body-tertiary">
                    <div class="container container-fluid">
                        <a class="navbar-brand" href="#">
                            <img src="{{ Storage::url('icons/star.png') }}" alt="Logo" width="30" height="24"
                                class="d-inline-block align-text-top">
                            {{ config('app.name', 'Monolit') }}
                        </a>
                    </div>
                </nav>
                <div class="container">
                    <div class="center-img">
                        <img src="{{ Storage::url('photo/' . $user->photo) }}" alt="User Photo">
                        <h1>{{ $user->name }}</h1>
                        <h4>{{ $user->azs_name }}</h4>
                        <h4>{{ $user->adress }}</h4>
                    </div>
                    <div class="grades">
                        <input type="hidden" id="id" value="">
                        <button id="gradeB1" onclick="grade(this)" name="grade" value="1" class="btn b_star">
                            <img src="{{ Storage::url('icons/star.png') }}" height="40">
                        </button>
                        <button id="gradeB2" onclick="grade(this)" name="grade" value="2" class="btn b_star">
                            <img src="{{ Storage::url('icons/star.png') }}" height="40">
                        </button>
                        <button id="gradeB3" onclick="grade(this)" name="grade" value="3" class="btn b_star">
                            <img src="{{ Storage::url('icons/star.png') }}" height="40">
                        </button>
                        <button id="gradeB4" onclick="grade(this)" name="grade" value="4" class="btn b_star">
                            <img src="{{ Storage::url('icons/star.png') }}" height="40">
                        </button>
                        <button id="gradeB5" onclick="grade(this)" name="grade" value="5" class="btn b_star">
                            <img src="{{ Storage::url('icons/star.png') }}" height="40">
                        </button>
                    </div>
                    <div style="max-width: 400px; margin: 1em auto;">
                        <label for="name">Имя:</label>
                        <input id="name" type="text" class="form-control" placeholder="Имя" required>
                    </div>
                    <div style="max-width: 400px; margin: 1em auto;">
                        <label for="phone">Телефон:</label>
                        <input id="phone" type="text" class="form-control" placeholder="+7 (___) ___ __ __"
                            required>
                    </div>
                    <div style="max-width: 400px; margin: 0 auto;">
                        <label for="comment">Комментарии:</label>
                        <textarea id="comment" cols="30" rows="5" placeholder="Оставьте отзыв!" class="form-control"></textarea>
                    </div>
                    <div style="margin: 1em auto; color: white; max-width: 400px;">
                        <label for="file">Можете прикрепить файл!</label>
                        <input type="file" name="file" id="file" class="form-control">
                    </div>
                    <div class="center">
                        <button class="btn btn-primary" onclick="review()">Оставить отзыв!</button>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>

<script>
    $(document).ready(function() {
        IMask(
            document.getElementById('phone'), {
                mask: '+{7}(000)000-00-00'
            }
        )
    });

    let grades = 0;

    function grade(gradeB) {
        grades = $(gradeB).val();

        let gradeB1 = $('#gradeB1').find('img');
        let gradeB2 = $('#gradeB2').find('img');
        let gradeB3 = $('#gradeB3').find('img');
        let gradeB4 = $('#gradeB4').find('img');
        let gradeB5 = $('#gradeB5').find('img');
        if (grades == 1) {
            $(gradeB1).attr('src', "{{ Storage::url('icons/star_black.png') }}");

            $(gradeB2).attr('src', "{{ Storage::url('icons/star.png') }}");
            $(gradeB3).attr('src', "{{ Storage::url('icons/star.png') }}");
            $(gradeB4).attr('src', "{{ Storage::url('icons/star.png') }}");
            $(gradeB5).attr('src', "{{ Storage::url('icons/star.png') }}");
        } else if (grades == 2) {
            $(gradeB1).attr('src', "{{ Storage::url('icons/star_black.png') }}");
            $(gradeB2).attr('src', "{{ Storage::url('icons/star_black.png') }}");

            $(gradeB3).attr('src', "{{ Storage::url('icons/star.png') }}");
            $(gradeB4).attr('src', "{{ Storage::url('icons/star.png') }}");
            $(gradeB5).attr('src', "{{ Storage::url('icons/star.png') }}");
        } else if (grades == 3) {
            $(gradeB1).attr('src', "{{ Storage::url('icons/star_black.png') }}");
            $(gradeB2).attr('src', "{{ Storage::url('icons/star_black.png') }}");
            $(gradeB3).attr('src', "{{ Storage::url('icons/star_black.png') }}");

            $(gradeB4).attr('src', "{{ Storage::url('icons/star.png') }}");
            $(gradeB5).attr('src', "{{ Storage::url('icons/star.png') }}");
        } else if (grades == 4) {
            $(gradeB1).attr('src', "{{ Storage::url('icons/star_black.png') }}");
            $(gradeB2).attr('src', "{{ Storage::url('icons/star_black.png') }}");
            $(gradeB3).attr('src', "{{ Storage::url('icons/star_black.png') }}");
            $(gradeB4).attr('src', "{{ Storage::url('icons/star_black.png') }}");

            $(gradeB5).attr('src', "{{ Storage::url('icons/star.png') }}");
        } else if (grades == 5) {
            $(gradeB1).attr('src', "{{ Storage::url('icons/star_black.png') }}");
            $(gradeB2).attr('src', "{{ Storage::url('icons/star_black.png') }}");
            $(gradeB3).attr('src', "{{ Storage::url('icons/star_black.png') }}");
            $(gradeB4).attr('src', "{{ Storage::url('icons/star_black.png') }}");
            $(gradeB5).attr('src', "{{ Storage::url('icons/star_black.png') }}");
        }
    }

    function review() {
        let name = $("#name").val();
        let phone = $("#phone").val();
        let comment = $("#comment").val();
        let file = $("#file").prop('files')[0];
        let formData = new FormData();
        formData.append('azs_id', {{ $user->azs_id }});
        formData.append('user_id', {{ $user->user_id }});
        formData.append('grade', grades);
        formData.append('name', name);
        formData.append('phone', phone);
        formData.append('comment', comment);
        formData.append('file', file);

        if (name.trim() === '' || phone.trim() === '') {
            showNotification('Пожалуйста, заполните обязательные поля: Имя и Телефон');
            return;
        }

        if (grades > 0) {
            $.ajax({
                url: '/api/review',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response == true) {
                        var overlay = $('<div></div>').css({
                            'position': 'fixed',
                            'top': '0',
                            'left': '0',
                            'width': '100%',
                            'height': '100%',
                            'background': 'rgba(0, 0, 0, 0.5)',
                            'display': 'flex',
                            'align-items': 'center',
                            'justify-content': 'center',
                            'z-index': '9999'
                        });
                        var checkmark = $('<img>').attr('src', '{{ Storage::url('icons/chekmark.gif') }}')
                            .css({
                                'width': '100px',
                                'height': '100px'
                            });
                        var message = $('<div></div>').text('Спасибо за оставленный отзыв!').css({
                            'color': 'white',
                            'font-size': '24px'
                        });
                        overlay.append(checkmark, message);
                        $('body').append(overlay);

                        setTimeout(() => {
                            checkmark.attr('src', '{{ Storage::url('icons/chekmark.png') }}');
                        }, 1000);
                    }
                },
                error: function(error) {
                    console.error("Произошла ошибка: " + error);
                }
            });
        } else {
            showNotification('Нужно обязательно поставить оценку!');
        }
    }

    function showNotification(message) {
        var notification = $('<div></div>').text(message).css({
            'position': 'fixed',
            'top': '0',
            'left': '50%',
            'transform': 'translateX(-50%)',
            'background': 'rgba(0, 0, 0, 0.8)',
            'color': 'white',
            'padding': '10px 20px',
            'border-radius': '5px',
            'z-index': '9999'
        }).hide();

        $('body').append(notification);
        notification.slideDown();

        setTimeout(function() {
            notification.slideUp(function() {
                notification.remove();
            });
        }, 3000);
    }
</script>

</html>
