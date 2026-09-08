<?php
namespace App\Controllers;

use App\Models\AccountsHeadModel;
use App\Models\AccountsBankModel;
use App\Models\AccountsTransactionModel;

class OrgAccounts extends BaseController
{
    public function index()
    {
        // Simple dashboard
        $db = \Config\Database::connect();
        $orgId = session('org_id');

        $data['total_bank'] = $db->table('accounts_banks')->selectSum('current_balance')->where('org_id', $orgId)->where('type', 'Bank')->get()->getRow()->current_balance ?? 0;
        $data['total_cash'] = $db->table('accounts_banks')->selectSum('current_balance')->where('org_id', $orgId)->where('type', 'Cash')->get()->getRow()->current_balance ?? 0;

        return view('org/accounts/index', $data);
    }

    // Chart of Accounts
    public function heads()
    {
        $headModel = new AccountsHeadModel();
        $data['heads'] = $headModel->where('org_id', session('org_id'))->findAll();
        return view('org/accounts/heads', $data);
    }

    public function save_head()
    {
        $headModel = new AccountsHeadModel();
        $id = $this->request->getPost('id');
        
        $data = [
            'org_id' => session('org_id'),
            'head_name' => $this->request->getPost('head_name'),
            'head_type' => $this->request->getPost('head_type'),
            'gl_code' => $this->request->getPost('gl_code')
        ];

        if (empty($id)) {
            $headModel->insert($data);
        } else {
            $headModel->update($id, $data);
        }

        return redirect()->to('org/accounts/heads')->with('success', 'Account head saved.');
    }

    // Bank and Cash Accounts
    public function banks()
    {
        $bankModel = new AccountsBankModel();
        $data['banks'] = $bankModel->where('org_id', session('org_id'))->findAll();
        return view('org/accounts/banks', $data);
    }

    public function save_bank()
    {
        $bankModel = new AccountsBankModel();
        $id = $this->request->getPost('id');
        
        $data = [
            'org_id' => session('org_id'),
            'account_name' => $this->request->getPost('account_name'),
            'account_no' => $this->request->getPost('account_no'),
            'ifsc_code' => $this->request->getPost('ifsc_code'),
            'type' => $this->request->getPost('type')
        ];

        if (empty($id)) {
            $data['opening_balance'] = $this->request->getPost('opening_balance');
            $data['current_balance'] = $data['opening_balance'];
            $bankModel->insert($data);
        } else {
            $bankModel->update($id, $data);
        }

        return redirect()->to('org/accounts/banks')->with('success', 'Bank/Cash account saved.');
    }

    // Transactions (Income, Expense, Deposits)
    public function transactions()
    {
        $db = \Config\Database::connect();
        $orgId = session('org_id');

        $builder = $db->table('accounts_transactions t');
        $builder->select('t.*, h.head_name, b.account_name');
        $builder->join('accounts_heads h', 'h.id = t.head_id', 'left');
        $builder->join('accounts_banks b', 'b.id = t.bank_account_id');
        $builder->where('t.org_id', $orgId);
        $builder->orderBy('t.transaction_date', 'DESC');
        $builder->orderBy('t.id', 'DESC');
        $builder->limit(100); // Show recent 100
        
        $data['transactions'] = $builder->get()->getResultArray();
        
        $headModel = new AccountsHeadModel();
        $data['heads'] = $headModel->where('org_id', $orgId)->findAll();
        
        $bankModel = new AccountsBankModel();
        $data['banks'] = $bankModel->where('org_id', $orgId)->findAll();

        return view('org/accounts/transactions', $data);
    }

    public function save_transaction()
    {
        $transModel = new AccountsTransactionModel();
        $bankModel = new AccountsBankModel();
        $db = \Config\Database::connect();
        
        $type = $this->request->getPost('type'); // Income, Expense, Deposit, Withdrawal
        $amount = (float) $this->request->getPost('amount');
        $bankId = $this->request->getPost('bank_account_id');
        
        $transactionType = in_array($type, ['Income', 'Deposit', 'Refund']) ? 'Credit' : 'Debit';
        
        $emp = $db->table('hr_employees')->where('org_id', session('org_id'))->where('org_user_id', session('org_user_id'))->get()->getRowArray();
        $createdBy = $emp ? $emp['id'] : null;

        $data = [
            'org_id' => session('org_id'),
            'transaction_date' => $this->request->getPost('transaction_date'),
            'type' => $type,
            'head_id' => $this->request->getPost('head_id') ?: null,
            'bank_account_id' => $bankId,
            'amount' => $amount,
            'transaction_type' => $transactionType,
            'reference_no' => $this->request->getPost('reference_no'),
            'description' => $this->request->getPost('description'),
            'party_name' => $this->request->getPost('party_name'),
            'created_by' => $createdBy
        ];

        $db->transStart();
        
        $transModel->insert($data);
        
        // Update Bank Balance
        $bank = $bankModel->find($bankId);
        if ($transactionType == 'Credit') {
            $newBalance = $bank['current_balance'] + $amount;
        } else {
            $newBalance = $bank['current_balance'] - $amount;
        }
        $bankModel->update($bankId, ['current_balance' => $newBalance]);
        
        $db->transComplete();

        return redirect()->to('org/accounts/transactions')->with('success', 'Transaction recorded successfully.');
    }

    // Day Book
    public function daybook()
    {
        $date = $this->request->getGet('date') ?: date('Y-m-d');
        
        $db = \Config\Database::connect();
        $orgId = session('org_id');

        $builder = $db->table('accounts_transactions t');
        $builder->select('t.*, h.head_name, b.account_name');
        $builder->join('accounts_heads h', 'h.id = t.head_id', 'left');
        $builder->join('accounts_banks b', 'b.id = t.bank_account_id');
        $builder->where('t.org_id', $orgId);
        $builder->where('t.transaction_date', $date);
        
        $data['transactions'] = $builder->get()->getResultArray();
        $data['selected_date'] = $date;

        return view('org/accounts/daybook', $data);
    }

    // Profit & Loss
    public function pl_statement()
    {
        $startDate = $this->request->getGet('start_date') ?: date('Y-04-01'); // Assuming Financial Year starts April
        $endDate = $this->request->getGet('end_date') ?: date('Y-m-d');

        $db = \Config\Database::connect();
        $orgId = session('org_id');

        // Get Income
        $builder = $db->table('accounts_transactions t');
        $builder->select('h.head_name, SUM(t.amount) as total');
        $builder->join('accounts_heads h', 'h.id = t.head_id');
        $builder->where('t.org_id', $orgId);
        $builder->where('h.head_type', 'Income');
        $builder->where('t.transaction_date >=', $startDate);
        $builder->where('t.transaction_date <=', $endDate);
        $builder->groupBy('t.head_id');
        $data['incomes'] = $builder->get()->getResultArray();

        // Get Expenses
        $builder_ex = $db->table('accounts_transactions t');
        $builder_ex->select('h.head_name, SUM(t.amount) as total');
        $builder_ex->join('accounts_heads h', 'h.id = t.head_id');
        $builder_ex->where('t.org_id', $orgId);
        $builder_ex->where('h.head_type', 'Expense');
        $builder_ex->where('t.transaction_date >=', $startDate);
        $builder_ex->where('t.transaction_date <=', $endDate);
        $builder_ex->groupBy('t.head_id');
        $data['expenses'] = $builder_ex->get()->getResultArray();

        $data['start_date'] = $startDate;
        $data['end_date'] = $endDate;

        return view('org/accounts/pl_statement', $data);
    }
}
