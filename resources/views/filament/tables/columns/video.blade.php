@php
if(isset($getRecord)) {
$record = $getRecord();
}

$media = $record->getMedia('attachedvideos');

$videos = $media->map(fn($path)=>$path->getUrl())->all();

@endphp

@foreach($videos as $video)
<video controls>
    <source src="{{ $video }}">
    Your browser does not support the video tag.
</video>
@endforeach