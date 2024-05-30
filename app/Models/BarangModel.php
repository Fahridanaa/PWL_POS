<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * 
 *
 * @method static \Illuminate\Database\Eloquent\Builder|BarangModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BarangModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BarangModel query()
 * @property int $barang_id
 * @property int $kategori_id
 * @property string $barang_kode
 * @property string $barang_name
 * @property int $harga_beli
 * @property int $harga_jual
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\KategoriModel $kategori
 * @method static \Illuminate\Database\Eloquent\Builder|BarangModel whereBarangId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BarangModel whereBarangKode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BarangModel whereBarangName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BarangModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BarangModel whereHargaBeli($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BarangModel whereHargaJual($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BarangModel whereKategoriId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BarangModel whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class BarangModel extends Model
{
	use HasFactory;

	protected $table = 'm_barang';
	protected $primaryKey = 'barang_id';
	
	protected $fillable = ['kategori_id', 'barang_kode', 'barang_name', 'harga_beli', 'harga_jual', 'image'];

	public function kategori() : BelongsTo
	{
		return $this->belongsTo(KategoriModel::class, 'kategori_id', 'kategori_id');
	}

	public function stock() : HasMany
	{
		return $this->hasMany(StockModel::class, 'stok_id', 'stok_id');
	}

	public function image(): Attribute
	{
		return Attribute::make(
			get: fn($image) => url('/storage/posts/' . $image)
		);
	}
}