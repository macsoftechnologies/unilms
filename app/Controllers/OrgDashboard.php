<?php
namespace App\Controllers;

use App\Models\OrganizationModel;
use App\Models\OrgUserModel;

class OrgDashboard extends BaseController
{
    public function index()
    {
        $orgModel = new OrganizationModel();
        $userModel = new OrgUserModel();
        
        $orgId = session()->get('org_id');
        
        $data['org'] = $orgModel->find($orgId);
        $data['total_users'] = $userModel->where('org_id', $orgId)->countAllResults();
        
        return view('org/dashboard', $data);
    }
}
