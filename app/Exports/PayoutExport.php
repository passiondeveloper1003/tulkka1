<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PayoutExport implements FromCollection, WithHeadings, WithMapping
{
    protected $payouts;

    public function __construct($payouts)
    {
        $this->payouts = $payouts;
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        return $this->payouts;
    }

    /**
     * @inheritDoc
     */
    public function headings(): array
    {
        return [
            trans('admin/main.user'),
            trans('admin/main.role'),
            trans('admin/main.payout_amount'),
            trans('admin/main.bank'),
            trans('admin/main.account_id'),
            trans('admin/main.iban'),
            trans('admin/main.phone'),
            trans('admin/main.last_payout_date'),
            trans('admin/main.status'),
        ];
    }

    /**
     * @inheritDoc
     */
    public function map($payout): array
    {
        return [
            $payout->user->full_name,
            $payout->user->role->caption,
            addCurrencyToPrice($payout->amount),
            $payout->account_bank_name,
            $payout->user->account_id,
            $payout->account_number,
            $payout->user->mobile,
            dateTimeFormat($payout->created_at, 'Y/m/j-H:i'),
            trans('public.'.$payout->status)
        ];
    }
}
