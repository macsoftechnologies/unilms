<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 *
 * Extend this class in any new controllers:
 * ```
 *     class Home extends BaseController
 * ```
 *
 * For security, be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */

    // protected $session;

    protected $org_id;
    protected $org_user_id;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Load here all helpers you want to be available in your controllers that extend BaseController.
        // Caution: Do not put the this below the parent::initController() call below.
        // $this->helpers = ['form', 'url'];

        // Caution: Do not edit this line.
        parent::initController($request, $response, $logger);

        // Prevent browser back-button caching for all authenticated portals
        $this->response->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0, post-check=0, pre-check=0');
        $this->response->setHeader('Pragma', 'no-cache');
        $this->response->setHeader('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');

        if (session()->has('org_id')) {
            $this->org_id = session()->get('org_id');
            $this->org_user_id = session()->get('org_user_id');
        }
    }

    protected function hasPermission($permission_code)
    {
        if (session('is_org_admin')) return true;
        $perms = session('user_permissions') ?? [];
        return in_array($permission_code, $perms);
    }

    protected function checkSuperAdminPermission($module)
    {
        if (session()->get('admin_is_root') == 1) {
            return true;
        }
        
        $permissions = session()->get('admin_permissions') ?? [];
        if (!in_array($module, $permissions)) {
            echo view('super_admin/layout', ['title' => 'Access Denied']) . '<div style="padding:40px; text-align:center;"><h3>Access Denied</h3><p>You do not have permission to view this section.</p></div>';
            exit;
        }
        return true;
    }
}
