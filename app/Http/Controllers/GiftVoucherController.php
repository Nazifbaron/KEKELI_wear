<?php

namespace App\Http\Controllers;

use App\Models\GiftVoucher;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class GiftVoucherController extends Controller
{
    /*
    |----------------------------------------------------------
    | PAGE ACHAT BON D'ACHAT
    |----------------------------------------------------------
    */
    public function index()
    {
        return view('vouchers.index');
    }

    /*
    |----------------------------------------------------------
    | CRÉER LE BON après paiement KKiaPay confirmé
    | Appelé depuis le front une fois le paiement validé
    |----------------------------------------------------------
    */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'amount'            => 'required|numeric|min:5000|max:1000000',
            'buyer_name'        => 'required|string|max:120',
            'buyer_phone'       => 'required|string|max:20',
            'buyer_email'       => 'nullable|email|max:120',
            'recipient_name'    => 'nullable|string|max:120',
            'recipient_phone'   => 'nullable|string|max:20',
            'transaction_id'    => 'required|string|max:100',
        ]);

        /*
        | Vérifier la transaction KKiaPay côté serveur
        */
        $verified = $this->verifyKkiapayTransaction($validated['transaction_id'], $validated['amount']);

        if (!$verified) {
            return response()->json([
                'success' => false,
                'error'   => 'Transaction KKiaPay non vérifiée. Veuillez contacter le support.',
            ], 422);
        }

        $voucher = GiftVoucher::create([
            'code'                   => GiftVoucher::generateCode(),
            'initial_amount'         => $validated['amount'],
            'balance'                => $validated['amount'],
            'buyer_name'             => $validated['buyer_name'],
            'buyer_phone'            => $validated['buyer_phone'],
            'buyer_email'            => $validated['buyer_email'] ?? null,
            'recipient_name'         => $validated['recipient_name'] ?? null,
            'recipient_phone'        => $validated['recipient_phone'] ?? null,
            'payment_transaction_id' => $validated['transaction_id'],
            'payment_status'         => 'paid',
            'status'                 => 'active',
            'paid_at'                => now(),
        ]);

        return response()->json([
            'success' => true,
            'code'    => $voucher->code,
            'amount'  => $voucher->formatted_initial_amount,
            'message' => 'Bon d\'achat créé avec succès !',
        ]);
    }

    /*
    |----------------------------------------------------------
    | VÉRIFIER UN BON — appelé depuis le checkout
    | Retourne le solde disponible
    |----------------------------------------------------------
    */
    public function verify(Request $request): JsonResponse
    {
        $code    = strtoupper(trim($request->input('code', '')));
        $voucher = GiftVoucher::where('code', $code)->first();

        if (!$voucher || !$voucher->isUsable()) {
            return response()->json([
                'valid'   => false,
                'message' => 'Bon invalide, épuisé ou expiré.',
            ], 422);
        }

        /*
        | Sauvegarder en session comme le code promo
        */
        $request->session()->put('voucher_code',    $voucher->code);
        $request->session()->put('voucher_id',      $voucher->id);
        $request->session()->put('voucher_balance', (float)$voucher->balance);

        return response()->json([
            'valid'   => true,
            'code'    => $voucher->code,
            'balance' => (float)$voucher->balance,
            'label'   => $voucher->formatted_balance . ' disponibles',
            'message' => '✓ Bon valide — ' . $voucher->formatted_balance . ' disponibles.',
        ]);
    }

    /*
    |----------------------------------------------------------
    | RETIRER LE BON de la session
    |----------------------------------------------------------
    */
    public function remove(Request $request): JsonResponse
    {
        $request->session()->forget(['voucher_code', 'voucher_id', 'voucher_balance']);
        return response()->json(['success' => true]);
    }

    /*
    |----------------------------------------------------------
    | PAGE DE CONFIRMATION après achat
    |----------------------------------------------------------
    */
    public function confirmation(string $code)
    {
        $voucher = GiftVoucher::where('code', $code)
                              ->where('payment_status', 'paid')
                              ->firstOrFail();

        return view('vouchers.confirmation', compact('voucher'));
    }

    /*
    |----------------------------------------------------------
    | Vérification KKiaPay côté serveur
    |----------------------------------------------------------
    */
    private function verifyKkiapayTransaction(string $transactionId, float $amount): bool
    {
        try {
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'x-private-key' => config('kekeli.kkiapay_private_key'),
            ])->get('https://api.kkiapay.me/api/v1/transactions/' . $transactionId);

            if ($response->successful()) {
                $data   = $response->json();
                $status = $data['status'] ?? '';
                $txAmt  = $data['amount'] ?? 0;
                return $status === 'SUCCESS' && (float)$txAmt >= $amount;
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('KKiaPay verify error: ' . $e->getMessage());
        }

        /* En sandbox → accepter sans vérification */
        return config('kekeli.kkiapay_env', 'sandbox') === 'sandbox';
    }
}
