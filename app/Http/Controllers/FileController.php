<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiValidateException;
use App\Http\Controllers\Controller;
use App\Http\Requests\FileRequest;
use App\Models\File;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FileController extends Controller
{
    public function addFile(FileRequest $request)
    {
//        print_r(User::files()->get());
//        function getFileName($filename, $count = 0)
//        {
//            if (current(
//                array_filter(User::diskFiles(), fn($file) => $file['name'] === $filename))
//            ) {
//                return getFileName($filename, $count + 1);
//            } else {
//
//            }
//        }
//
        $files = $request->allFiles();

        $createdFiles = [];

        if ($files) {
            foreach ($files['files'] as $key => $file) {
                $filePath = $file->store('files');
                $fileName = $file->getClientOriginalName();

                $createdFiles[] = File::create([
                    'id' => Str::random(10),
                    'name' => $fileName,
                    'url' => url('/') . '/' . $filePath,
                ]);
            }
        }

        return $createdFiles;
    }

    public function updateFile()
    {

    }

    public function deleteFile()
    {

    }

    public function getFile()
    {

    }

    public function getDiskFiles()
    {

    }

    public function getSharedFiles()
    {

    }
}
