<?php
namespace App\Controllers;

use App\Models\HostelModel;
use App\Models\HostelRoomModel;
use App\Models\HostelRegistrationModel;
use App\Models\HostelOutingModel;
use App\Models\StudentModel;

class OrgHostel extends BaseController
{
    public function index()
    {
        $orgId = session()->get('org_id');
        $hostelModel = new HostelModel();
        
        $data['hostels'] = $hostelModel->where('org_id', $orgId)->findAll();
        
        return view('org/hostel/index', $data);
    }
    
    public function save_hostel()
    {
        $orgId = session()->get('org_id');
        $hostelModel = new HostelModel();
        
        $id = $this->request->getPost('id');
        $data = [
            'org_id' => $orgId,
            'name' => $this->request->getPost('name'),
            'type' => $this->request->getPost('type'),
            'location' => $this->request->getPost('location'),
            'warden_name' => $this->request->getPost('warden_name'),
            'warden_contact' => $this->request->getPost('warden_contact'),
            'capacity' => $this->request->getPost('capacity')
        ];
        
        if (empty($id)) {
            $hostelModel->insert($data);
        } else {
            $hostelModel->update($id, $data);
        }
        
        return redirect()->to(base_url('org/hostel'))->with('success', 'Hostel saved successfully.');
    }
    
    public function rooms()
    {
        $orgId = session()->get('org_id');
        $hostelModel = new HostelModel();
        $roomModel = new HostelRoomModel();
        
        $data['hostels'] = $hostelModel->where('org_id', $orgId)->findAll();
        
        $db = \Config\Database::connect();
        $builder = $db->table('hostel_rooms hr');
        $builder->select('hr.*, h.name as hostel_name');
        $builder->join('hostels h', 'h.id = hr.hostel_id');
        $builder->where('hr.org_id', $orgId);
        $data['rooms'] = $builder->get()->getResultArray();
        
        return view('org/hostel/rooms', $data);
    }
    
    public function save_room()
    {
        $orgId = session()->get('org_id');
        $roomModel = new HostelRoomModel();
        
        $id = $this->request->getPost('id');
        $data = [
            'org_id' => $orgId,
            'hostel_id' => $this->request->getPost('hostel_id'),
            'room_no' => $this->request->getPost('room_no'),
            'floor' => $this->request->getPost('floor'),
            'type' => $this->request->getPost('type'),
            'capacity' => $this->request->getPost('capacity'),
            'has_ac' => $this->request->getPost('has_ac') ? 1 : 0
        ];
        
        if (empty($id)) {
            $roomModel->insert($data);
        } else {
            $roomModel->update($id, $data);
        }
        
        return redirect()->to(base_url('org/hostel/rooms'))->with('success', 'Room saved successfully.');
    }
    
    public function registrations()
    {
        $orgId = session()->get('org_id');
        
        $db = \Config\Database::connect();
        $builder = $db->table('hostel_registrations hr');
        $builder->select('hr.*, h.name as hostel_name, r.room_no, s.first_name, s.last_name, s.roll_number');
        $builder->join('hostels h', 'h.id = hr.hostel_id');
        $builder->join('hostel_rooms r', 'r.id = hr.room_id');
        $builder->join('students s', 's.id = hr.student_id');
        $builder->where('hr.org_id', $orgId);
        $data['registrations'] = $builder->get()->getResultArray();
        
        $data['hostels'] = $db->table('hostels')->where('org_id', $orgId)->get()->getResultArray();
        $data['rooms'] = $db->table('hostel_rooms')->where('org_id', $orgId)->get()->getResultArray();
        $data['students'] = $db->table('students')->select('id, first_name, last_name, roll_number')->where('org_id', $orgId)->get()->getResultArray();
        
        return view('org/hostel/registrations', $data);
    }
    
    public function save_registration()
    {
        $orgId = session()->get('org_id');
        $regModel = new HostelRegistrationModel();
        
        $id = $this->request->getPost('id');
        $data = [
            'org_id' => $orgId,
            'student_id' => $this->request->getPost('student_id'),
            'hostel_id' => $this->request->getPost('hostel_id'),
            'room_id' => $this->request->getPost('room_id'),
            'bed_no' => $this->request->getPost('bed_no'),
            'joining_date' => $this->request->getPost('joining_date'),
            'academic_year_id' => 1, // simplified for now
            'emergency_contact' => $this->request->getPost('emergency_contact'),
            'status' => $this->request->getPost('status') ?: 'active'
        ];
        
        if (empty($id)) {
            $regModel->insert($data);
        } else {
            $regModel->update($id, $data);
        }
        
        return redirect()->to(base_url('org/hostel/registrations'))->with('success', 'Registration saved successfully.');
    }
    
    public function outings()
    {
        $orgId = session()->get('org_id');
        
        $db = \Config\Database::connect();
        $builder = $db->table('hostel_outings ho');
        $builder->select('ho.*, s.first_name, s.last_name, s.roll_number');
        $builder->join('students s', 's.id = ho.student_id');
        $builder->where('ho.org_id', $orgId);
        $builder->orderBy('ho.date_out', 'DESC');
        $data['outings'] = $builder->get()->getResultArray();
        
        return view('org/hostel/outings', $data);
    }
    
    public function update_outing_status()
    {
        $orgId = session()->get('org_id');
        $outingModel = new HostelOutingModel();
        
        $id = $this->request->getPost('id');
        $status = $this->request->getPost('status');
        
        $data = [
            'status' => $status
        ];
        
        if ($status == 'returned') {
            $data['actual_return'] = date('Y-m-d H:i:s');
        }
        
        $outingModel->update($id, $data);
        
        return redirect()->to(base_url('org/hostel/outings'))->with('success', 'Outing status updated.');
    }
}
