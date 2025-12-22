<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'deal_id',
        'user_id',
        'deal_purchase_id',
        'voucher_code',
        'qr_code_path',
        'pdf_path',
        'purchase_date',
        'expiration_date',
        'status',
        'redeemed_at',
        'redeemed_by_vendor_user_id'
    ];
    
    protected $casts = [
        'purchase_date' => 'datetime',
        'expiration_date' => 'datetime',
        'redeemed_at' => 'datetime'
    ];
    
    // Relationships
    public function deal(): BelongsTo
    {
        return $this->belongsTo(Deal::class);
    }
    
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function purchase(): BelongsTo
    {
        return $this->belongsTo(DealPurchase::class, 'deal_purchase_id');
    }
    
    public function redeemedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'redeemed_by_vendor_user_id');
    }
    
    // Status Checks
    public function isActive(): bool
    {
        return $this->status === 'active' && !$this->isExpired();
    }
    
    public function isExpired(): bool
    {
        return $this->expiration_date->isPast();
    }
    
    public function isRedeemed(): bool
    {
        return $this->status === 'redeemed';
    }
    
    public function canRedeem(): bool
    {
        return $this->isActive() && !$this->isExpired() && !$this->isRedeemed();
    }
    
    // Getters
    public function getQrCodeUrl(): string
    {
        return $this->qr_code_path ? asset('storage/' . $this->qr_code_path) : '';
    }
    
    public function getPdfUrl(): string
    {
        return $this->pdf_path ? asset('storage/' . $this->pdf_path) : '';
    }
    
    public function getFormattedCode(): string
    {
        // Format: ABCD-1234-EFGH-5678
        return chunk_split($this->voucher_code, 4, '-');
