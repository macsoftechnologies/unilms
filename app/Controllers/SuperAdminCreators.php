<?php
namespace App\Controllers;

use App\Models\CreatorUserModel;
use App\Models\OrganizationModel;
use App\Models\SuperadminActivityLogModel;

class SuperAdminCreators extends BaseController
{
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        
        if (!session()->get('is_superadmin')) {
            header('Location: ' . base_url('superadmin/login'));
            exit;
        }
    }

    public function index()
    {
        $creatorModel = new CreatorUserModel();
        $orgModel = new OrganizationModel();
        $db = \Config\Database::connect();

        $creators = $creatorModel->orderBy('created_at', 'DESC')->findAll();
        $organizations = $orgModel->where('status', 'active')->orderBy('name', 'ASC')->findAll();

        // Attach organization name and course count
        foreach ($creators as &$creator) {
            if (!empty($creator['org_id'])) {
                $org = $orgModel->find($creator['org_id']);
                $creator['org_name'] = $org['name'] ?? 'Unknown Organization';
            } else {
                $creator['org_name'] = 'Global (All Organizations)';
            }

            $creator['course_count'] = $db->table('creator_courses')
                ->where('creator_id', $creator['id'])
                ->countAllResults();
        }

        $data = [
            'title' => 'Content Creators Management',
            'creators' => $creators,
            'organizations' => $organizations
        ];

        return view('super_admin/creators', $data);
    }

    public function save()
    {
        $creatorModel = new CreatorUserModel();
        $logModel = new SuperadminActivityLogModel();
        
        $id = $this->request->getPost('creator_id');
        $name = trim($this->request->getPost('name'));
        $email = trim($this->request->getPost('email'));
        $password = $this->request->getPost('password');
        $orgId = $this->request->getPost('org_id');
        $status = $this->request->getPost('status') ?: 'active';

        $data = [
            'name'   => $name,
            'email'  => $email,
            'org_id' => !empty($orgId) ? (int)$orgId : null,
            'status' => $status
        ];

        if (!empty($password)) {
            $data['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
        }

        if (empty($id)) {
            // Check if email already exists
            if ($creatorModel->where('email', $email)->first()) {
                return redirect()->back()->with('error', 'A creator with this email address already exists.');
            }
            if (empty($password)) {
                return redirect()->back()->with('error', 'Password is required when creating a new content creator.');
            }

            $newId = $creatorModel->insert($data);
            $logModel->logAction(session('admin_id') ?: 1, 'Created Content Creator', "Creator: {$name} ({$email})");
            $msg = 'Content Creator created successfully.';
        } else {
            // Check if email belongs to another creator
            $existing = $creatorModel->where('email', $email)->where('id !=', $id)->first();
            if ($existing) {
                return redirect()->back()->with('error', 'Email is already taken by another creator.');
            }

            $creatorModel->update($id, $data);
            $logModel->logAction(session('admin_id') ?: 1, 'Updated Content Creator', "Creator: {$name} ({$email})");
            $msg = 'Content Creator updated successfully.';
        }

        return redirect()->to('superadmin/creators')->with('success', $msg);
    }

    public function delete($id)
    {
        $creatorModel = new CreatorUserModel();
        $logModel = new SuperadminActivityLogModel();

        $creator = $creatorModel->find($id);
        if ($creator) {
            $creatorModel->delete($id);
            $logModel->logAction(session('admin_id') ?: 1, 'Deleted Content Creator', "Creator: {$creator['name']} ({$creator['email']})");
            return redirect()->to('superadmin/creators')->with('success', 'Content Creator removed successfully.');
        }

        return redirect()->to('superadmin/creators')->with('error', 'Creator not found.');
    }
}
