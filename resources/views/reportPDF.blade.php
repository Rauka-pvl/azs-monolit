<!DOCTYPE html>
<html>

<head>
    <title>Отчет</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <h1>Отчет с {{ $startDate }} по {{ $endDate }}</h1>
    @foreach ($azs as $key => $users)
        <h1>{{ $key }}</h1>
        <table>
            <thead>
                <tr>
                    <th>Имя</th>
                    <th>Должность</th>
                    <th>Средняя оценка за период</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ App\Models\Role::find($user->role)->name }}</td>
                        <td>{{ $user->avg_grade }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach
</body>

</html>
