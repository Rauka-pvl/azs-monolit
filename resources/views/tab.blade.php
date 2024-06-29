@extends('layouts.appTab')

@section('content')
    <style>
        .grades {
            justify-content: space-evenly;
            display: flex;
            margin: 2em 0;
        }

        .pin-cal button {
            font-size: 3em;
        }

        .center {
            text-align: center;
        }

        .b_star:focus {
            outline: none;
            box-shadow: none;
            border: 0;
        }

        #video {
            display: none;
        }

        .alert {
            position: fixed;
            top: 25%;
            left: 30.5%;
            /* transform: translate(-50%, -10%); */
            width: 400px;
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
            padding: 20px;
            border-radius: 5px;
            text-align: center;
            font-size: 20px;
            font-weight: bold;
        }

        .alert-button {
            display: inline-block;
            background-color: #4CAF50;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 20px;
            font-size: 16px;
            cursor: pointer;
        }
    </style>
    <div class="justify-content-center d-none" id="card">{{-- d-none --}}
        <div class="col-md d-flex">
            <div style="margin: 2em auto; width: 45%; border: 0;">
                <div class="card" style="margin-bottom: 0.5em;">
                    <div class="center" style="margin: 0.5em auto">
                        <div class="d-flex" style="align-items: center;">
                            <img src="{{ Storage::url('icons/qr.gif') }}" width="30" height="30"
                                class="d-inline-block align-text-top">
                            <div>
                                <h4>Сіз өз пікіріңізді қалдыра аласыз!</h4>
                                <h4>Вы можете оставить отзыв!</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card" style="margin: 1em auto; max-width: 330px;">
                    <div style="margin: 1em auto;">
                        <div id="qrcode"></div>
                    </div>
                </div>
                <div class="card" style="margin-top: 0.5em;">
                    <div class="center" style="margin: 0.5em auto">
                        <div class="d-flex" style="align-items: center;">
                            <div>
                                <h4>QR-кодты сканерлеңіз!</h4>
                                <h4>Отсканируй QR-код!</h4>
                            </div>
                            <img src="{{ Storage::url('icons/qr.gif') }}" width="30" height="30"
                                class="d-inline-block align-text-top">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card" style="margin: 2em auto; width: 45%; border: 0; ">
                <div style="text-align: center;">
                    <div class="center">
                        <h1 id="name" style="margin-bottom: 0; padding-bottom: 0;"></h1>
                    </div>
                    <div class="center" style="margin-top: 0.5em;">
                        <h4 id="adress" style="margin-bottom: 0;"></h4>
                    </div>
                    <div class="center" style="margin-top: 0.5em;">
                        <h4 id="operator" style="margin-bottom: 0;"></h4>
                    </div>
                    <div class="center" style="margin-top: 0.5em;">
                        <img id="photo" src="" height="100%" width="100%"
                            style="max-height: 230px; max-width: 230px;">
                    </div>
                </div>
                <div class="grades">
                    <input type="hidden" id="id" value="">
                    <button id="gradeB1" onclick="gradeKab(this)" name="grade" value="1" class="btn b_star">
                        <img src="{{ Storage::url('icons/star.png') }}" height="40">
                    </button>
                    <button id="gradeB2" onclick="gradeKab(this)" name="grade" value="2" class="btn b_star">
                        <img src="{{ Storage::url('icons/star.png') }}" height="40">
                    </button>
                    <button id="gradeB3" onclick="gradeKab(this)" name="grade" value="3" class="btn b_star">
                        <img src="{{ Storage::url('icons/star.png') }}" height="40">
                    </button>
                    <button id="gradeB4" onclick="gradeKab(this)" name="grade" value="4" class="btn b_star">
                        <img src="{{ Storage::url('icons/star.png') }}" height="40">
                    </button>
                    <button id="gradeB5" onclick="gradeKab(this)" name="grade" value="5" class="btn b_star">
                        <img src="{{ Storage::url('icons/star.png') }}" height="40">
                    </button>
                </div>
                <div class="center">
                    <h4>Қызмет көрсету сапасын бағалаңыз</h4>
                    <h4>Оцените качество обслуживания</h4>
                </div>
            </div>
        </div>
        <div class="col-md d-flex">
            <div style="margin: 0 auto; font-size: 10px;">
                При оставлений оценки вы соглашаетесь с фотофиксацией.
            </div>
        </div>
    </div>
    <div class="justify-content-center" id="auth">
        <div class="card" style="margin: 1em auto; width: 45%;">
            <div class="card-header">{{ __('Авторизация') }}</div>

            <div class="card-body">
                <div>
                    <label id='error' class="center" style="color: red; display: none;"></label>
                </div>
                <div class="row mb-3">
                    <label class="col-md-4 col-form-label text-md-end"
                        style="font-weight: bold; font-size: 2em;">{{ __('PIN - код') }}</label>
                    <div class="col-md-6">
                        <input disabled id="input_pin" type="text" class="form-control" required maxlength="6"
                            minlength="6" style="font-size: 2em;">
                    </div>
                    <div class="center pin-cal" id="pin-cal" style="text-align: center;">
                        <div>
                            <button class="btn pin_num">1</button>
                            <button class="btn pin_num">2</button>
                            <button class="btn pin_num">3</button>
                        </div>
                        <div>
                            <button class="btn pin_num">4</button>
                            <button class="btn pin_num">5</button>
                            <button class="btn pin_num">6</button>
                        </div>
                        <div>
                            <button class="btn pin_num">7</button>
                            <button class="btn pin_num">8</button>
                            <button class="btn pin_num">9</button>
                        </div>
                        <div>
                            <button class="btn" style="color: white;">0</button>
                            <button class="btn pin_num">0</button>
                            <button id="deleteButton" style="font-size: 1em;" class="btn">
                                <img src="{{ Storage::url('icons/del.png') }}" width="25" height="25">
                            </button>
                        </div>
                    </div>
                </div>
                <div class="row mb-0">
                    <div class="center">
                        <button onclick="login()" class="btn btn-primary">
                            {{ __('Авторизация') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <video id="video" autoplay></video>
    <canvas id="canvas" style="display:none;"></canvas>
    <script>
        let user;

        $(document).ready(function() {
            startCamera();

            let currentTime = new Date();
            let hours = currentTime.getHours();

            user = getDataFromLocalStorage();
            if (user) {
                updateUI(getDataFromLocalStorage());
            }
        });

        function login() {
            let code = $('#input_pin').val();

            $.ajax({
                type: 'post',
                url: '/api/loginTab',
                data: {
                    code: code,
                    zone: "{{ $zone }}"
                },
                success: function(response) {
                    if (response) {
                        saveDataToLocalStorage(response);
                        user = response;

                        updateUI(response);
                        $('#input_pin').val('');
                    }
                },
                error: function(xhr, status, error) {
                    if (xhr.status == 401) {
                        $("#error").text("Неправильный код");
                        $("#error").css('display', 'block');
                    }
                    if (xhr.status == 402) {
                        $("#error").text("Вы не можете оценить себя");
                        $("#error").css('display', 'block');
                    }
                    setTimeout(() => {
                        $("#error").css('display', 'none');
                    }, 3000)
                }
            });
        }


        function updateUI(response) {
            $("#name").text(response['azs_name']);
            $("#adress").text(response['adress']);
            $("#operator").text(response['name']);
            if (response['photo'] != null)
                $("#photo").attr("src", '{{ Storage::url('photo/') }}' + response['photo']);
            else
                $("#photo").attr("src", '{{ Storage::url('photo/1.jpg') }}');
            $("#auth").addClass('d-none');
            $("#card").removeClass('d-none');
            qr(response['user_id']);
        }


        const currentUrl = window.location.href;
        const domain = new URL(currentUrl).origin;


        // QR-code ----------------------------------------------------
        // Создайте новый экземпляр QRCode
        // let qrcode = new QRCode(document.getElementById("qrcode"), {
        //     text: text,
        //     width: 400,
        //     height: 400,
        // });

        function qr(user_id) {
            let text = domain + "/qr/" + user_id;
            let qr = new QRious({
                value: text,
                size: 300
            });
            let qrCanvas = qr.canvas;
            let finalImage = document.createElement('img');
            mergeImages([{
                    src: qrCanvas.toDataURL(),
                    x: 0,
                    y: 0
                },
                {
                    src: '{{ Storage::url('icons/star.gif') }}',
                    x: 125,
                    y: 125,
                    width: 300,
                    height: 300,
                }
            ]).then(b64 => {
                finalImage.src = b64;
                document.getElementById('qrcode').appendChild(finalImage);
            });
        }

        // -----------------------------------------------------------

        function gradeKab(gradeB) {
            let grade = gradeB.value;

            let gradeB1 = $('#gradeB1').find('img');
            let gradeB2 = $('#gradeB2').find('img');
            let gradeB3 = $('#gradeB3').find('img');
            let gradeB4 = $('#gradeB4').find('img');
            let gradeB5 = $('#gradeB5').find('img');
            if (grade == 1) {
                $(gradeB1).attr('src', "{{ Storage::url('icons/star.gif') }}");
                setTimeout(() => {
                    $(gradeB1).attr('src', "{{ Storage::url('icons/star.png') }}");
                }, 1000);
            } else if (grade == 2) {
                $(gradeB1).attr('src', "{{ Storage::url('icons/star.gif') }}");
                $(gradeB2).attr('src', "{{ Storage::url('icons/star.gif') }}");
                setTimeout(() => {
                    $(gradeB1).attr('src', "{{ Storage::url('icons/star.png') }}");
                    $(gradeB2).attr('src', "{{ Storage::url('icons/star.png') }}");
                }, 1000);
            } else if (grade == 3) {
                $(gradeB1).attr('src', "{{ Storage::url('icons/star.gif') }}");
                $(gradeB2).attr('src', "{{ Storage::url('icons/star.gif') }}");
                $(gradeB3).attr('src', "{{ Storage::url('icons/star.gif') }}");
                setTimeout(() => {
                    $(gradeB1).attr('src', "{{ Storage::url('icons/star.png') }}");
                    $(gradeB2).attr('src', "{{ Storage::url('icons/star.png') }}");
                    $(gradeB3).attr('src', "{{ Storage::url('icons/star.png') }}");
                }, 1000);
            } else if (grade == 4) {
                $(gradeB1).attr('src', "{{ Storage::url('icons/star.gif') }}");
                $(gradeB2).attr('src', "{{ Storage::url('icons/star.gif') }}");
                $(gradeB3).attr('src', "{{ Storage::url('icons/star.gif') }}");
                $(gradeB4).attr('src', "{{ Storage::url('icons/star.gif') }}");
                setTimeout(() => {
                    $(gradeB1).attr('src', "{{ Storage::url('icons/star.png') }}");
                    $(gradeB2).attr('src', "{{ Storage::url('icons/star.png') }}");
                    $(gradeB3).attr('src', "{{ Storage::url('icons/star.png') }}");
                    $(gradeB4).attr('src', "{{ Storage::url('icons/star.png') }}");
                }, 1000);
            } else if (grade == 5) {
                $(gradeB1).attr('src', "{{ Storage::url('icons/star.gif') }}");
                $(gradeB2).attr('src', "{{ Storage::url('icons/star.gif') }}");
                $(gradeB3).attr('src', "{{ Storage::url('icons/star.gif') }}");
                $(gradeB4).attr('src', "{{ Storage::url('icons/star.gif') }}");
                $(gradeB5).attr('src', "{{ Storage::url('icons/star.gif') }}");
                setTimeout(() => {
                    $(gradeB1).attr('src', "{{ Storage::url('icons/star.png') }}");
                    $(gradeB2).attr('src', "{{ Storage::url('icons/star.png') }}");
                    $(gradeB3).attr('src', "{{ Storage::url('icons/star.png') }}");
                    $(gradeB4).attr('src', "{{ Storage::url('icons/star.png') }}");
                    $(gradeB5).attr('src', "{{ Storage::url('icons/star.png') }}");
                }, 1000);
            }

            capturePhoto().then(photo => {
                $.post('/api/grade', {
                        azs_id: user['azs_id'],
                        user_id: user['user_id'],
                        grade: grade,
                        photo: photo
                    })
                    .then(response => {
                        console.log(response);
                        showAlertGrade();
                    })
                    .catch(error => {
                        console.error("Произошла ошибка: " + error);
                    });
            });
        }

        $('.pin_num').on('click', function() {
            let value = $(this).text();
            let currentValue = $('#input_pin').val();

            if (currentValue.length < 6) {
                $('#input_pin').val(currentValue + value);
            }
        });
        $('#deleteButton').on('click', function() {
            var currentValue = $('#input_pin').val();
            var newValue = currentValue.slice(0, -1);
            $('#input_pin').val(newValue);
        });
        async function startCamera() {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: 'user'
                    }
                });
                const video = document.getElementById('video');
                video.srcObject = stream;
                video.play();
            } catch (error) {
                console.error('Ошибка доступа к камере', error);
            }
        }

        function capturePhoto() {
            return new Promise((resolve, reject) => {
                const video = document.getElementById('video');
                const canvas = document.getElementById('canvas');
                const context = canvas.getContext('2d');
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                context.drawImage(video, 0, 0, canvas.width, canvas.height);

                // Проверяем, что метод canvas.toBlob поддерживается
                if (canvas.toBlob) {
                    canvas.toBlob(blob => {
                        if (blob) {
                            const reader = new FileReader();
                            reader.onloadend = () => {
                                resolve(reader.result);
                            };
                            reader.onerror = reject;
                            reader.readAsDataURL(blob);
                        } else {
                            reject(new Error('Ошибка создания Blob'));
                        }
                    }, 'image/jpeg');
                } else {
                    reject(new Error('Метод canvas.toBlob не поддерживается'));
                }
            });
        }

        function showAlertGrade() {
            let alert1 = document.createElement('div');
            alert1.classList.add('alert');

            // Создаем новый элемент p для текста alert
            let aletText = document.createElement('p');
            aletText.style.backgroundColor = 'white';
            aletText.innerText = 'Сіз сәтті бағаладыңыз!';

            let alertText1 = document.createElement('p');
            alertText1.style.backgroundColor = 'white';
            alertText1.innerText = 'Вы успешно оценили!';

            // Создаем новый элемент a для кнопки "OK"
            let alertButton1 = document.createElement('a');
            alertButton1.classList.add('alert-button');
            alertButton1.innerText = 'OK';

            // Добавляем обработчик событий на кнопку "OK", чтобы скрыть alert при нажатии
            alertButton1.addEventListener('click', () => {
                alert1.remove();
            });

            // Добавляем alert на страницу
            alert1.appendChild(aletText);
            alert1.appendChild(alertText1);
            alert1.appendChild(alertButton1);
            document.body.appendChild(alert1);

            let gradeB1 = $('#gradeB1').prop('disabled', true);
            let gradeB2 = $('#gradeB2').prop('disabled', true);
            let gradeB3 = $('#gradeB3').prop('disabled', true);
            let gradeB4 = $('#gradeB4').prop('disabled', true);
            let gradeB5 = $('#gradeB5').prop('disabled', true);

            setTimeout(() => {
                gradeB1 = $('#gradeB1').prop('disabled', false);
                gradeB2 = $('#gradeB2').prop('disabled', false);
                gradeB3 = $('#gradeB3').prop('disabled', false);
                gradeB4 = $('#gradeB4').prop('disabled', false);
                gradeB5 = $('#gradeB5').prop('disabled', false);
            }, 1000);

            setTimeout(() => {
                alert1.remove();
            }, 2000);
        }

        // Функция для сохранения данных в localStorage на сутки
        function saveDataToLocalStorage(data) {
            // Получаем текущую дату и время
            let currentTime = new Date();
            let currentHours = currentTime.getHours();
            let currentMinutes = currentTime.getMinutes();

            let expirationDate = new Date();
            expirationDate.setDate(expirationDate.getDate() + 1);
            expirationDate.setHours(8);
            expirationDate.setMinutes(59);
            expirationDate.setSeconds(0);

            // Создаем объект для хранения данных и метаданных
            let storageData = {
                data: data,
                expiration: expirationDate.getTime(), // Время истечения срока годности в миллисекундах
                savedAt: `${currentHours}:${currentMinutes}` // Время сохранения
            };

            // Сериализуем объект в строку JSON и сохраняем в localStorage
            localStorage.setItem('savedData', JSON.stringify(storageData));
        }

        // Функция для получения данных из localStorage с проверкой срока годности
        function getDataFromLocalStorage() {
            // Получаем сохраненные данные из localStorage
            let storedData = localStorage.getItem('savedData');

            if (storedData) {
                // Парсим JSON
                let parsedData = JSON.parse(storedData);

                // Получаем текущее время
                let currentTime = new Date().getTime();

                // Проверяем, не истек ли срок годности данных
                if (currentTime <= parsedData.expiration) {
                    // Возвращаем данные
                    return parsedData.data;
                } else {
                    // Удаляем устаревшие данные из localStorage
                    localStorage.removeItem('savedData');
                    return null;
                }
            } else {
                return null;
            }
        }

        function reloadAtNine() {
            let now = new Date();
            let hours = now.getHours();
            let minutes = now.getMinutes();

            if (hours === 9 && minutes === 0) {
                location.reload();
            }
        }

        setInterval(function() {
            getDataFromLocalStorage();
            reloadAtNine();
        }, 30000);
    </script>
@endsection
