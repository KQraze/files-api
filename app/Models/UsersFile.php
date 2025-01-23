<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UsersFile extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'role_id', 'file_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class, 'file_id');
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public static function sharedFiles(int $user_id)
    {
        return self::query()
            ->where('user_id', $user_id)
            ->where('role_id', 2)
            ->with(['file'])
            ->get()
            ->map(fn ($file) => [
                "file_id" => $file->file->file_id,
                "code" => 200,
                "name" => $file->file->name,
                "url" => $file->file->url,
            ])
            ->toArray();
    }

    public static function diskFiles(int $user_id): array
    {
        return self::query()
            ->where('user_id', $user_id)
            ->where('role_id', 1)
            ->with(['file'])
            ->get()
            ->map(fn ($file) => [
                "file_id" => $file->file->file_id,
                "code" => 200,
                "name" => $file->file->name,
                "url" => $file->file->url,
                "accesses" => self::query()
                    ->where('file_id', $file->file_id)
                    ->with(['user', 'role'])
                    ->get()
                    ->map(fn ($fileChildren) => [
                        "fullname" => $fileChildren->user->last_name . ' ' . $fileChildren->user->first_name,
                        "email" => $fileChildren->user->email,
                        "type" => $fileChildren->role->type
                    ])
                    ->toArray()
                ,
            ])
            ->toArray();
    }
}
