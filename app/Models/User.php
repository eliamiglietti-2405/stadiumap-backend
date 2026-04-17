<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
class User extends Authenticatable
{
 use HasApiTokens, HasFactory, Notifiable;
 protected $table = 'users';
 protected $fillable = [
    'uid',
    'email',
    'password_hash',
    'display_name',
    'photo_url',
    'is_admin',
    'last_login',
 ];
 protected $hidden = [
    'password_hash',
    'remember_token',
 ];
 protected $casts = [
    'is_admin' => 'boolean',
    'last_login' => 'datetime',
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
 ];
 public function favorites()
 {
    return $this->belongsToMany(Stadium::class, 'favorites', 'user_id',
    'stadium_id')
    ->withTimestamps();
 }
}