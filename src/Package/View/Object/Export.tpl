{{R3M}}
{{$options = options()}}
{{while(is.empty($options.class))}}
{{$options.class = terminal.readline('Class: ')}}
{{/while}}
{{$class = controller.name($options.class)}}
{{if(is.empty($options.url))}}
{{$options.url = config('project.dir.mount') +
'Backup' +
'/' +
'Package' +
'/' +
'Difference.Fun.Node' +
'/' +
$class +
'/' +
date('Y-m-d-H-i-s') +
'/' +
$class +
config('extension.json')}}
{{/if}}
{{if(!is.empty($options.compression))}}
{{$options.compression = [
'algorithm' => 'gz',
'level' => 9
]}}
{{else}}
{{$options.compression = false}}
{{/if}}
{{if(is.empty($options.limit))}}
{{$options.limit = 1000}}
{{/if}}
{{if(!is.empty($options.page))}}
{{Difference.Fun.Node:Data:export(
$class,
Difference.Fun.Node:Role:role.system(),
[
'url' => $options.url,
'compression' => $options.compression,
'page' => $options.page,
'limit' => $options.limit
])}}
{{else}}
{{Difference.Fun.Node:Data:export(
$class,
Difference.Fun.Node:Role:role.system(),
[
'url' => $options.url,
'compression' => $options.compression
])}}
{{/if}}