<?php

namespace App\Exports;

use App\Models\Ledger;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\User;

class ShopLedgerExport implements FromArray,WithHeadings
{
    protected $shopId;

    public function __construct($shopId)
    {
        $this->shopId = $shopId;
    }

    /**
     * @return array
     */
    public function array(): array
    {
        $ledgers = Ledger::where('shop_id',$this->shopId)->with('shop','product')->get();
        $data = [];
        if($ledgers->count() > 0)
        {
            foreach ($ledgers as $key =>$ledger)
            {
                $data[$key]['shop_name'] =$ledger->shop->name ?? '';
                $data[$key]['product_name'] = $ledger->product->name ?? '';
                $data[$key]['price'] = $ledger->price ?? '';
                $data[$key]['quantity'] = $ledger->quantity ?? '';
                $data[$key]['status'] = $ledger->status == '0' ? 'Credit' : 'Debit';
                $data[$key]['credit'] = $ledger->credit ?? '';
                $data[$key]['debit'] = $ledger->debit ?? '';
                $data[$key]['closing_account'] = $ledger->closing_account ?? '';
                $data[$key]['date'] = date("j-m-y h:i:s",$ledger->created_at->timestamp) ?? '';
            }
        }
        return $data;

    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
           'Shop Name',
            'Product',
            'Price',
            'Quantity',
            'Status',
            'Credit',
            'Debit',
            'Closing Account',
            'Date'

        ];
    }
}
