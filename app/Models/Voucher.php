<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'deal_purchase_id',
        'deal_id',
        'code',
        'qr_code_path',
        'status',
        'valid_until',
        'redeemed_at',
        'redeemed_by',
        'redemption_notes',
    ];

    protected $casts = [
        'valid_until' => 'datetime',
        'redeemed_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($voucher) {
            if (empty($voucher->code)) {
                $voucher->code = static::generateVoucherCode();
            }
        });

        static::created(function ($voucher) {
            $voucher->generateQRCode();
        });
    }

    public function dealPurchase(): BelongsTo
    {
        return $this->belongsTo(DealPurchase::class);
    }

    public function deal(): BelongsTo
    {
        return $this->belongsTo(Deal::class);
    }

    public function redeemedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'redeemed_by');
    }

    /**
     * Generate unique voucher code
     */
    public static function generateVoucherCode(): string
    {
        do {
            // Format: AAAA-BBBB-CCCC (12 chars + 2 dashes)
            $code = strtoupper(substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 4) . '-' .
                              substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 4) . '-' .
                              substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 4));
        } while (static::where('code', $code)->exists());

        return $code;
    }

    /**
     * Generate QR code for voucher
     */
    public function generateQRCode(): void
    {
        if ($this->qr_code_path) {
            return; // Already generated
        }

        $qrCodeData = json_encode([
            'voucher_code' => $this->code,
            'deal_id' => $this->deal_id,
            'deal_title' => $this->deal->title ?? 'Deal',
            'valid_until' => $this->valid_until?->toDateTimeString(),
        ]);

        $filename = 'voucher-' . $this->code . '.png';
        $path = 'vouchers/qr-codes/' . $filename;

        // Generate QR code (you'll need simplesoftwareio/simple-qrcode package)
        // For now, we'll just store the path
        // QrCode::format('png')->size(300)->generate($qrCodeData, storage_path('app/public/' . $path));
        
        $this->update(['qr_code_path' => $path]);
    }

    /**
     * Check if voucher is valid
     */
    public function isValid(): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

        if ($this->valid_until && $this->valid_until->isPast()) {
            $this->markAsExpired();
            return false;
        }

        return true;
    }

    /**
     * Redeem voucher
     */
    public function redeem(User $user = null, string $notes = null): bool
    {
        if (!$this->isValid()) {
            return false;
        }

        $this->update([
            'status' => 'redeemed',
            'redeemed_at' => now(),
            'redeemed_by' => $user?->id,
            'redemption_notes' => $notes,
        ]);

        return true;
    }

    /**
     * Mark voucher as expired
     */
    public function markAsExpired(): void
    {
        if ($this->status === 'active') {
            $this->update(['status' => 'expired']);
        }
    }

    /**
     * Cancel voucher
     */
    public function cancel(): void
    {
        $this->update(['status' => 'cancelled']);
    }

    /**
     * Get QR code URL
     */
    public function getQrCodeUrlAttribute(): ?string
    {
        return $this->qr_code_path ? Storage::url($this->qr_code_path) : null;
    }
}
