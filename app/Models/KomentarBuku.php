<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KomentarBuku extends Model
{
    protected $table = 'komentar_buku';

    protected $fillable = [
        'buku_id',
        'anggota_id',
        'isi_komentar',
    ];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'anggota_id', 'id');
    }


    public function buku()
    {
        return $this->belongsTo(Buku::class, 'buku_id');
    }

    // ---- Helpers: JSON safe parse ----
    public function meta(): array
    {
        $raw = $this->isi_komentar;

        if (!is_string($raw)) return [];

        $decoded = json_decode($raw, true);
        return is_array($decoded) ? $decoded : [];
    }

    public function body(): string
    {
        $m = $this->meta();
        return $m['body'] ?? (string) $this->isi_komentar;
    }

    public function rec(): ?string
    {
        $m = $this->meta();
        return $m['rec'] ?? null;
    }

    public function aud(): ?string
    {
        $m = $this->meta();
        return $m['aud'] ?? null;
    }

    public function status(): string
    {
        $m = $this->meta();
        return $m['status'] ?? 'approved'; // komentar lama dianggap approved
    }

    public function pinned(): bool
    {
        $m = $this->meta();
        return (bool)($m['pinned'] ?? false);
    }

    public function setMeta(array $meta): void
    {
        // pastikan body selalu ada
        $meta['v'] = $meta['v'] ?? 1;
        $meta['body'] = $meta['body'] ?? '';

        $this->isi_komentar = json_encode($meta, JSON_UNESCAPED_UNICODE);
    }
}
