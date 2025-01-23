<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiValidateException;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApiRequest;
use App\Http\Requests\FileRequest;
use App\Models\File;
use App\Models\User;
use App\Models\UsersFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class FileController extends Controller
{
    public function addFile(FileRequest $request): array
    {
        dd(UsersFile::diskFiles($request->user()->id));
//        dd(User::query()->where('id', $request->user()->id)
//            ->with('files.file')
//            ->get()
//        );

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
//        $files = $request->allFiles();
//
//        $createdFiles = [];
//
//        if ($files) {
//            foreach ($files['files'] as $file) {
//                $filePath = $file->store('files');
//                $fileName = $file->getClientOriginalName();
//
//                $createdFiles[] = File::create([
//                    'file_id' => Str::random(10),
//                    'name' => $fileName,
//                    'url' => url('/') . '/' . $fileName,
//                ]);
//            }
//        }

//        return $createdFiles;
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

    public function getDiskFiles(Request $request): array
    {
        return UsersFile::diskFiles($request->user()->id);
    }

    public function getSharedFiles(ApiRequest $request)
    {
        return UsersFile::sharedFiles($request->user()->id);
    }
}
