<?php

namespace App\Http\Controllers;

use Response;
use App\Http\Requests\UploadFolderRequest;
use App\Http\Requests\UploadFileRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App\Services\uploadMgr;
use Illuminate\Http\Request;

class UploadController extends Controller{
  protected $manager;

  public function __construct(uploadMgr $manager){
    $this->manager = $manager;
  }

  public function index(Request $request){
    $folder = $request->get('folder');
    $data = $this->manager->folderInfo($folder);

    return view('upload.index', $data);
  }

  public function createFolder(UploadFolderRequest $req){
    $new_folder = $req->get('new_folder');
    $folder = $req->get('folder').'/'.$new_folder;
    $res = $this->manager->createDirectory($folder);

    if($res === true){
      return redirect()->back()->withSuccess("Folder '$new_folder' has been created!");
    }

    $err = $res ? : "An error occurred creating directory!";
    return redirect()->back->withErrors([$err]);

  }

  public function deleteFile(Request $req){
    $del_file = $req->get('del_file');
    $path = $req->get('folder').'/'.$del_file;

    $res = $this->manager->deleteFile($path);

    if($res === true){
      return redirect()->back()->withSuccess("File '$del_file' has been deleted!");
    }

    $err = $res ? : "An error occurred deleting file!";
    return redirect()->back->withErrors([$err]);

  }

  public function deleteFolder(Request $req){
    $del_folder = $req->get('del_folder');
    $path = $req->get('folder').'/'.$del_folder;

    $res = $this->manager->deleteDirectory($path);

    if($res === true){
      return redirect()->back()->withSuccess("File '$del_folder' has been deleted!");
    }

    $err = $res ? : "An error occurred deleting folder!";
    return redirect()->back->withErrors([$err]);

  }

  public function uploadFile(UploadFileRequest $req){
    $file = $_FILES['file'];
    $fileName = $req->get('file_name');
    $fileName = $fileName ?: $file['name'];
    $path = str_finish($req->get('folder'), '/') . $fileName;
    $content = File::get($file['tmp_name']);

    $res = $this->manager->saveFile($path, $content);

    if ($res === true) {
      return redirect()
          ->back()
          ->withSuccess("File '$fileName' uploaded.");
    }

    $err = $res ? : "An error occurred uploading file.";
    return redirect()
        ->back()
        ->withErrors([$err]);
  }

  public function downloadFile(Request $req){
    $fileName = $req->get('name');
    $path = 'uploads'.$req->get('path');
    $content = File::get($path);

    $res = $this->manager->downloadFile($path, $content);


    if ($res === true) {
      return redirect()
          ->back()
          ->withSuccess("File '$fileName' downloaded.");
    }

    $err = $res ? : "An error occurred downloading file.";
    return redirect()
        ->back()
        ->withErrors([$err]);
  }

  public function downloadResponse(Request $req){
    $file = public_path().'/uploads'. $req->get('path');
    $fileName = $req->get('name');
    $headers = array(
             'Content-Type: image/jpg',
           );

    return Response::download($file, $fileName , $headers);
  }

}
