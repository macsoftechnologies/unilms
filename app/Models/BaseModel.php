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
        if (!in_array('setUuid', $this->beforeInsert)) {
            $this->beforeInsert[] = 'setUuid';
        }
        if (!in_array('setUpdatedBy', $this->beforeUpdate)) {
            $this->beforeUpdate[] = 'setUpdatedBy';
        }
    }

    protected function setUuid(array $data)
    {
        if (isset($data['data'])) {
            try {
                $fields = $this->db->getFieldNames($this->table);
                if (in_array('uuid', $fields)) {
                    if (!in_array('uuid', $this->allowedFields)) {
                        $this->allowedFields[] = 'uuid';
                    }
                    if (empty($data['data']['uuid'])) {
                        $data['data']['uuid'] = uuid_v7();
                    }
                }
            } catch (\Throwable $e) {}
        }
        return $data;
    }

    /**
     * Find a record strictly by its UUID v7.
     * Rejects numeric / sequential IDs to prevent enumeration attacks.
     *
     * @param string $uuid
     * @return array|object|null
     */
    public function findByUuid(string $uuid)
    {
        if (!is_uuid($uuid)) {
            return null;
        }
        return $this->where($this->table . '.uuid', $uuid)->first();
    }

    /**
     * Resolve record strictly by UUID. Rejects sequential numbers to prevent IDOR/enumeration.
     *
     * @param string $identifier
     * @return array|object|null
     */
    public function findByIdOrUuid($identifier)
    {
        if (!is_uuid($identifier)) {
            return null;
        }
        return $this->where($this->table . '.uuid', $identifier)->first();
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
