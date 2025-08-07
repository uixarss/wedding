<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\GuestBook;
use App\User;
use App\Models\Audio;

class Event extends Model
{
    protected $fillable = [
        'order_id', 'code_event', 'path_qr_code', 'template', 'slug', 'link_livestreaming',
        'nama_panggilan_mempelai_pria', 'nama_lengkap_mempelai_pria', 'avatar_pria',
        'nama_panggilan_mempelai_wanita', 'nama_lengkap_mempelai_wanita',  'avatar_wanita',
        'bio_mempelai_pria','bio_mempelai_wanita',
        'tanggal_ijab', 'tanggal_resepsi',
        'jam_mulai_ijab', 'jam_mulai_resepsi',
        'jam_selesai_ijab', 'jam_selesai_resepsi',
        'lokasi_ijab', 'lokasi_resepsi',
        'gm_ijab','gm_resepsi','created_by','link_youtube','yt_title','yt_desc','logo_req','audio_id'
    ];

    protected $casts = [
        'tanggal_ijab' => 'date',
        'tanggal_resepsi' => 'date',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(PhotoEvent::class);
    }

    public function guests(): BelongsToMany
    {
        return $this->belongsToMany(User::class,'guest_books','event_id','user_id')->withPivot('text','user_id');
    }

    public function invite(): BelongsToMany
    {
        return $this->belongsToMany(User::class,'invites','event_id','guest_id')->withPivot('kode_kupon','status','is_confirmed','is_invited');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class,'created_by');
    }

    public function audio(): BelongsTo
    {
        return $this->belongsTo(Audio::class);
    }

    public function angpao(): HasMany
    {
        return $this->hasMany(Angpao::class, 'event_id', 'id');
    }

    public function guestBooks(): HasMany
    {
        return $this->hasMany(GuestBook::class);
    }

    /**
     * Get the formatted groom and bride names
     */
    public function getFormattedNamesAttribute(): string
    {
        return $this->nama_lengkap_mempelai_wanita . ' & ' . $this->nama_lengkap_mempelai_pria;
    }

    /**
     * Scope to filter by template
     */
    public function scopeByTemplate($query, string $template)
    {
        return $query->where('template', $template);
    }
}
