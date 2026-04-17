<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Stadium extends Model
{
 use HasFactory;
 protected $table = 'stadiums';
 protected $fillable = [
 'name',
 'city',
 'country',
 'latitude',
 'longitude',
 'capacity',
 'photo_url',
 'team_id',
 'team_name',
 'year_built',
 ];
 protected $casts = [
 'latitude' => 'float',
 'longitude' => 'float',
 'capacity' => 'integer',
 'team_id' => 'integer',
 'year_built' => 'integer',
 ];
 public function usersWhoFavorited()
 {
 return $this->belongsToMany(User::class, 'favorites', 'stadium_id', 'user_id')
 ->withTimestamps();
 }
}