@php
if(isset($getRecord)) {
$record = $getRecord();
}

$media = $record->getMedia('attachedfindings');
$overlap = 0;
$height= "100%";
$width= "100%";

$images = $media->map(fn($path)=>$path->getUrl())->all();
$imgCount = count($images);


$columns = ($imgCount <= 4)? $imgCount : 4; @endphp <div class="container grid grid-cols-{{$columns}} gap-2 mx-auto">


    @foreach($images as $image)
    <div class="w-full rounded-lg border bg-black overflow-hidden">
        <img src="{{$image}}" alt="image" class="w-full  flex-grow p-1 rounded-lg">
    </div>
    @endforeach
    </div>