<?php

function cus_filesize($bytes, $decimals = 2 ){
  $size = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
  $factor = floor((strlen($bytes) - 1 ) / 3);

  return sprintf("%.{$decimals}f", $bytes/ pow(1024, $factor)).
         @$size[$factor];
}

function is_img($mimeType)
{
    return starts_with($mimeType, 'image/');
}
