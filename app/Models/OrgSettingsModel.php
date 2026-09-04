<?php
namespace App\Models;

use CodeIgniter\Model;

class OrgSettingsModel extends BaseModel
{
    protected $table = 'org_settings';
    protected $primaryKey = 'id';
    protected $allowedFields = ['org_id', 'setting_key', 'setting_value'];
    protected $useTimestamps = true;

    public function getSetting($org_id, $key, $default = null)
    {
        $setting = $this->where('org_id', $org_id)
                        ->where('setting_key', $key)
                        ->first();
        return $setting ? $setting['setting_value'] : $default;
    }

    public function setSetting($org_id, $key, $value)
    {
        $existing = $this->where('org_id', $org_id)
                         ->where('setting_key', $key)
                         ->first();
        if ($existing) {
            return $this->update($existing['id'], ['setting_value' => $value]);
        } else {
            return $this->insert([
                'org_id' => $org_id,
                'setting_key' => $key,
                'setting_value' => $value
            ]);
        }
    }
}
