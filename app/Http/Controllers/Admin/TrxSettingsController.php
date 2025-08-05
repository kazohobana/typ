<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\TransactionSetting;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

class TrxSettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page_title = __("Fees & Charges");
        $transaction_charges = TransactionSetting::where('status',1)->get();
        return view('admin.sections.trx-settings.index',compact(
            'page_title',
            'transaction_charges'
        ));
    }

    /**
     * Update transaction charges
     * @param Request closer
     * @return back view
     */
    public function trxChargeUpdate(Request $request) {
        // Special validation for 'trade' slug
        if ($request->slug == 'trade') {
            $validator = Validator::make($request->all(), [
                                'slug'          => 'required|string',
                $request->slug.'_min_limit'     => 'array',
                $request->slug.'_min_limit.*'   => 'required|numeric',
                $request->slug.'_max_limit'     => 'array',
                $request->slug.'_max_limit.*'   => 'required|numeric',
                $request->slug.'_charge'        => 'array',
                $request->slug.'_charge.*'      => 'required|numeric',
                $request->slug.'_percent'       => 'array',
                $request->slug.'_percent.*'     => 'required|numeric',
                $request->slug.'_daily_limit'   => 'nullable',
                $request->slug.'_monthly_limit' => 'nullable',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $validated = $validator->validate();
            $transaction_setting = TransactionSetting::where('slug', $request->slug)->first();

            if (!$transaction_setting) {
                return back()->with(['error' => [__('Transaction charge not found').'!']]);
            }

            $validated = replace_array_key($validated, $request->slug."_");

            $input_fields = [];
            foreach ($validated['min_limit'] ?? [] as $key => $item) {
                $input_fields[] = [
                    'min_limit' => $item,
                    'max_limit' => $validated['max_limit'][$key] ?? "",
                    'charge'    => $validated['charge'][$key] ?? "",
                    'percent'   => $validated['percent'][$key] ?? "",
                ];
            }

            $validated['intervals'] = $input_fields;
            $validated = Arr::except($validated, ['min_limit', 'max_limit', 'charge', 'percent']);

            try {
                $transaction_setting->update($validated);
            } catch (Exception $e) {
                return back()->with(['error' => ["Something went wrong. Please try again"]]);
            }

            return back()->with(['success' => [__("Charge Updated Successfully!")]]);
        }

        $transaction_setting = TransactionSetting::where('slug',$request->slug)->first();
        if(!$transaction_setting) return back()->with(['error' => [__("Transaction charge not found!")]]);
        if($transaction_setting->agent_profit == true){
            $agent_percent_commission   = 'required|numeric';
            $agent_fixed_commission     = 'required|numeric';
        }else{
            $agent_percent_commission   = 'nullable';
            $agent_fixed_commission     = 'nullable';
        }
        if($request->slug == 'gift_card' || $request->slug == 'pay-link'){
            $daily_limit    = 'nullable';
            $monthly_limit  = 'nullable';
        }else{
            $daily_limit    = 'required|numeric';
            $monthly_limit  = 'required|numeric';
        }
        $validator = Validator::make($request->all(),[
            'slug'                                          => 'required|string',
            $request->slug.'_fixed_charge'                  => 'required|numeric',
            $request->slug.'_percent_charge'                => 'required|numeric',
            $request->slug.'_min_limit'                     => 'required|numeric',
            $request->slug.'_max_limit'                     => 'required|numeric',
            $request->slug.'_daily_limit'                   => $daily_limit,
            $request->slug.'_monthly_limit'                 => $monthly_limit,
            $request->slug.'_agent_percent_commissions'     => $agent_percent_commission,
            $request->slug.'_agent_fixed_commissions'       => $agent_fixed_commission,
        ]);
        $validated = $validator->validate();
        $validated = replace_array_key($validated,$request->slug."_");

        try{
            $transaction_setting->update($validated);
        }catch(Exception $e) {
            return back()->with(['error' => [__("Something went wrong! Please try again.")]]);
        }
        return back()->with(['success' => [__("Charge Updated Successfully!")]]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
