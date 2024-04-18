<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * 
 *
 * @property int $level_id
 * @property string $level_kode
 * @property string $level_nama
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|LevelModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LevelModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LevelModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|LevelModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LevelModel whereLevelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LevelModel whereLevelKode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LevelModel whereLevelNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LevelModel whereUpdatedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $user
 * @property-read int|null $user_count
 * @mixin \Eloquent
 */
class LevelModel extends Model
{
	use HasFactory;

	protected $table ='m_level';
	protected $primaryKey = 'level_id';

	protected $fillable = ['level_id', 'level_kode', 'level_nama'];

	public function user(): HasMany
	{
		return $this->hasMany(User::class, 'level_id', 'level_id');
	}
}