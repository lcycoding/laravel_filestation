<?php

namespace App\Http\Requests;

class UploadFolderRequest extends Request{

  public function authorize(){
    return true;
  }

  public function rules(){
    return [
      'folder' => 'required',
      'new_folder' => 'required',
    ];
  }

}
