<?php
namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * OrgAuthFilter — THE SINGLE CENTRAL CHECKPOINT for all organization-level access control.
 * 
 * How it works:
 * 1. Checks if user is logged in (has org session). If not → redirect to /org/login.
 * 2. Checks if the organization's subscription is active and not expired. If not → show "Suspended" page.
 * 3. If user has is_org_admin = 1 → FULL ACCESS, skip all permission checks.
 * 4. Otherwise, checks if the user has the required permission_key for the current action.
 * 
 * This is NOT scattered across controllers. Every controller simply declares what permission
 * it needs, and this filter enforces it in one place.
 */
class OrgAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        
        // --- Step 1: Is the user logged in? ---
        if (!$session->get('org_user_id')) {
            return redirect()->to(base_url('org/login'));
        }
        
        // --- Step 2: Is the organization active and not expired? ---
        $orgModel = new \App\Models\OrganizationModel();
        $org = $orgModel->find($session->get('org_id'));
        
        if (!$org || $org['status'] !== 'active') {
            $session->remove(['org_user_id', 'org_id', 'is_org_admin', 'org_user_email', 'org_user_name', 'cms_enabled', 'lms_enabled', 'user_permissions']);
            return redirect()->to(base_url('org/login'))->with('error', 'Your organization account has been suspended. Please contact the platform administrator.');
        }
        
        if (strtotime($org['subscription_end_date']) < time()) {
            $session->remove(['org_user_id', 'org_id', 'is_org_admin', 'org_user_email', 'org_user_name', 'cms_enabled', 'lms_enabled', 'user_permissions']);
            return redirect()->to(base_url('org/login'))->with('error', 'Your organization subscription has expired. Please contact the platform administrator to renew.');
        }
        
        // --- Step 2b: Keep Session Modules in Sync (Self-Correcting) ---
        $session->set('cms_enabled', (bool)$org['cms_enabled']);
        $session->set('lms_enabled', (bool)$org['lms_enabled']);
        
        // --- Step 3: is_org_admin bypass (FULL ACCESS) ---
        if ($session->get('is_org_admin')) {
            return; // Org Admin always passes — no further checks needed.
        }
        
        // --- Step 4: Permission check ---
        // If $arguments contains a required permission key, verify the user has it.
        if (!empty($arguments)) {
            $requiredPermission = $arguments[0]; // e.g. 'cms.academics.attendance.mark'
            
            // Always fetch fresh permissions on protected routes to immediately reflect mid-session changes
            $permModel = new \App\Models\PermissionModel();
            $userPermissions = $permModel->getUserPermissionKeys($session->get('org_user_id'));
            // Still update the session just in case views need it
            $session->set('user_permissions', $userPermissions);
            
            if (!in_array($requiredPermission, $userPermissions)) {
                return redirect()->to(base_url('org/dashboard'))->with('error', 'You do not have permission to perform this action.');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        $response->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0, post-check=0, pre-check=0');
        $response->setHeader('Pragma', 'no-cache');
        $response->setHeader('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
        return $response;
    }
}
