<?php

namespace App\Http\Requests;

class UploadFileRequest extends Request{

  public function authorize(){
    return true;
  }

  public function rules(){
    return [
      'file' => 'required',
      'folder' => 'required',
    ];
  }

}
