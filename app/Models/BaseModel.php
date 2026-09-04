<?php
namespace App\Models;

use CodeIgniter\Model;

class BaseModel extends Model
{
    public function __construct()
    {
        parent::__construct();
        
        // Only hook into insert/update lifecycle
        if (!in_array('setCreatedBy', $this->beforeInsert)) {
            $this->beforeInsert[] = 'setCreatedBy';
        }
        if (!in_array('setUpdatedBy', $this->beforeUpdate)) {
            $this->beforeUpdate[] = 'setUpdatedBy';
        }
    }

    protected function setCreatedBy(array $data)
    {
        $userId = null;
        if (session()->has('org_user_id')) {
            $userId = session('org_user_id');
        } elseif (session()->has('admin_id')) {
            $userId = session('admin_id');
        }
        
        if ($userId && isset($data['data'])) {
            try {
                $fields = $this->db->getFieldNames($this->table);
                if (in_array('created_by', $fields) && !isset($data['data']['created_by'])) {
                    $data['data']['created_by'] = $userId;
                }
                if (in_array('updated_by', $fields) && !isset($data['data']['updated_by'])) {
                    $data['data']['updated_by'] = $userId;
                }
            } catch (\Throwable $e) {}
        }
        return $data;
    }

    protected function setUpdatedBy(array $data)
    {
        $userId = null;
        if (session()->has('org_user_id')) {
            $userId = session('org_user_id');
        } elseif (session()->has('admin_id')) {
            $userId = session('admin_id');
        }
        
        if ($userId && isset($data['data'])) {
            try {
                $fields = $this->db->getFieldNames($this->table);
                if (in_array('updated_by', $fields) && !isset($data['data']['updated_by'])) {
                    $data['data']['updated_by'] = $userId;
                }
            } catch (\Throwable $e) {}
        }
        return $data;
    }
}
