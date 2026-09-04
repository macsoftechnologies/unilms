<?php
namespace App\Controllers;

use App\Models\StudentFeeLedgerModel;
use App\Models\FeeReceiptModel;
use App\Models\StudentModel;

class OrgFeePayments extends BaseController
{
    public function duesList()
    {
        $orgId = session()->get('org_id');
        
        $db = \Config\Database::connect();
        $builder = $db->table('student_fee_ledger sfl');
        $builder->select('sfl.*, s.first_name, s.last_name, s.roll_number, fs.amount as total_amount, ft.name as fee_type_name');
        $builder->join('students s', 's.id = sfl.student_id');
        $builder->join('fee_structures fs', 'fs.id = sfl.fee_structure_id');
        $builder->join('fee_types ft', 'ft.id = fs.fee_type_id');
        $builder->where('sfl.org_id', $orgId);
        $builder->where('sfl.status !=', 'paid');
        
        $data['dues'] = $builder->get()->getResultArray();
        
        return view('org/fees/dues_list', $data);
    }
    
    public function payFee()
    {
        $orgId = session()->get('org_id');
        $studentId = $this->request->getPost('student_id');
        $ledgerId = $this->request->getPost('ledger_id');
        $amountPaid = $this->request->getPost('amount');
        $mode = $this->request->getPost('mode');
        $bankRef = $this->request->getPost('bank_ref');
        $remarks = $this->request->getPost('remarks');
        
        $ledgerModel = new StudentFeeLedgerModel();
        $receiptModel = new FeeReceiptModel();
        
        $ledger = $ledgerModel->where('org_id', $orgId)->find($ledgerId);
        
        if ($ledger) {
            $newPaid = $ledger['amount_paid'] + $amountPaid;
            $newBalance = $ledger['amount_due'] - $newPaid;
            $status = ($newBalance <= 0) ? 'paid' : 'partial';
            
            $ledgerModel->update($ledgerId, [
                'amount_paid' => $newPaid,
                'balance' => $newBalance,
                'status' => $status
            ]);
            
            // Generate receipt no
            $receiptNo = 'REC-' . time() . '-' . rand(100, 999);
            
            $receiptId = $receiptModel->insert([
                'org_id' => $orgId,
                'receipt_no' => $receiptNo,
                'student_id' => $studentId,
                'amount' => $amountPaid,
                'mode' => $mode,
                'bank_ref' => $bankRef,
                'date' => date('Y-m-d'),
                'collected_by' => session()->get('org_user_id'),
                'remarks' => $remarks
            ]);
            
            return redirect()->to(base_url('org/fee-payments/receipt/' . $receiptId))->with('success', 'Payment successful.');
        }
        
        return redirect()->back()->with('error', 'Invalid ledger entry.');
    }
    
    public function receipt($id)
    {
        $orgId = session()->get('org_id');
        
        $db = \Config\Database::connect();
        $builder = $db->table('fee_receipts fr');
        $builder->select('fr.*, s.first_name, s.last_name, s.roll_number, p.name as program_name');
        $builder->join('students s', 's.id = fr.student_id');
        $builder->join('cohorts c', 'c.id = s.cohort_id', 'left');
        $builder->join('programs p', 'p.id = c.program_id', 'left');
        $builder->where('fr.id', $id);
        $builder->where('fr.org_id', $orgId);
        
        $receipt = $builder->get()->getRowArray();
        
        if (!$receipt) {
            return redirect()->to(base_url('org/fee-payments/dues'))->with('error', 'Receipt not found.');
        }
        
        $data['receipt'] = $receipt;
        
        return view('org/fees/receipt_pdf', $data);
    }
}
