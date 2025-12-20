<?php

namespace App\Http\Controllers;

use App\Models\DealPurchase;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class VoucherController extends Controller
{
    /**
     * Show voucher page
     */
    public function show($confirmationCode)
    {
        $purchase = DealPurchase::where('confirmation_code', $confirmationCode)
            ->with(['deal.vendor', 'deal.category'])
            ->firstOrFail();
        
        return view('vouchers.show', compact('purchase'));
    }
    
    /**
     * Download voucher as PDF
     */
    public function downloadPdf($confirmationCode)
    {
        $purchase = DealPurchase::where('confirmation_code', $confirmationCode)
            ->with(['deal.vendor', 'deal.category'])
            ->firstOrFail();
        
        $pdf = Pdf::loadView('vouchers.pdf', compact('purchase'));
        
        return $pdf->download('voucher-' . $confirmationCode . '.pdf');
    }
    
    /**
     * Email voucher to consumer
     */
    public function emailVoucher(Request $request, $confirmationCode)
    {
        $request->validate([
            'email' => 'required|email',
        ]);
        
        $purchase = DealPurchase::where('confirmation_code', $confirmationCode)
            ->with(['deal.vendor', 'deal.category'])
            ->firstOrFail();
        
        try {
            \Mail::send('emails.voucher_email', [
                'purchase' => $purchase,
                'deal' => $purchase->deal,
            ], function ($message) use ($request, $purchase) {
                $message->from(env('MAIL_FROM_ADDRESS'), getcong('site_name'));
                $message->to($request->email)->subject('Your Deal Voucher - ' . $purchase->deal->title);
                
                // Attach PDF
                $pdf = Pdf::loadView('vouchers.pdf', ['purchase' => $purchase]);
                $message->attachData($pdf->output(), 'voucher-' . $purchase->confirmation_code . '.pdf');
            });
            
            \Session::flash('flash_message', 'Voucher sent to ' . $request->email);
        } catch (\Exception $e) {
            \Log::error('Failed to email voucher: ' . $e->getMessage());
            \Session::flash('error_flash_message', 'Failed to send email. Please try again.');
        }
        
        return redirect()->back();
    }
}


