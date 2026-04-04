<?php
include '../../global/datatable-json/includes.php';

$params = $_REQUEST;
$columns = array(
    'u.username',
    's.site_name',
    's.site_type',
    'request_count',
    'posted_earnings',
    's.status',
    's.updated_at',
);

$where = $sqlTot = $sqlRec = "";

if (!empty($params['search']['value'])) {
    $search = $params['search']['value'];
    $where .= " WHERE ";
    $where .= " (u.username LIKE '%" . $search . "%' ";
    $where .= " OR s.site_name LIKE '%" . $search . "%' ";
    $where .= " OR s.site_type LIKE '%" . $search . "%' ";
    $where .= " OR s.status LIKE '%" . $search . "%') ";
}

$sql = "SELECT s.*, u.username,
        (
            SELECT COUNT(*) FROM `" . $config['db']['pre'] . "website_orders` wo WHERE wo.site_id = s.id
        ) + (
            SELECT COUNT(*) FROM `" . $config['db']['pre'] . "website_bookings` wb WHERE wb.site_id = s.id
        ) AS request_count,
        (
            SELECT COALESCE(SUM(wl.amount), 0) FROM `" . $config['db']['pre'] . "website_wallet_ledger` wl
            WHERE wl.site_id = s.id AND wl.status = 'posted'
        ) AS posted_earnings
        FROM `" . $config['db']['pre'] . "website_sites` as s
        LEFT JOIN `" . $config['db']['pre'] . "user` as u ON u.id = s.user_id ";

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
    $statusClass = $row['status'] === 'published' ? 'badge-primary' : 'badge-warning';
    $rows = [];
    $rows[] = '<td>' . escape($row['username']) . '</td>';
    $rows[] = '<td><strong>' . escape($row['site_name']) . '</strong><br><small>' . escape($row['subdomain']) . '</small></td>';
    $rows[] = '<td>' . escape(ucfirst($row['site_type'])) . '</td>';
    $rows[] = '<td>' . (int) $row['request_count'] . '</td>';
    $rows[] = '<td>' . price_format((float) $row['posted_earnings']) . '</td>';
    $rows[] = '<td><span class="badge ' . $statusClass . '">' . escape(ucfirst($row['status'])) . '</span></td>';
    $rows[] = '<td>' . (!empty($row['updated_at']) ? date('d M Y h:i A', strtotime($row['updated_at'])) : '-') . '</td>';
    $rows[] = '<td class="text-center"><a href="' . SITEURL . 'your-website/dashboard/' . $id . '" target="_blank" class="btn-icon mr-1" data-tippy-placement="top" title="' . __('Open dashboard') . '"><i class="icon-feather-external-link"></i></a></td>';
    $rows['DT_RowId'] = $id;
    $data[] = $rows;
}

echo json_encode(array(
    "draw" => intval($params['draw']),
    "recordsTotal" => intval($totalRecords),
    "recordsFiltered" => intval($totalRecords),
    "data" => $data
));
