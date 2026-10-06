<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GiftVoucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function index(Request $request)
    {
        $query = GiftVoucher::with(['usages.order'])->latest();

        if ($request->filter === 'active') {
            $query->where('status', 'active')->where('payment_status', 'paid');
        } elseif ($request->filter) {
            $query->where('status', $request->filter);
        }

        $vouchers = $query->paginate(20)->withQueryString();

        $stats = [
            'total'         => GiftVoucher::count(),
            'active'        => GiftVoucher::where('status','active')->where('payment_status','paid')->count(),
            'used'          => GiftVoucher::where('status','used')->count(),
            'total_issued'  => GiftVoucher::where('payment_status','paid')->sum('initial_amount'),
            'total_balance' => GiftVoucher::where('status','active')->sum('balance'),
        ];

        return view('admin.vouchers.index', compact('vouchers', 'stats'));
    }

    /* Création manuelle par l'admin — pas de paiement requis */
    public function store(Request $request)
    {
        $data = $request->validate([
            'amount'         => 'required|numeric|min:1000',
            'buyer_name'     => 'nullable|string|max:120',
            'buyer_phone'    => 'nullable|string|max:20',
            'recipient_name' => 'nullable|string|max:120',
            'expires_at'     => 'nullable|date|after:today',
        ]);

        GiftVoucher::create([
            'code'            => GiftVoucher::generateCode(),
            'initial_amount'  => $data['amount'],
            'balance'         => $data['amount'],
            'buyer_name'      => $data['buyer_name'] ?? 'Admin',
            'buyer_phone'     => $data['buyer_phone'] ?? null,
            'recipient_name'  => $data['recipient_name'] ?? null,
            'payment_status'  => 'paid',   // Admin = déjà payé
            'status'          => 'active',
            'paid_at'         => now(),
            'expires_at'      => $data['expires_at'] ?? null,
        ]);

        return redirect()->route('admin.vouchers')
                         ->with('success', 'Bon d\'achat créé avec succès.');
    }

    /* Annuler un bon actif */
    public function cancel(GiftVoucher $voucher)
    {
        if ($voucher->status !== 'active') {
            return back()->with('error', 'Ce bon ne peut pas être annulé.');
        }
        $voucher->update(['status' => 'cancelled']);
        return back()->with('success', 'Bon ' . $voucher->code . ' annulé.');
    }
}
