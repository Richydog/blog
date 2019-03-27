<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<form action="kroko" method="post">
    @csrf

    <label for="mesto">Место</label>
    <input type="text" name="place">
    <p><select size="1" name="type_id">
            <option selected disabled>Выберите тип</option>
            <option value=1>Парк</option>
            <option value=2>летний отдых</option>
            <option value=3>рыбалка</option>

        </select></p>


   //
    <button type="submit">Отправить</button>
</form>

</body>
</html>