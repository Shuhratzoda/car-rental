<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\BookingRepository;

final class ReportController extends BaseController
{
    public function income(): void
    {
        require_role(['admin', 'manager']);
        $from = $_GET['from'] ?? '';
        $to   = $_GET['to'] ?? '';

        $repo = new BookingRepository();
        $rep  = $repo->incomeReport($from ?: null, $to ?: null);
        $tot  = array_sum(array_column($rep, 'sum_total'));
        $mx   = max(1, ...(array_column($rep, 'sum_total') ?: [1]));

        $this->render('reports/income', [
            'title' => 'Ҳисоботи даромад',
            'rep'   => $rep,
            'tot'   => $tot,
            'mx'    => $mx,
            'from'  => $from,
            'to'    => $to,
        ]);
    }
}
