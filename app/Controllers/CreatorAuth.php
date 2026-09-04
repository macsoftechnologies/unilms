<?php

namespace App\Controllers;

use App\Models\CreatorUserModel;

class CreatorAuth extends BaseController
{
    public function login()
    {
        if (session()->get('is_creator_logged_in')) {
            return redirect()->to('/creator/courses');
        }
        return view('creator/login');
    }

    public function authenticate()
    {
        $email = trim($this->request->getPost('email'));
        $password = $this->request->getPost('password');

        $userModel = new CreatorUserModel();
        $creator = $userModel->findByEmail($email);

        if (!$creator) {
            return redirect()->back()->with('error', 'Invalid creator credentials or inactive account.');
        }

        if (password_verify($password, $creator['password_hash'])) {
            session()->set([
                'is_creator_logged_in' => true,
                'creator_id'           => $creator['id'],
                'creator_name'         => $creator['name'],
                'creator_email'        => $creator['email'],
                'creator_org_id'       => $creator['org_id'] ?? null
            ]);

            return redirect()->to('/creator/courses')->with('success', "Welcome to the Creator Studio, {$creator['name']}!");
        }

        return redirect()->back()->with('error', 'Invalid email or password.');
    }

    public function logout()
    {
        session()->remove(['is_creator_logged_in', 'creator_id', 'creator_name', 'creator_email', 'creator_org_id']);
        return redirect()->to('/creator/login')->with('success', 'Logged out of Creator Studio.');
    }
}
