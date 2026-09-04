<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Models\OrganizationModel;
use App\Models\ParentModel;

class ParentAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        
        if (!$session->get('parent_logged_in') || !$session->get('parent_id')) {
            return redirect()->to(base_url('parent/login'))->with('error', 'Please log in to access the Parent Portal.');
        }

        // Verify parent account is still active
        $parentModel = new ParentModel();
        $parent = $parentModel->find($session->get('parent_id'));
        if (!$parent || $parent['status'] !== 'Active') {
            $session->destroy();
            return redirect()->to(base_url('parent/login'))->with('error', 'Your parent portal account is inactive.');
        }

        // Verify institution status
        $orgModel = new OrganizationModel();
        $org = $orgModel->find($session->get('org_id'));
        if (!$org || $org['status'] !== 'active') {
            $session->destroy();
            return redirect()->to(base_url('parent/login'))->with('error', 'The institution account is currently inactive.');
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        $response->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0, post-check=0, pre-check=0');
        $response->setHeader('Pragma', 'no-cache');
        $response->setHeader('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
        return $response;
    }
}
