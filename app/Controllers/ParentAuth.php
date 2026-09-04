<?php
namespace App\Controllers;

use App\Models\ParentModel;
use CodeIgniter\Controller;

class ParentAuth extends Controller
{
    public function login()
    {
        return view('parent/login');
    }

    public function authenticate()
    {
        $session = session();
        $parentModel = new ParentModel();
        $db = \Config\Database::connect();
        
        $identifier = trim((string)($this->request->getPost('parent_identifier') ?: $this->request->getPost('login_id')));
        $password = $this->request->getPost('password');
        
        if (empty($identifier) || empty($password)) {
            $session->setFlashdata('error', 'Please enter your Mobile Number or Child\'s Roll Number and password.');
            return redirect()->to('parent/login');
        }

        $parent = null;

        // 1. Look up by Parent Registered Mobile Number
        $parent = $parentModel->where('phone', $identifier)->where('status', 'Active')->first();

        // 2. Look up by Parent Code (if assigned)
        if (!$parent) {
            $parent = $parentModel->where('parent_code', $identifier)->where('status', 'Active')->first();
        }

        // 3. Look up by Linked Student Roll Number
        if (!$parent) {
            $student = $db->table('students')->where('roll_number', $identifier)->get()->getRowArray();
            if ($student) {
                $map = $db->table('parent_student_map')->where('student_id', $student['id'])->get()->getRowArray();
                if ($map) {
                    $parent = $parentModel->where('id', $map['parent_id'])->where('status', 'Active')->first();
                }
            }
        }
        
        if ($parent) {
            // Check password strictly using password_verify
            if (password_verify($password, $parent['password_hash'])) {
                $sessionData = [
                    'parent_id' => $parent['id'],
                    'parent_name' => $parent['first_name'] . ' ' . $parent['last_name'],
                    'org_id' => $parent['org_id'],
                    'parent_logged_in' => true
                ];
                $session->set($sessionData);
                return redirect()->to('parent/dashboard');
            } else {
                $session->setFlashdata('error', 'Invalid password.');
                return redirect()->to('parent/login');
            }
        } else {
            $session->setFlashdata('error', 'Account not found for the provided Mobile Number / Student Roll Number.');
            return redirect()->to('parent/login');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('parent/login');
    }
}
