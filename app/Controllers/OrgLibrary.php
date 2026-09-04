<?php
namespace App\Controllers;

use App\Models\LibraryBookModel;
use App\Models\LibraryMemberModel;
use App\Models\LibraryIssueModel;
use App\Models\LibraryReservationModel;
use App\Models\LibraryCategoryModel;
use App\Models\LibrarySupplierModel;
use App\Models\LibraryPeriodicalModel;
use App\Models\LibraryStockModel;

class OrgLibrary extends BaseController
{
    public function index()
    {
        $orgId = session()->get('org_id');
        $bookModel = new LibraryBookModel();
        
        $data['total_books'] = $bookModel->where('org_id', $orgId)->countAllResults();
        
        $db = \Config\Database::connect();
        $data['total_issued'] = $db->table('library_issues')->where('org_id', $orgId)->where('status', 'issued')->countAllResults();
        $data['activeModule'] = 'library';
        return view('org/library/index', $data);
    }
    
    public function books()
    {
        $orgId = session()->get('org_id');
        $bookModel = new LibraryBookModel();
        
        $data['books'] = $bookModel->where('org_id', $orgId)->findAll();
        $data['activeModule'] = 'library';
        return view('org/library/books', $data);
    }
    
    public function save_book()
    {
        $orgId = session()->get('org_id');
        $bookModel = new LibraryBookModel();
        
        $id = $this->request->getPost('id');
        $data = [
            'org_id' => $orgId,
            'accession_no' => $this->request->getPost('accession_no'),
            'title' => $this->request->getPost('title'),
            'author' => $this->request->getPost('author'),
            'publisher' => $this->request->getPost('publisher'),
            'publication_year' => $this->request->getPost('publication_year'),
            'edition' => $this->request->getPost('edition'),
            'isbn' => $this->request->getPost('isbn'),
            'subject' => $this->request->getPost('subject'),
            'category' => $this->request->getPost('category'),
            'rack_no' => $this->request->getPost('rack_no'),
            'copies' => $this->request->getPost('copies'),
            'status' => $this->request->getPost('status') ?: 'available'
        ];
        
        if (empty($id)) {
            $bookModel->insert($data);
        } else {
            $bookModel->update($id, $data);
        }
        
        return redirect()->to(base_url('org/library/books'))->with('success', 'Book saved successfully.');
    }
    
    public function members()
    {
        $orgId = session()->get('org_id');
        
        $db = \Config\Database::connect();
        $builder = $db->table('library_members lm');
        $builder->select('lm.*, s.first_name, s.last_name, s.roll_number, u.full_name as staff_name');
        $builder->join('students s', 's.id = lm.student_id', 'left');
        $builder->join('org_users u', 'u.id = lm.staff_id', 'left');
        $builder->where('lm.org_id', $orgId);
        $data['members'] = $builder->get()->getResultArray();
        
        $data['students'] = $db->table('students')->select('id, first_name, last_name, roll_number')->where('org_id', $orgId)->get()->getResultArray();
        $data['staff'] = $db->table('org_users')->select('id, full_name')->where('org_id', $orgId)->get()->getResultArray();
        $data['activeModule'] = 'library';
        return view('org/library/members', $data);
    }
    
    public function save_member()
    {
        $orgId = session()->get('org_id');
        $memberModel = new LibraryMemberModel();
        
        $id = $this->request->getPost('id');
        $type = $this->request->getPost('member_type');
        $data = [
            'org_id' => $orgId,
            'member_type' => $type,
            'student_id' => ($type == 'student') ? $this->request->getPost('student_id') : null,
            'staff_id' => ($type == 'staff') ? $this->request->getPost('staff_id') : null,
            'max_books_allowed' => $this->request->getPost('max_books_allowed'),
            'valid_till' => $this->request->getPost('valid_till'),
            'status' => $this->request->getPost('status') ?: 'active'
        ];
        
        if (empty($id)) {
            $memberModel->insert($data);
        } else {
            $memberModel->update($id, $data);
        }
        
        return redirect()->to(base_url('org/library/members'))->with('success', 'Member saved successfully.');
    }
    
    public function issues()
    {
        $orgId = session()->get('org_id');
        
        $db = \Config\Database::connect();
        $builder = $db->table('library_issues li');
        $builder->select('li.*, lb.title, lb.accession_no, lm.member_type, s.first_name, s.last_name, u.full_name as staff_name');
        $builder->join('library_books lb', 'lb.id = li.book_id');
        $builder->join('library_members lm', 'lm.id = li.member_id');
        $builder->join('students s', 's.id = lm.student_id', 'left');
        $builder->join('org_users u', 'u.id = lm.staff_id', 'left');
        $builder->where('li.org_id', $orgId);
        $builder->orderBy('li.issue_date', 'DESC');
        $data['issues'] = $builder->get()->getResultArray();
        
        $data['books'] = $db->table('library_books')->where('org_id', $orgId)->where('status', 'available')->get()->getResultArray();
        $data['members'] = $db->table('library_members lm')
                              ->select('lm.id, lm.member_type, s.first_name, s.last_name, s.roll_number, u.full_name as staff_name')
                              ->join('students s', 's.id = lm.student_id', 'left')
                              ->join('org_users u', 'u.id = lm.staff_id', 'left')
                              ->where('lm.org_id', $orgId)
                              ->where('lm.status', 'active')
                              ->get()->getResultArray();
        $data['activeModule'] = 'library';                      
        return view('org/library/issues', $data);
    }
    
    public function issue_book()
    {
        $orgId = session()->get('org_id');
        $issueModel = new LibraryIssueModel();
        $bookModel = new LibraryBookModel();
        
        $bookId = $this->request->getPost('book_id');
        
        $data = [
            'org_id' => $orgId,
            'book_id' => $bookId,
            'member_id' => $this->request->getPost('member_id'),
            'issue_date' => $this->request->getPost('issue_date') ?: date('Y-m-d'),
            'due_date' => $this->request->getPost('due_date'),
            'issued_by' => session()->get('org_user_id'),
            'status' => 'issued'
        ];
        
        $issueModel->insert($data);
        $bookModel->update($bookId, ['status' => 'issued']);
        
        return redirect()->to(base_url('org/library/issues'))->with('success', 'Book issued successfully.');
    }
    
    public function return_book()
    {
        $orgId = session()->get('org_id');
        $issueModel = new LibraryIssueModel();
        $bookModel = new LibraryBookModel();
        
        $id = $this->request->getPost('id');
        $bookId = $this->request->getPost('book_id');
        $finePaid = $this->request->getPost('fine_paid') ? 1 : 0;
        
        $data = [
            'return_date' => date('Y-m-d'),
            'status' => 'returned',
            'fine_paid' => $finePaid
        ];
        
        $issueModel->update($id, $data);
        $bookModel->update($bookId, ['status' => 'available']);
        
        return redirect()->to(base_url('org/library/issues'))->with('success', 'Book returned successfully.');
    }

    public function categories()
    {
        $orgId = session()->get('org_id');
        $model = new LibraryCategoryModel();
        
        if ($this->request->getMethod() === 'post') {
            $data = [
                'org_id' => $orgId,
                'name' => $this->request->getPost('name'),
                'description' => $this->request->getPost('description')
            ];
            $id = $this->request->getPost('id');
            if ($id) $model->update($id, $data);
            else $model->insert($data);
            return redirect()->to(base_url('org/library/categories'))->with('success', 'Category saved.');
        }

        $data['categories'] = $model->where('org_id', $orgId)->findAll();
        $data['activeModule'] = 'library';
        return view('org/library/categories', $data);
    }

    public function suppliers()
    {
        $orgId = session()->get('org_id');
        $model = new LibrarySupplierModel();
        
        if ($this->request->getMethod() === 'post') {
            $data = [
                'org_id' => $orgId,
                'name' => $this->request->getPost('name'),
                'contact_person' => $this->request->getPost('contact_person'),
                'phone' => $this->request->getPost('phone'),
                'email' => $this->request->getPost('email'),
                'address' => $this->request->getPost('address')
            ];
            $id = $this->request->getPost('id');
            if ($id) $model->update($id, $data);
            else $model->insert($data);
            return redirect()->to(base_url('org/library/suppliers'))->with('success', 'Supplier saved.');
        }

        $data['suppliers'] = $model->where('org_id', $orgId)->findAll();
        $data['activeModule'] = 'library';
        return view('org/library/suppliers', $data);
    }

    public function periodicals()
    {
        $orgId = session()->get('org_id');
        $model = new LibraryPeriodicalModel();
        
        if ($this->request->getMethod() === 'post') {
            $data = [
                'org_id' => $orgId,
                'title' => $this->request->getPost('title'),
                'issn' => $this->request->getPost('issn'),
                'frequency' => $this->request->getPost('frequency'),
                'subscription_date' => $this->request->getPost('subscription_date'),
                'valid_till' => $this->request->getPost('valid_till'),
                'copies' => $this->request->getPost('copies'),
                'status' => $this->request->getPost('status') ?: 'active'
            ];
            $id = $this->request->getPost('id');
            if ($id) $model->update($id, $data);
            else $model->insert($data);
            return redirect()->to(base_url('org/library/periodicals'))->with('success', 'Periodical saved.');
        }

        $data['periodicals'] = $model->where('org_id', $orgId)->findAll();
        $data['activeModule'] = 'library';
        return view('org/library/periodicals', $data);
    }

    public function stock()
    {
        $orgId = session()->get('org_id');
        $model = new LibraryStockModel();
        
        if ($this->request->getMethod() === 'post') {
            $data = [
                'org_id' => $orgId,
                'rack_no' => $this->request->getPost('rack_no'),
                'expected_count' => $this->request->getPost('expected_count'),
                'physical_count' => $this->request->getPost('physical_count'),
                'missing_books' => $this->request->getPost('missing_books'),
                'verified_by' => session()->get('org_user_id'),
                'verified_on' => date('Y-m-d')
            ];
            $id = $this->request->getPost('id');
            if ($id) $model->update($id, $data);
            else $model->insert($data);
            return redirect()->to(base_url('org/library/stock'))->with('success', 'Stock verification saved.');
        }

        $data['verifications'] = $model->where('org_id', $orgId)->findAll();
        $data['activeModule'] = 'library';
        return view('org/library/stock', $data);
    }
}
