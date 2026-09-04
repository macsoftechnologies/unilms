<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Filters\ApiAuthFilter;
use App\Models\OrgUserModel;
use App\Models\StudentModel;
use App\Models\ParentModel;
use App\Models\OrganizationModel;

class AuthApiController extends BaseController
{
    public function login()
    {
        $raw = $this->request->getBody();
        $json = [];
        if (!empty($raw)) {
            $decoded = json_decode($raw, true);
            if (is_array($decoded)) {
                $json = $decoded;
            }
        }
        $post = $this->request->getPost() ?: [];
        $data = array_merge($post, $json);

        $email = trim($data['email'] ?? $data['identifier'] ?? '');
        $password = trim($data['password'] ?? '');
        $userType = strtolower(trim($data['user_type'] ?? 'student')); // 'student' or 'parent'

        if (empty($email) || empty($password)) {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error',
                'message' => 'Email/Mobile and password are required.'
            ]);
        }

        $db = \Config\Database::connect();

        if ($userType === 'parent') {
            // Check Parent Account
            $parent = (new ParentModel())
                ->where('email', $email)
                ->orWhere('phone', $email)
                ->first();

            if (!$parent || !password_verify($password, $parent['password_hash'])) {
                return $this->response->setStatusCode(401)->setJSON([
                    'status' => 'error',
                    'message' => 'Invalid parent credentials.'
                ]);
            }

            if ($parent['status'] !== 'Active') {
                return $this->response->setStatusCode(403)->setJSON([
                    'status' => 'error',
                    'message' => 'Your parent account has been deactivated.'
                ]);
            }

            $org = (new OrganizationModel())->find($parent['org_id']);
            if (!$org || $org['status'] !== 'active') {
                return $this->response->setStatusCode(403)->setJSON([
                    'status' => 'error',
                    'message' => 'Institution subscription is inactive.'
                ]);
            }

            // Fetch linked student wards
            $wards = $db->table('parent_student_map psm')
                ->select('s.id, s.roll_number, s.first_name, s.last_name, s.cohort_id, psm.relationship, c.name as cohort_name, pr.name as program_name')
                ->join('students s', 's.id = psm.student_id')
                ->join('cohorts c', 'c.id = s.cohort_id', 'left')
                ->join('programs pr', 'pr.id = c.program_id', 'left')
                ->where('psm.parent_id', $parent['id'])
                ->get()->getResultArray();

            $tokenPayload = [
                'parent_id' => (int)$parent['id'],
                'user_id' => (int)$parent['id'],
                'org_id' => (int)$parent['org_id'],
                'user_type' => 'parent',
                'email' => $parent['email'],
                'name' => trim(($parent['first_name'] ?? '') . ' ' . ($parent['last_name'] ?? ''))
            ];

            $token = ApiAuthFilter::createToken($tokenPayload);

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Parent authenticated successfully.',
                'token' => $token,
                'token_type' => 'Bearer',
                'user_type' => 'parent',
                'user' => [
                    'id' => $parent['id'],
                    'name' => trim(($parent['first_name'] ?? '') . ' ' . ($parent['last_name'] ?? '')),
                    'email' => $parent['email'],
                    'phone' => $parent['phone']
                ],
                'wards' => $wards,
                'organization' => [
                    'id' => $org['id'],
                    'name' => $org['name']
                ]
            ]);

        } else {
            // Student Login
            $user = (new OrgUserModel())
                ->where('email', $email)
                ->orWhere('phone', $email)
                ->first();

            if (!$user || !password_verify($password, $user['password_hash'])) {
                return $this->response->setStatusCode(401)->setJSON([
                    'status' => 'error',
                    'message' => 'Invalid student credentials.'
                ]);
            }

            $student = (new StudentModel())
                ->select('students.*, c.name as cohort_name, p.name as program_name')
                ->join('cohorts c', 'c.id = students.cohort_id', 'left')
                ->join('programs p', 'p.id = c.program_id', 'left')
                ->where('students.user_id', $user['id'])
                ->first();

            if (!$student) {
                // Fallback: check by roll number
                $student = (new StudentModel())
                    ->select('students.*, c.name as cohort_name, p.name as program_name')
                    ->join('cohorts c', 'c.id = students.cohort_id', 'left')
                    ->join('programs p', 'p.id = c.program_id', 'left')
                    ->where('students.org_id', $user['org_id'])
                    ->where('students.roll_number', $email)
                    ->first();
            }

            $org = (new OrganizationModel())->find($user['org_id']);
            if (!$org || $org['status'] !== 'active') {
                return $this->response->setStatusCode(403)->setJSON([
                    'status' => 'error',
                    'message' => 'Institution subscription is inactive.'
                ]);
            }

            $tokenPayload = [
                'user_id' => (int)$user['id'],
                'student_id' => $student ? (int)$student['id'] : null,
                'cohort_id' => $student ? (int)$student['cohort_id'] : null,
                'org_id' => (int)$user['org_id'],
                'user_type' => 'student',
                'email' => $user['email'],
                'name' => $user['full_name']
            ];

            $token = ApiAuthFilter::createToken($tokenPayload);

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Student authenticated successfully.',
                'token' => $token,
                'token_type' => 'Bearer',
                'user_type' => 'student',
                'user' => [
                    'id' => $user['id'],
                    'name' => $user['full_name'],
                    'email' => $user['email'],
                    'phone' => $user['phone']
                ],
                'student' => $student,
                'organization' => [
                    'id' => $org['id'],
                    'name' => $org['name']
                ]
            ]);
        }
    }

    public function me()
    {
        $user = $this->request->api_user ?? null;
        if (!$user) {
            return $this->response->setStatusCode(401)->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'user' => $user
        ]);
    }

    public function logout()
    {
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Session terminated successfully.'
        ]);
    }
}
