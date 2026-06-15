<?php

namespace App\Controllers;

class ReportController extends BaseController
{
    public function index()
    {
        [$dateFrom, $dateTo] = $this->dateRange();

        return view('report/index', [
            'title'       => 'Laporan Penjualan',
            'filters'     => [
                'date_from' => $dateFrom,
                'date_to'   => $dateTo,
            ],
            'summary'     => $this->summary($dateFrom, $dateTo),
            'dailySales'  => $this->dailySales($dateFrom, $dateTo),
            'topProducts' => $this->topProducts($dateFrom, $dateTo),
        ]);
    }

    public function export()
    {
        [$dateFrom, $dateTo] = $this->dateRange();
        $summary = $this->summary($dateFrom, $dateTo);
        $dailySales = $this->dailySales($dateFrom, $dateTo);
        $topProducts = $this->topProducts($dateFrom, $dateTo);

        $content = "\xEF\xBB\xBFsep=,\r\n";
        $content .= $this->csvLine(['Laporan Penjualan']);
        $content .= $this->csvLine(['Periode', $dateFrom . ' sampai ' . $dateTo]);
        $content .= $this->csvLine([]);
        $content .= $this->csvLine(['Ringkasan']);
        $content .= $this->csvLine(['Omzet', $summary->total_sales]);
        $content .= $this->csvLine(['Transaksi', $summary->total_transactions]);
        $content .= $this->csvLine(['Item Terjual', $summary->total_items]);
        $content .= $this->csvLine([]);
        $content .= $this->csvLine(['Penjualan Harian']);
        $content .= $this->csvLine(['Tanggal', 'Transaksi', 'Item Terjual', 'Omzet']);

        foreach ($dailySales as $row) {
            $content .= $this->csvLine([
                $row->date,
                $row->transaction_count,
                $row->total_items,
                $row->total_sales,
            ]);
        }

        $content .= $this->csvLine([]);
        $content .= $this->csvLine(['Produk Terlaris']);
        $content .= $this->csvLine(['Produk', 'Qty Terjual', 'Omzet']);

        foreach ($topProducts as $row) {
            $content .= $this->csvLine([
                $row->product_name,
                $row->total_qty,
                $row->total_sales,
            ]);
        }

        return $this->response
            ->setHeader('Content-Type', 'text/csv; charset=UTF-8')
            ->setHeader('Content-Disposition', 'attachment; filename="laporan_penjualan_' . $dateFrom . '_' . $dateTo . '.csv"')
            ->setBody($content);
    }

    private function summary(string $dateFrom, string $dateTo): object
    {
        $db = \Config\Database::connect();
        $transactionBuilder = $this->transactionScope(
            $db->table('transactions'),
            $dateFrom,
            $dateTo
        );
        $itemBuilder = $this->itemScope(
            $db->table('transaction_items')
                ->join('transactions', 'transactions.id = transaction_items.transaction_id'),
            $dateFrom,
            $dateTo
        );

        $transactionSummary = $transactionBuilder
            ->select('COUNT(transactions.id) AS total_transactions, COALESCE(SUM(transactions.total_amount), 0) AS total_sales', false)
            ->get()
            ->getRow();

        $itemSummary = $itemBuilder
            ->select('COALESCE(SUM(transaction_items.qty), 0) AS total_items', false)
            ->get()
            ->getRow();

        return (object) [
            'total_transactions' => (int) ($transactionSummary->total_transactions ?? 0),
            'total_sales'        => (float) ($transactionSummary->total_sales ?? 0),
            'total_items'        => (int) ($itemSummary->total_items ?? 0),
        ];
    }

    private function dailySales(string $dateFrom, string $dateTo): array
    {
        $db = \Config\Database::connect();
        $builder = $this->itemScope(
            $db->table('transactions')
                ->select('DATE(transactions.created_at) AS date, COUNT(DISTINCT transactions.id) AS transaction_count, COALESCE(SUM(transaction_items.qty), 0) AS total_items, COALESCE(SUM(transaction_items.subtotal), 0) AS total_sales', false)
                ->join('transaction_items', 'transaction_items.transaction_id = transactions.id', 'left'),
            $dateFrom,
            $dateTo
        );

        return $builder
            ->groupBy('DATE(transactions.created_at)')
            ->orderBy('date', 'ASC')
            ->get()
            ->getResult();
    }

    private function topProducts(string $dateFrom, string $dateTo): array
    {
        $db = \Config\Database::connect();
        $builder = $this->itemScope(
            $db->table('transaction_items')
                ->select('transaction_items.product_name, COALESCE(SUM(transaction_items.qty), 0) AS total_qty, COALESCE(SUM(transaction_items.subtotal), 0) AS total_sales', false)
                ->join('transactions', 'transactions.id = transaction_items.transaction_id'),
            $dateFrom,
            $dateTo
        );

        return $builder
            ->groupBy('transaction_items.product_name')
            ->orderBy('total_qty', 'DESC')
            ->orderBy('total_sales', 'DESC')
            ->limit(10)
            ->get()
            ->getResult();
    }

    private function transactionScope($builder, string $dateFrom, string $dateTo)
    {
        $builder
            ->where('DATE(transactions.created_at) >=', $dateFrom)
            ->where('DATE(transactions.created_at) <=', $dateTo);

        if (session()->get('user_role') !== 'admin') {
            $builder->where('transactions.user_id', (int) session()->get('user_id'));
        }

        return $builder;
    }

    private function itemScope($builder, string $dateFrom, string $dateTo)
    {
        return $this->transactionScope($builder, $dateFrom, $dateTo);
    }

    private function dateRange(): array
    {
        $dateFrom = $this->validDate((string) $this->request->getGet('date_from')) ?: date('Y-m-d');
        $dateTo = $this->validDate((string) $this->request->getGet('date_to')) ?: $dateFrom;

        if ($dateFrom > $dateTo) {
            [$dateFrom, $dateTo] = [$dateTo, $dateFrom];
        }

        return [$dateFrom, $dateTo];
    }

    private function validDate(string $date): ?string
    {
        if (! preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            return null;
        }

        $parsed = date_create_from_format('Y-m-d', $date);

        return $parsed && $parsed->format('Y-m-d') === $date ? $date : null;
    }

    private function csvLine(array $row): string
    {
        $handle = fopen('php://temp', 'rb+');
        fputcsv($handle, $row);
        rewind($handle);
        $line = stream_get_contents($handle);
        fclose($handle);

        return (string) $line;
    }
}
