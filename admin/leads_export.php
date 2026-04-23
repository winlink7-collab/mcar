<?php
require_once __DIR__ . '/_bootstrap.php';

$pdo = db();
if (!$pdo) { http_response_code(500); echo 'DB not connected'; exit; }

$filename = 'mcar-leads-' . date('Y-m-d') . '.csv';
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');

$out = fopen('php://output', 'w');
// BOM for Excel UTF-8
fwrite($out, "\xEF\xBB\xBF");

// Header row
fputcsv($out, ['ID', 'תאריך', 'שם', 'טלפון', 'אימייל', 'מקור', 'רכב', 'חבילה', 'סוג עסקה', 'הודעה', 'סטטוס', 'IP']);

$stmt = $pdo->query("SELECT * FROM leads ORDER BY created_at DESC");
foreach ($stmt as $r) {
    fputcsv($out, [
        $r['id'],
        $r['created_at'],
        $r['name'],
        $r['phone'],
        $r['email'],
        $r['source'],
        $r['car_id'],
        $r['pkg_id'],
        $r['deal_type'],
        $r['message'],
        $r['status'],
        $r['ip'],
    ]);
}
fclose($out);
exit;
