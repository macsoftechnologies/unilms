<?php

namespace App\Controllers;

class CertificateVerify extends BaseController
{
    public function verify($hash)
    {
        $db = \Config\Database::connect();
        $record = $db->table('internship_enrollments e')
            ->select('e.*, s.first_name, s.last_name, s.roll_number, s.email, p.company_name, p.role_title, p.start_date, p.end_date')
            ->join('students s', 's.id = e.student_id')
            ->join('internship_postings p', 'p.id = e.posting_id')
            ->where('e.certificate_hash', $hash)
            ->get()->getRowArray();

        return view('public/verify_certificate', [
            'record' => $record,
            'hash'   => $hash
        ]);
    }
}
