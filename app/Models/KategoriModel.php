<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * 
 *
 * @property int $kategori_id
 * @property string $kategori_kode
 * @property string $kategori_nama
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BarangModel> $barang
 * @property-read int|null $barang_count
 * @method static \Illuminate\Database\Eloquent\Builder|KategoriModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KategoriModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KategoriModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|KategoriModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KategoriModel whereKategoriId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KategoriModel whereKategoriKode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KategoriModel whereKategoriNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KategoriModel whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class KategoriModel extends Model
{
	protected $table = 'm_kategori';
	protected $primaryKey = 'kategori_id';

	protected $fillable = ['kategori_kode', 'kategori_nama'];

	public function barang(): HasMany
	{
		return $this->hasMany(BarangModel::class, 'barang_id', 'barang_id');
	}
}