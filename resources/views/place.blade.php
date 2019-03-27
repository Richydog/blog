<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
 @foreach($place as $places)
     {{$places->id}}
     <a href="#"
   >Перейти {{$places->place}}.{{$places->type_id}}</a>
 <br>


    @endforeach


<select>
    @for($i=1920;$i<2017;$i++)
    <option value="{{$i}}">{{$i}}</option>
@endfor



</select>
</body>
</html>