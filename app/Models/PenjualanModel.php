<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PenjualanModel extends Model
{
    use HasFactory;

	protected $table = 't_penjualan';
	protected $primaryKey = 'penjualan_id';

	protected $fillable = ['penjualan_id', 'user_id', 'pembeli', 'penjualan_kode', 'penjualan_tanggal'];

	public function user(): BelongsTo
	{
		return $this->belongsTo(UserModel::class, 'user_id', 'user_id');
	}

	public function details(): HasMany
	{
		return $this->hasMany(PenjualanDetailModel::class, 'penjualan_id');
	}

	public static function generateSaleCode()
	{
		$latestSale = self::latest('created_at')->first();
		if(!$latestSale){
			$number = 0;
		}else{
			$number = substr($latestSale->penjualan_kode, 1);
		}
		return 'S' . str_pad($number + 1, 3, '0', STR_PAD_LEFT);
	}
}
