<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'donor_name',
        'email',
        'amount',
        'payment_method',
        'note',
        'qr_code_path',
        'qr_code_label',
        'evidence_path',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    // Accessor to get full QR code URL
    public function getQrCodeUrlAttribute()
    {
        if ($this->qr_code_path && $this->qr_code_path !== '') {
            return asset('storage/' . $this->qr_code_path);
        }
        return null;
    }

    // Accessor to get full evidence URL
    public function getEvidenceUrlAttribute()
    {
        if ($this->evidence_path && $this->evidence_path !== '') {
            return asset('storage/' . $this->evidence_path);
        }
        return null;
    }

    // Check if this is a QR code record (not a donation)
    public function isQrCode()
    {
        return $this->qr_code_path && 
               $this->qr_code_path !== '' && 
               $this->qr_code_label &&
               !$this->donor_name;
    }

    // Check if this is a real donation
    public function isDonation()
    {
        return $this->amount > 0 && 
               $this->donor_name && 
               !$this->qr_code_label;
    }
}