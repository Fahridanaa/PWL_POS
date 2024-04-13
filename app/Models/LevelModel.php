<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
 * @mixin \Eloquent
 */
class LevelModel extends Model
{
	use HasFactory;

	protected $table ='m_level';

	protected $primaryKey = 'level_id';
}