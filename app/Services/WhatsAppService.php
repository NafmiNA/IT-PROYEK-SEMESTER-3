<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected $token;
    protected $url;

    public function __construct()
    {
        $this->token = config('services.fonnte.token');
        $this->url = config('services.fonnte.url');
    }

    /**
     * Send WhatsApp message
     * 
     * @param string $phoneNumber
     * @param string $message
     * @return bool
     */
    public function send($phoneNumber, $message)
    {
        if (empty($this->token)) {
            Log::warning('Fonnte token not configured');
            return false;
        }

        // Format phone number (remove +, spaces, hyphens)
        $phone = preg_replace('/[^0-9]/', '', $phoneNumber);
        
        // Ensure starts with 62 (Indonesia)
        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        } elseif (substr($phone, 0, 2) !== '62') {
            $phone = '62' . $phone;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $this->token,
            ])->post($this->url, [
                'target' => $phone,
                'message' => $message,
                'countryCode' => '62',
            ]);

            if ($response->successful()) {
                Log::info('WhatsApp sent successfully to ' . $phone);
                return true;
            } else {
                Log::error('WhatsApp failed: ' . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            Log::error('WhatsApp exception: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send verification notification to dosen
     * 
     * @param \App\Models\Dosen $dosen
     * @param \App\Models\Pengabdian $pengabdian
     * @param string $status
     * @param string|null $catatan
     * @return bool
     */
    public function sendVerificationNotification($dosen, $pengabdian, $status, $catatan = null)
    {
        $statusText = $status === 'Disetujui' ? 'DISETUJUI ✅' : 'DITOLAK ❌';
        
        $message = "🔔 *Notifikasi Verifikasi Pengabdian*\n\n";
        $message .= "Kepada: *{$dosen->nama}*\n\n";
        $message .= "Pengabdian Anda:\n";
        $message .= "📋 *{$pengabdian->judul}*\n\n";
        $message .= "Status: *{$statusText}*\n\n";
        
        if ($catatan) {
            $message .= "📝 Catatan Admin:\n";
            $message .= "_{$catatan}_\n\n";
        }
        
        $message .= "Silakan cek sistem P3M untuk detail lengkap.\n\n";
        $message .= "---\n";
        $message .= "Sistem P3M Politala";

        // Get phone number from dosen
        $phone = $dosen->no_hp ?? $dosen->telepon ?? null;
        
        if (empty($phone)) {
            Log::warning('Dosen ' . $dosen->nama . ' (ID: ' . $dosen->id . ') has no phone number');
            return false;
        }

        return $this->send($phone, $message);
    }
}
