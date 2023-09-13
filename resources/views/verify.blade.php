<?php

$styles = [
    'center' => 'text-align: center;
        width: 500px;
        margin: auto;
        background-color: #efefef;
        border: 1px solid #cccccc;',
    'image' => 'width: 500px;
        height: 800px;',
    'font' => 'font-family: IRANSans, sans-serif'
];
?>


<div style="{{ $styles['center'] }}">
    <p style="{{ $styles['font'] }}"> کد تایید شما برای فروشگاه مال استور:</p>
    <p style="{{ $styles['font'] }}">{{$code}}</p>
</div>
