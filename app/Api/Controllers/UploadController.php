<?php

namespace App\Api\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Validator;

class UploadController extends Controller
{
    protected $uploadFolder;
    protected $maxFileSize;

    public function __construct()
    {
        $this->uploadFolder = 'uploads';
        // 10240 kilobytes = 10 megabytes
        $this->maxFileSize  = 10240;
    }

    public function uploadImage(Request $request)
    {
        $files = $this->getFiles($request);

        $allowedMimes = 'jpg,jpeg,bmp,png';

        $rule = sprintf('required|mimes:%s|max:%d', $allowedMimes, $this->maxFileSize);

        $rules = [];

        foreach ($files as $fieldName => $file) {
             $rules[$fieldName] = $rule;
        }

        $validator = Validator::make($files, $rules);

        if ($validator->fails()) {
            throw new BadRequestHttpException('Invalid data');
        }

        $paths = [];

        foreach ($files as $fieldName => $file) {
            $path = $this->moveFile($file);

            if ($path !== false) {
                $paths[$fieldName] = $path;
            }
        }

        return ['files' => $paths];
    }

    public function moveFile($file, $changeName = true)
    {
        $extension = $file->getClientOriginalExtension();
        $filename  = $changeName ? str_random(16).'.'.$extension : $file->getClientOriginalName();
        $folder    = $this->getUploadFolder();

        try {
            $file->move($folder, $filename);
        } catch (FileException $e) {
            return false;
        }

        $path = $folder.'/'.$filename;

        return $path;
    }

    public function getUploadFolder()
    {
        return $this->uploadFolder.'/'.date('Y/m/d');
    }

    public function getFiles(Request $request)
    {
        $allowedCount = 5;
        $fieldPrefix  = 'file';

        $files = [];

        for ($i=1; $i <= $allowedCount; $i++) { 
            $fieldName = $fieldPrefix.$i;

            if ($request->hasFile($fieldName)) {
                $files[$fieldName] = $request->file($fieldName);
            }
        }

        return $files;
    }
}
