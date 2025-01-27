<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class File extends Model {
    use HasFactory;

    protected $fillable = ['file_id', 'name', 'url', 'storage_url'];

    public function accesses(): hasMany {
        return $this->hasMany(UsersFile::class, 'file_id');
    }

    public static function accessesUser($file_id)
    {
        return self::query()
            ->where('file_id', $file_id)
            ->with(['accesses.user', 'accesses.role'])
            ->get()
            ->map(fn ($file) => [
                ...$file->accesses->map(fn ($access) => [
                    'fullname' => $access->user->first_name . ' ' . $access->user->last_name,
                    'email' => $access->user->email,
                    'code' => 200,
                    'type' => $access->role->type
                ])
            ]);
    }
}
