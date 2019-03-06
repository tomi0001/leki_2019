@foreach ($list as $description)
<div class="list_description">
    {{$description->description}}<br>
    <b>{{$description->date}}</b>
    
</div><br>
    
@endforeach