{{R3M}}
{{$request = request()}}
{{$options = options()}}
{{$class = data.extract('options.class')}}
{{$response = Difference.Fun.Node:Data:put(
$class,
Difference.Fun.Node:Role:role_system(),
$options
)}}
{{$response|json.encode:'JSON_PRETTY_PRINT'}}

