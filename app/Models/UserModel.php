<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 *
 *
 * @property int $user_id
 * @property int $level_id
 * @property string $username
 * @property string $name
 * @property string $password
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|UserModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserModel whereLevelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserModel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserModel wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserModel whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserModel whereUsername($value)
 * @property-read \App\Models\LevelModel $level
 * @mixin \Eloquent
 */
class UserModel extends Authenticatable implements JWTSubject
{
	use HasFactory;

	protected $table = 'm_user';
	public $timestamps = false;
	protected $primaryKey = 'user_id';

	protected $fillable = ['user_id', 'level_id', 'username', 'name', 'password', 'image'];

	public function level(): BelongsTo
	{
		return $this->belongsTo(LevelModel::class, 'level_id', 'level_id');
	}

	public function getJWTIdentifier()
	{
		return $this->getKey();
	}

	public function getJWTCustomClaims(): array
	{
		return [];
	}

	protected function image() : Attribute
	{
		return Attribute::make(
			get: fn ($image) => url('/storage/posts/'. $image),
		);
	}
}