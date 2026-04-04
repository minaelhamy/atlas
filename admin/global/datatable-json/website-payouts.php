<?php
include '../../global/datatable-json/includes.php';

$params = $_REQUEST;
$columns = array(
    'u.username',
    's.site_name',
    'p.amount',
    'p.payment_method',
    'p.account_details',
    'p.status',
    'p.created_at',
);

$where = $sqlTot = $sqlRec = "";

if (!empty($params['search']['value'])) {
    $search = $params['search']['value'];
    $where .= " WHERE ";
    $where .= " (u.username LIKE '%" . $search . "%' ";
    $where .= " OR s.site_name LIKE '%" . $search . "%' ";
    $where .= " OR p.payment_method LIKE '%" . $search . "%' ";
    $where .= " OR p.status LIKE '%" . $search . "%') ";
}

$sql = "SELECT p.*, u.username as username, u.email as user_email, s.site_name as site_name
FROM `" . $config['db']['pre'] . "website_payouts` as p
LEFT JOIN `" . $config['db']['pre'] . "user` as u ON u.id = p.user_id
LEFT JOIN `" . $config['db']['pre'] . "website_sites` as s ON s.id = p.site_id ";

$sqlTot .= $sql;
$sqlRec .= $sql;
if ($where !== '') {
    $sqlTot .= $where;
    $sqlRec .= $where;
}

$sqlRec .= " ORDER BY " . $columns[$params['order'][0]['column']] . " " . $params['order'][0]['dir'] . " LIMIT " . $params['start'] . " ," . $params['length'] . " ";

$queryTot = $pdo->query($sqlTot);
$totalRecords = $queryTot->rowCount();
$queryRecords = $pdo->query($sqlRec);

$data = [];
foreach ($queryRecords as $row) {
    $id = (int) $row['id'];
    $owner = escape($row['username']);
    $siteName = escape($row['site_name']);
    $amount = price_format($row['amount']);
    $paymentMethod = escape($row['payment_method']);
    $accountDetails = nl2br(escape($row['account_details']));
    $createdAt = date('d M Y h:i A', strtotime($row['created_at']));
    $statusKey = $row['status'];

    if ($statusKey == 'paid') {
        $status = '<span class="badge badge-primary">' . __("Paid") . '</span>';
    } elseif ($statusKey == 'pending') {
        $status = '<span class="badge badge-warning">' . __("Pending") . '</span>';
    } else {
        $status = '<span class="badge badge-danger">' . __("Rejected") . '</span>';
    }

    $rows = array();
    $rows[] = '<td>' . $owner . '</td>';
    $rows[] = '<td>' . $siteName . '</td>';
    $rows[] = '<td>' . $amount . '</td>';
    $rows[] = '<td>' . $paymentMethod . '</td>';
    $rows[] = '<td>' . $accountDetails . '</td>';
    $rows[] = '<td>' . $status . '</td>';
    $rows[] = '<td>' . $createdAt . '</td>';
    $rows[] = '<td class="text-center">
                <div class="btn-group">
                <a href="#" title="' . __('Review') . '" data-url="panel/website-payouts.php?id=' . $id . '" data-toggle="slidePanel" class="btn-icon mr-1" data-tippy-placement="top"><i class="icon-feather-edit"></i></a>
                </div>
            </td>';
    $rows['DT_RowId'] = $id;
    $data[] = $rows;
}

echo json_encode(array(
    "draw" => intval($params['draw']),
    "recordsTotal" => intval($totalRecords),
    "recordsFiltered" => intval($totalRecords),
    "data" => $data
));
