<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;


class Wallet extends Model
{
    use HasFactory;


    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'client_id' => 'required',
        'wallet_id' => 'required|unique:wallet,wallet_id',
        'wallet_date' => 'required',
        'due_date' => 'required',
    ];


    public $table = 'wallet';

    public $appends = ['status_label'];

    public $fillable = [
        'client_id',
        'wallet_id',
        'currency_id',
        'amount',
        'parent_id',
    ];

    protected $casts = [
        'client_id' => 'integer',
        'parent_id' => 'integer',
        'wallet_id' => 'string',
        'currency_id' => 'integer',
        'amount' => 'double',
    ];

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_ARR[$this->status];
    }

    /**
     * @return BelongsTo
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }


    /**
     * @return HasMany
     */
    public function AdminWallet(): HasMany
    {
        return $this->hasMany(AdminWallet::class, 'wallet_id', 'id');
    }



    public function parentWallet(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id', 'id');
    }

    public function childWallet(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }

    /**
     * @param $value
     * @return Setting|mixed
     */
    public function getCurrencyIdAttribute($value)
    {
        if (! empty($value)) {
            return Currency::where('id', $value)->pluck('id')->first();
        }

        return Currency::where('id', getSettingValue('current_currency'))->pluck('id')->first();
    }

}
