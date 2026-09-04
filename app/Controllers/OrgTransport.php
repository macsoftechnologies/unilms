<?php

namespace App\Controllers;

use App\Models\TransportVehicleModel;
use App\Models\TransportRouteModel;
use App\Models\TransportHaltModel;
use App\Models\TransportSubscriptionModel;
use App\Models\TransportLogbookModel;

class OrgTransport extends BaseController
{
    protected $vehicleModel;
    protected $routeModel;
    protected $haltModel;
    protected $subscriptionModel;
    protected $logbookModel;

    public function __construct()
    {
        $this->vehicleModel = new TransportVehicleModel();
        $this->routeModel = new TransportRouteModel();
        $this->haltModel = new TransportHaltModel();
        $this->subscriptionModel = new TransportSubscriptionModel();
        $this->logbookModel = new TransportLogbookModel();
    }

    private function getOrgId()
    {
        return session()->get('org_id');
    }

    // -------------------------------------------------------------------------
    // VEHICLES
    // -------------------------------------------------------------------------
    public function vehicles()
    {
        $orgId = $this->getOrgId();
        if (!$orgId) return redirect()->to('/org/login');

        if ($this->request->getMethod() === 'post') {
            $data = [
                'org_id' => $orgId,
                'vehicle_no' => $this->request->getPost('vehicle_no'),
                'type' => $this->request->getPost('type'),
                'capacity' => $this->request->getPost('capacity'),
                'model_year' => $this->request->getPost('model_year'),
                'fuel_type' => $this->request->getPost('fuel_type'),
                'owner_status' => $this->request->getPost('owner_status'),
            ];

            $id = $this->request->getPost('id');
            if ($id) {
                $this->vehicleModel->update($id, $data);
                session()->setFlashdata('success', 'Vehicle updated successfully.');
            } else {
                $this->vehicleModel->insert($data);
                session()->setFlashdata('success', 'Vehicle added successfully.');
            }
            return redirect()->to('/org/transport/vehicles');
        }

        $data['vehicles'] = $this->vehicleModel->where('org_id', $orgId)->findAll();
        $data['activeModule'] = 'transport';
        $data['active_menu'] = 'transport';
        $data['active_submenu'] = 'vehicles';
        
        return view('org/transport/vehicles', $data);
    }

    public function delete_vehicle($id)
    {
        $this->vehicleModel->where('org_id', $this->getOrgId())->delete($id);
        session()->setFlashdata('success', 'Vehicle deleted.');
        return redirect()->to('/org/transport/vehicles');
    }

    // -------------------------------------------------------------------------
    // ROUTES
    // -------------------------------------------------------------------------
    public function routes()
    {
        $orgId = $this->getOrgId();
        if (!$orgId) return redirect()->to('/org/login');

        if ($this->request->getMethod() === 'post') {
            $data = [
                'org_id' => $orgId,
                'name' => $this->request->getPost('name'),
                'start_point' => $this->request->getPost('start_point'),
                'end_point' => $this->request->getPost('end_point'),
            ];

            $id = $this->request->getPost('id');
            if ($id) {
                $this->routeModel->update($id, $data);
                session()->setFlashdata('success', 'Route updated successfully.');
            } else {
                $this->routeModel->insert($data);
                session()->setFlashdata('success', 'Route added successfully.');
            }
            return redirect()->to('/org/transport/routes');
        }

        $data['routes'] = $this->routeModel->where('org_id', $orgId)->findAll();
        $data['activeModule'] = 'transport';
        $data['active_menu'] = 'transport';
        $data['active_submenu'] = 'routes';
        
        return view('org/transport/routes', $data);
    }

    public function delete_route($id)
    {
        $this->routeModel->where('org_id', $this->getOrgId())->delete($id);
        session()->setFlashdata('success', 'Route deleted.');
        return redirect()->to('/org/transport/routes');
    }

    // -------------------------------------------------------------------------
    // HALTS
    // -------------------------------------------------------------------------
    public function halts()
    {
        $orgId = $this->getOrgId();
        if (!$orgId) return redirect()->to('/org/login');

        if ($this->request->getMethod() === 'post') {
            $data = [
                'org_id' => $orgId,
                'route_id' => $this->request->getPost('route_id'),
                'name' => $this->request->getPost('name'),
                'distance_km' => $this->request->getPost('distance_km'),
                'annual_fee' => $this->request->getPost('annual_fee'),
            ];

            $id = $this->request->getPost('id');
            if ($id) {
                $this->haltModel->update($id, $data);
                session()->setFlashdata('success', 'Halt updated successfully.');
            } else {
                $this->haltModel->insert($data);
                session()->setFlashdata('success', 'Halt added successfully.');
            }
            return redirect()->to('/org/transport/halts');
        }

        $db = \Config\Database::connect();
        $data['halts'] = $db->table('transport_halts th')
            ->select('th.*, tr.name as route_name')
            ->join('transport_routes tr', 'tr.id = th.route_id', 'left')
            ->where('th.org_id', $orgId)
            ->get()->getResultArray();
            
        $data['routes'] = $this->routeModel->where('org_id', $orgId)->findAll();
        $data['activeModule'] = 'transport';
        $data['active_menu'] = 'transport';
        $data['active_submenu'] = 'halts';
        
        return view('org/transport/halts', $data);
    }

    public function delete_halt($id)
    {
        $this->haltModel->where('org_id', $this->getOrgId())->delete($id);
        session()->setFlashdata('success', 'Halt deleted.');
        return redirect()->to('/org/transport/halts');
    }

    // -------------------------------------------------------------------------
    // SUBSCRIPTIONS
    // -------------------------------------------------------------------------
    public function subscriptions()
    {
        $orgId = $this->getOrgId();
        if (!$orgId) return redirect()->to('/org/login');
        $db = \Config\Database::connect();

        if ($this->request->getMethod() === 'post') {
            $data = [
                'org_id' => $orgId,
                'student_id' => $this->request->getPost('student_id'),
                'route_id' => $this->request->getPost('route_id'),
                'halt_id' => $this->request->getPost('halt_id'),
                'start_date' => $this->request->getPost('start_date'),
                'status' => $this->request->getPost('status'),
            ];

            $id = $this->request->getPost('id');
            if ($id) {
                $this->subscriptionModel->update($id, $data);
                session()->setFlashdata('success', 'Subscription updated successfully.');
            } else {
                $this->subscriptionModel->insert($data);
                session()->setFlashdata('success', 'Subscription added successfully.');
            }
            return redirect()->to('/org/transport/subscriptions');
        }

        $data['subscriptions'] = $db->table('transport_subscriptions ts')
            ->select('ts.*, ou.full_name, tr.name as route_name, th.name as halt_name')
            ->join('org_users ou', 'ou.id = ts.student_id', 'left')
            ->join('transport_routes tr', 'tr.id = ts.route_id', 'left')
            ->join('transport_halts th', 'th.id = ts.halt_id', 'left')
            ->where('ts.org_id', $orgId)
            ->get()->getResultArray();

        $data['students'] = $db->table('org_users')->where('org_id', $orgId)->where('role', 'student')->get()->getResultArray();
        $data['routes'] = $this->routeModel->where('org_id', $orgId)->findAll();
        $data['halts'] = $this->haltModel->where('org_id', $orgId)->findAll();
        
        $data['activeModule'] = 'transport';
        $data['active_menu'] = 'transport';
        $data['active_submenu'] = 'subscriptions';
        
        return view('org/transport/subscriptions', $data);
    }

    public function delete_subscription($id)
    {
        $this->subscriptionModel->where('org_id', $this->getOrgId())->delete($id);
        session()->setFlashdata('success', 'Subscription deleted.');
        return redirect()->to('/org/transport/subscriptions');
    }

    // -------------------------------------------------------------------------
    // LOGBOOK
    // -------------------------------------------------------------------------
    public function logbook()
    {
        $orgId = $this->getOrgId();
        if (!$orgId) return redirect()->to('/org/login');
        $db = \Config\Database::connect();

        if ($this->request->getMethod() === 'post') {
            $data = [
                'org_id' => $orgId,
                'vehicle_id' => $this->request->getPost('vehicle_id'),
                'expense_head' => $this->request->getPost('expense_head'),
                'amount' => $this->request->getPost('amount'),
                'expense_date' => $this->request->getPost('expense_date'),
                'description' => $this->request->getPost('description'),
            ];

            $id = $this->request->getPost('id');
            if ($id) {
                $this->logbookModel->update($id, $data);
                session()->setFlashdata('success', 'Log entry updated successfully.');
            } else {
                $this->logbookModel->insert($data);
                session()->setFlashdata('success', 'Log entry added successfully.');
            }
            return redirect()->to('/org/transport/logbook');
        }

        $data['logs'] = $db->table('transport_logbook tl')
            ->select('tl.*, tv.vehicle_no')
            ->join('transport_vehicles tv', 'tv.id = tl.vehicle_id', 'left')
            ->where('tl.org_id', $orgId)
            ->orderBy('tl.expense_date', 'DESC')
            ->get()->getResultArray();

        $data['vehicles'] = $this->vehicleModel->where('org_id', $orgId)->findAll();
        $data['activeModule'] = 'transport';
        $data['active_menu'] = 'transport';
        $data['active_submenu'] = 'logbook';
        
        return view('org/transport/logbook', $data);
    }

    public function delete_logbook($id)
    {
        $this->logbookModel->where('org_id', $this->getOrgId())->delete($id);
        session()->setFlashdata('success', 'Log entry deleted.');
        return redirect()->to('/org/transport/logbook');
    }
}
