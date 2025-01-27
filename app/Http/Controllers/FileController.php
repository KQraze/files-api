<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiValidateException;
use App\Http\Controllers\Controller;
use App\Http\Middleware\PreventRequestsDuringMaintenance;
use App\Http\Requests\ApiRequest;
use App\Http\Requests\FileRequest;
use App\Models\File;
use App\Models\User;
use App\Models\UsersFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileController extends Controller
{
    private int $userId;

    private function findFile(string $storage, string $field, mixed $value)
    {
        return match ($storage) {
            'all' => current(array_filter(UsersFile::allFiles($this->userId), fn($file) => $file[$field] === $value)),
            'disk' => current(array_filter(UsersFile::diskFiles($this->userId), fn($file) => $file[$field] === $value)),
            'shared' => current(array_filter(UsersFile::sharedFiles($this->userId), fn($file) => $file[$field] === $value)),
        };
    }

    private function getFileName($filename, $updatedFilename, $count = 1): string
    {
        $updatedFilename = $count === 1 ? $filename : $updatedFilename;
        if ($this->findFile('all', 'name', $updatedFilename)) {
            list($name, $ext) = explode('.', $filename);

            $updatedFilename = $name . ' (' . $count . ').' . $ext;

            return $this->getFileName($filename, $updatedFilename, $count + 1);
        } else {
            return $updatedFilename;
        }
    }

    public function addFile(FileRequest $request): array
    {
        $this->userId = $request->user()->id;

        $files = $request->allFiles();

        $createdFiles = [];

        if ($files) {
            foreach ($files['files'] as $file) {
                $fileName = $file->getClientOriginalName();
                $fileId = Str::random(10);

                $newFile = File::create([
                    'file_id' => $fileId,
                    'name' => $this->getFileName($fileName, null),
                    'url' => url('/') . '/api/files/' . $fileId,
                    'storage_url' => $file->store('files')
                ]);

                $createdFiles[] = $newFile;

                UsersFile::create([
                    'user_id' => $this->userId,
                    'role_id' => 1,
                    'file_id' => $newFile->id,
                ]);

            }
        }

        return array_map(function ($file) {
            return [
                'file_id' => $file->file_id,
                'name' => $file->name,
                'url' => $file->url,
                'code' => 200,
                'message' => 'Success',
            ];
        }, $createdFiles);
    }

    public function updateFile(Request $request): array
    {
        $this->userId = $request->user()->id;

        if ($this->findFile('disk', 'file_id', $request->file_id)) {
            File::query()
                ->where('file_id', $request->file_id)
                ->first()
                ->update(['name' => $this->getFileName($request->name, null)]);
            return [
                "success" => true,
                "code" => 200,
                "message" => "Renamed",
            ];
        }

        // todo add exception
        return [];
    }

    public function deleteFile(Request $request): array|null
    {
        $this->userId = $request->user()->id;

        if ($this->findFile('disk', 'file_id', $request->file_id)) {
            File::query()
                ->where('file_id', $request->file_id)
                ->first()
                ->delete();
            return [
                "success" => true,
                "code" => 200,
                "message" => "File deleted",
            ];
        }

        return null;
    }

    public function setAccess(Request $request): array
    {
        $userIdByEmail = User::query()->where('email', $request->email)->first()->id;
        $fileIdByStr = File::query()->where('file_id', $request->file_id)->first()->id;
        if (!UsersFile::query()->where([
            'user_id' => $userIdByEmail,
            'file_id' => $fileIdByStr,
        ])->exists()) {
            UsersFile::create([
                'user_id' => $userIdByEmail,
                'role_id' => 2,
                'file_id' => $fileIdByStr,
            ]);
        }

        return File::accessesUser($request->file_id)->toArray();
    }

    public function deleteAccess(Request $request): array
    {
        $userIdByEmail = User::query()->where('email', $request->email)->first()->id;
        $fileIdByStr = File::query()->where('file_id', $request->file_id)->first()->id;

        $access = UsersFile::query()->where([
            'user_id' => $userIdByEmail,
            'file_id' => $fileIdByStr,
        ]);

        if ($access->exists()) {
            $access->delete();
        }

        return File::accessesUser($request->file_id)->toArray();
    }

    public function getFile(Request $request): StreamedResponse|array
    {
        $this->userId = $request->user()->id;


        $file = $this->findFile('all', 'file_id', $request->file_id);

        if ($file) {
            return Storage::download(File::query()->where('file_id', $file['file_id'])->first()['storage_url']);
        }

        return [];
    }

    public function getDiskFiles(Request $request): array
    {
        return UsersFile::diskFiles($request->user()->id);
    }

    public function getSharedFiles(Request $request): array
    {
        return UsersFile::sharedFiles($request->user()->id);
    }
}
