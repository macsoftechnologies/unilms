<?php
namespace App\Controllers;

use App\Models\MessageTemplateModel;
use App\Models\FestivalGreetingModel;
use App\Models\InappNotificationModel;
use App\Models\OrgUserModel;

class OrgCorrespondence extends BaseController
{
    public function index()
    {
        $orgId = session()->get('org_id');
        $notifModel = new InappNotificationModel();
        
        $data['activeModule'] = 'correspondence';
        $data['active_menu'] = 'correspondence';
        $data['active_submenu'] = 'dashboard';
        
        // Fetch recent system-wide notifications for dashboard audit (latest 50)
        $data['recent_logs'] = $notifModel->select('inapp_notifications.*, org_users.full_name as recipient_name')
            ->join('org_users', 'org_users.id = inapp_notifications.user_id')
            ->where('inapp_notifications.org_id', $orgId)
            ->orderBy('inapp_notifications.created_at', 'DESC')
            ->limit(50)
            ->findAll();

        return view('org/correspondence/index', $data);
    }

    public function templates()
    {
        $orgId = session()->get('org_id');
        $templateModel = new MessageTemplateModel();
        
        $data['activeModule'] = 'correspondence';
        $data['active_menu'] = 'correspondence';
        $data['active_submenu'] = 'templates';
        
        $data['templates'] = $templateModel->where('org_id', $orgId)->findAll();
        
        return view('org/correspondence/templates', $data);
    }

    public function save_template()
    {
        $templateModel = new MessageTemplateModel();
        $id = $this->request->getPost('id');
        
        $data = [
            'org_id' => session()->get('org_id'),
            'name' => $this->request->getPost('name'),
            'type' => $this->request->getPost('type'),
            'body_text' => $this->request->getPost('body_text')
        ];

        if ($id) {
            $templateModel->update($id, $data);
        } else {
            $templateModel->insert($data);
        }
        return redirect()->to('org/correspondence/templates')->with('success', 'Template saved successfully.');
    }

    public function delete_template($id)
    {
        $templateModel = new MessageTemplateModel();
        $templateModel->where('org_id', session()->get('org_id'))->delete($id);
        return redirect()->to('org/correspondence/templates')->with('success', 'Template deleted.');
    }

    public function festivals()
    {
        $orgId = session()->get('org_id');
        $festivalModel = new FestivalGreetingModel();
        $templateModel = new MessageTemplateModel();
        
        $data['activeModule'] = 'correspondence';
        $data['active_menu'] = 'correspondence';
        $data['active_submenu'] = 'festivals';
        
        $data['festivals'] = $festivalModel->select('festival_greetings.*, message_templates.name as template_name')
            ->join('message_templates', 'message_templates.id = festival_greetings.template_id')
            ->where('festival_greetings.org_id', $orgId)
            ->findAll();
            
        $data['templates'] = $templateModel->where('org_id', $orgId)->where('type', 'Festival')->findAll();
        
        return view('org/correspondence/festivals', $data);
    }

    public function save_festival()
    {
        $festivalModel = new FestivalGreetingModel();
        $notifModel = new InappNotificationModel();
        $userModel = new OrgUserModel();
        $templateModel = new MessageTemplateModel();
        
        $orgId = session()->get('org_id');
        $id = $this->request->getPost('id');
        
        $template_id = $this->request->getPost('template_id');
        $audience = $this->request->getPost('audience');
        $festival_name = $this->request->getPost('festival_name');
        
        $data = [
            'org_id' => $orgId,
            'festival_name' => $festival_name,
            'send_date' => $this->request->getPost('send_date'),
            'template_id' => $template_id,
            'audience' => $audience,
            'status' => 'Sent' // Mocking immediate send for initial build
        ];

        if ($id) {
            $festivalModel->update($id, $data);
        } else {
            $festivalModel->insert($data);
            
            // Mocking the immediate send via In-App Notification
            $template = $templateModel->find($template_id);
            if ($template) {
                // Find audience users
                $usersQuery = $userModel->where('org_id', $orgId);
                if ($audience == 'Students') {
                    $usersQuery->where('role', 'student');
                } elseif ($audience == 'Staff') {
                    $usersQuery->whereIn('role', ['faculty', 'admin', 'superadmin']);
                }
                
                $users = $usersQuery->findAll();
                
                $notifications = [];
                foreach($users as $u) {
                    // Replace placeholders
                    $body = str_replace('{name}', $u['full_name'], $template['body_text']);
                    
                    $notifications[] = [
                        'org_id' => $orgId,
                        'user_id' => $u['id'],
                        'title' => 'Happy ' . $festival_name . '!',
                        'message' => $body,
                        'is_read' => 0
                    ];
                }
                
                if (count($notifications) > 0) {
                    $notifModel->insertBatch($notifications);
                }
            }
        }
        return redirect()->to('org/correspondence/festivals')->with('success', 'Festival campaign saved and notifications dispatched.');
    }

    public function unread_count()
    {
        $userId = session('org_user_id');
        if (!$userId) return $this->response->setJSON(['status' => 'success', 'unread' => 0]);

        $db = \Config\Database::connect();
        $count = $db->table('inapp_notifications')
            ->where('user_id', $userId)
            ->where('is_read', 0)
            ->countAllResults();

        return $this->response->setJSON(['status' => 'success', 'unread' => $count]);
    }
}
