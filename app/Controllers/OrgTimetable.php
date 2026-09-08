<?php

namespace App\Controllers;

use App\Models\TimetableTemplateModel;
use App\Models\TimetablePeriodModel;
use App\Models\TimetableScheduleModel;
use App\Models\TimetableEntryModel;
use App\Models\CohortModel;
use App\Models\SubjectModel;
use App\Models\OrgUserModel;

class OrgTimetable extends BaseController
{
    public function index()
    {
        if (!$this->hasPermission('manage_academics')) return redirect()->to('org/dashboard');

        $templateModel = new TimetableTemplateModel();
        $templates = $templateModel->where('org_id', $this->org_id)->findAll();

        return view('org/timetable/index', [
            'templates' => $templates
        ]);
    }

    public function createTemplate()
    {
        if (!$this->hasPermission('manage_academics')) return redirect()->to('org/dashboard');

        $templateModel = new TimetableTemplateModel();
        $name = $this->request->getPost('name');
        $desc = $this->request->getPost('description');

        $id = $templateModel->insert([
            'org_id' => $this->org_id,
            'name' => $name,
            'description' => $desc
        ]);
        $newTemplate = $templateModel->find($id);
        $targetUuid = $newTemplate['uuid'] ?? $id;

        return redirect()->to('org/timetable/periods/' . $targetUuid)->with('success', 'Template created. Now set up the periods.');
    }

    public function periods($template_id)
    {
        if (!$this->hasPermission('manage_academics')) return redirect()->to('org/dashboard');

        $templateModel = new TimetableTemplateModel();
        $template = $templateModel->where('org_id', $this->org_id)->findByIdOrUuid($template_id);
        
        if (!$template) return redirect()->to('org/timetable');
        $realId = $template['id'];

        $periodModel = new TimetablePeriodModel();
        $periods = $periodModel->where('template_id', $realId)
                               ->orderBy('start_time', 'ASC')
                               ->findAll();

        return view('org/timetable/periods', [
            'template' => $template,
            'periods' => $periods
        ]);
    }

    public function autoGeneratePeriods($template_id)
    {
        if (!$this->hasPermission('manage_academics')) return redirect()->to('org/dashboard');

        $templateModel = new TimetableTemplateModel();
        $template = $templateModel->where('org_id', $this->org_id)->findByIdOrUuid($template_id);
        if (!$template) return redirect()->to('org/timetable');
        $realId = $template['id'];
        $targetUuid = $template['uuid'] ?? $realId;

        $periodModel = new TimetablePeriodModel();

        $startTimeStr = $this->request->getPost('start_time') ?: '09:00';
        $periodDuration = (int)($this->request->getPost('period_duration') ?: 50); // minutes
        $totalPeriods = (int)($this->request->getPost('total_periods') ?: 7);
        $clearExisting = (int)($this->request->getPost('clear_existing') ?: 0);

        $hasTeaBreak = (int)($this->request->getPost('has_tea_break') ?: 0);
        $teaBreakAfter = (int)($this->request->getPost('tea_break_after') ?: 2);
        $teaBreakDuration = (int)($this->request->getPost('tea_break_duration') ?: 15); // minutes

        $hasLunchBreak = (int)($this->request->getPost('has_lunch_break') ?: 0);
        $lunchBreakAfter = (int)($this->request->getPost('lunch_break_after') ?: 4);
        $lunchBreakDuration = (int)($this->request->getPost('lunch_break_duration') ?: 45); // minutes

        if ($clearExisting) {
            $periodModel->where('org_id', $this->org_id)->where('template_id', $template_id)->delete();
        }

        $currentTime = strtotime("2026-01-01 " . $startTimeStr . ":00");
        $generatedSlots = [];

        for ($p = 1; $p <= $totalPeriods; $p++) {
            $startFormatted = date('H:i:s', $currentTime);
            $nextTime = strtotime("+{$periodDuration} minutes", $currentTime);
            $endFormatted = date('H:i:s', $nextTime);

            $generatedSlots[] = [
                'org_id'      => $this->org_id,
                'template_id' => $template_id,
                'period_name' => "Period {$p}",
                'start_time'  => $startFormatted,
                'end_time'    => $endFormatted,
                'is_break'    => 0
            ];

            $currentTime = $nextTime;

            // Check if Tea Break comes after this period
            if ($hasTeaBreak && $p == $teaBreakAfter && $p < $totalPeriods) {
                $breakStart = date('H:i:s', $currentTime);
                $breakEndTimestamp = strtotime("+{$teaBreakDuration} minutes", $currentTime);
                $breakEnd = date('H:i:s', $breakEndTimestamp);

                $generatedSlots[] = [
                    'org_id'      => $this->org_id,
                    'template_id' => $template_id,
                    'period_name' => "Morning Interval / Break",
                    'start_time'  => $breakStart,
                    'end_time'    => $breakEnd,
                    'is_break'    => 1
                ];

                $currentTime = $breakEndTimestamp;
            }

            // Check if Lunch Break comes after this period
            if ($hasLunchBreak && $p == $lunchBreakAfter && $p < $totalPeriods) {
                $lunchStart = date('H:i:s', $currentTime);
                $lunchEndTimestamp = strtotime("+{$lunchBreakDuration} minutes", $currentTime);
                $lunchEnd = date('H:i:s', $lunchEndTimestamp);

                $generatedSlots[] = [
                    'org_id'      => $this->org_id,
                    'template_id' => $realId,
                    'period_name' => "Lunch Break",
                    'start_time'  => $lunchStart,
                    'end_time'    => $lunchEnd,
                    'is_break'    => 1
                ];

                $currentTime = $lunchEndTimestamp;
            }
        }

        foreach ($generatedSlots as $slot) {
            $periodModel->insert($slot);
        }

        return redirect()->to('org/timetable/periods/' . $targetUuid)->with('success', count($generatedSlots) . ' period and break slots generated successfully!');
    }

    public function savePeriod()
    {
        if (!$this->hasPermission('manage_academics')) return redirect()->to('org/dashboard');

        $periodModel = new TimetablePeriodModel();
        $templateModel = new TimetableTemplateModel();
        $template_id = $this->request->getPost('template_id');
        $tpl = $templateModel->findByIdOrUuid($template_id);
        $realTemplateId = $tpl ? $tpl['id'] : (int)$template_id;

        $periodModel->insert([
            'org_id' => $this->org_id,
            'template_id' => $realTemplateId,
            'period_name' => $this->request->getPost('period_name'),
            'start_time' => $this->request->getPost('start_time'),
            'end_time' => $this->request->getPost('end_time'),
            'is_break' => $this->request->getPost('is_break') ? 1 : 0
        ]);

        return redirect()->back()->with('success', 'Period added successfully.');
    }

    public function deletePeriod($id)
    {
        if (!$this->hasPermission('manage_academics')) return redirect()->to('org/dashboard');

        $periodModel = new TimetablePeriodModel();
        $period = $periodModel->where('org_id', $this->org_id)->findByIdOrUuid($id);
        if ($period) {
            $periodModel->delete($period['id']);
        }
        return redirect()->back()->with('success', 'Period deleted.');
    }

    public function builder()
    {
        if (!$this->hasPermission('manage_academics')) return redirect()->to('org/dashboard');

        $cohortModel = new CohortModel();
        $cohorts = $cohortModel->where('org_id', $this->org_id)->findAll();

        $selected_cohort_id = $this->request->getGet('cohort_id');
        
        $schedule = null;
        $template = null;
        $periods = [];
        $entries_map = []; // [day][period_id] => entry array

        if ($selected_cohort_id) {
            $scheduleModel = new TimetableScheduleModel();
            $schedule = $scheduleModel->where('org_id', $this->org_id)
                                      ->where('cohort_id', $selected_cohort_id)
                                      ->first();
                                      
            if ($schedule) {
                $templateModel = new TimetableTemplateModel();
                $template = $templateModel->find($schedule['template_id']);
                
                $periodModel = new TimetablePeriodModel();
                $periods = $periodModel->where('template_id', $template['id'])
                                       ->orderBy('start_time', 'ASC')
                                       ->findAll();
                                       
                $entryModel = new TimetableEntryModel();
                $entries = $entryModel->where('schedule_id', $schedule['id'])->findAll();
                
                foreach($entries as $e) {
                    $entries_map[$e['day_of_week']][$e['period_id']] = $e;
                }
            }
        }

        $templateModel = new TimetableTemplateModel();
        $templates = $templateModel->where('org_id', $this->org_id)->findAll();
        
        $subjectModel = new SubjectModel();
        $subjects = $subjectModel->where('org_id', $this->org_id)->findAll();
        
        $userModel = new OrgUserModel();
        $faculty = $userModel->where('org_id', $this->org_id)->findAll(); // Ideally filtered by manage_academics permission or specific role

        return view('org/timetable/builder', [
            'cohorts' => $cohorts,
            'selected_cohort_id' => $selected_cohort_id,
            'schedule' => $schedule,
            'templates' => $templates,
            'template' => $template,
            'periods' => $periods,
            'entries_map' => $entries_map,
            'subjects' => $subjects,
            'faculty' => $faculty
        ]);
    }

    public function assignTemplate()
    {
        if (!$this->hasPermission('manage_academics')) return redirect()->to('org/dashboard');

        $scheduleModel = new TimetableScheduleModel();
        $cohort_id = $this->request->getPost('cohort_id');
        
        $existing = $scheduleModel->where('org_id', $this->org_id)->where('cohort_id', $cohort_id)->first();
        
        $data = [
            'org_id' => $this->org_id,
            'cohort_id' => $cohort_id,
            'template_id' => $this->request->getPost('template_id'),
            'status' => 'published'
        ];
        
        if ($existing) {
            $scheduleModel->update($existing['id'], $data);
        } else {
            $scheduleModel->insert($data);
        }
        
        return redirect()->to("org/timetable/builder?cohort_id=$cohort_id")->with('success', 'Template assigned. You can now build the grid.');
    }

    public function saveEntry()
    {
        if (!$this->hasPermission('manage_academics')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Permission denied.']);
        }

        $db = \Config\Database::connect();
        $entryModel = new TimetableEntryModel();
        
        $schedule_id = $this->request->getPost('schedule_id');
        $day = $this->request->getPost('day');
        $period_id = $this->request->getPost('period_id');
        $faculty_id = $this->request->getPost('faculty_id') ?: null;
        $room_number = trim($this->request->getPost('room_number') ?: '');
        $subject_id = $this->request->getPost('subject_id') ?: null;

        $db->transBegin();

        // 1. Check Faculty Collision across other schedules
        if (!empty($faculty_id)) {
            $conflictFaculty = $entryModel->select('timetable_entries.*, cohorts.name as cohort_name')
                ->join('timetable_schedules', 'timetable_schedules.id = timetable_entries.schedule_id')
                ->join('cohorts', 'cohorts.id = timetable_schedules.cohort_id')
                ->where('timetable_entries.org_id', $this->org_id)
                ->where('timetable_entries.day_of_week', $day)
                ->where('timetable_entries.period_id', $period_id)
                ->where('timetable_entries.faculty_user_id', $faculty_id)
                ->where('timetable_entries.schedule_id !=', $schedule_id)
                ->first();

            if ($conflictFaculty) {
                $db->transRollback();
                return $this->response->setJSON([
                    'success' => false,
                    'message' => "Schedule Collision: Teacher is already assigned to '{$conflictFaculty['cohort_name']}' during this period on " . ucfirst($day) . "."
                ]);
            }
        }

        // 2. Check Room Collision across other schedules
        if (!empty($room_number)) {
            $conflictRoom = $entryModel->select('timetable_entries.*, cohorts.name as cohort_name')
                ->join('timetable_schedules', 'timetable_schedules.id = timetable_entries.schedule_id')
                ->join('cohorts', 'cohorts.id = timetable_schedules.cohort_id')
                ->where('timetable_entries.org_id', $this->org_id)
                ->where('timetable_entries.day_of_week', $day)
                ->where('timetable_entries.period_id', $period_id)
                ->where('timetable_entries.room_number', $room_number)
                ->where('timetable_entries.schedule_id !=', $schedule_id)
                ->first();

            if ($conflictRoom) {
                $db->transRollback();
                return $this->response->setJSON([
                    'success' => false,
                    'message' => "Room Collision: Room '{$room_number}' is already occupied by '{$conflictRoom['cohort_name']}' during this period on " . ucfirst($day) . "."
                ]);
            }
        }

        $data = [
            'org_id' => $this->org_id,
            'schedule_id' => $schedule_id,
            'day_of_week' => $day,
            'period_id' => $period_id,
            'subject_id' => $subject_id,
            'faculty_user_id' => $faculty_id,
            'room_number' => $room_number ?: null
        ];

        $existing = $entryModel->where('schedule_id', $schedule_id)
                               ->where('day_of_week', $day)
                               ->where('period_id', $period_id)
                               ->first();

        if ($existing) {
            $entryModel->update($existing['id'], $data);
        } else {
            $entryModel->insert($data);
        }

        if ($db->transStatus() === false) {
            $db->transRollback();
            return $this->response->setJSON(['success' => false, 'message' => 'Database transaction failed.']);
        } else {
            $db->transCommit();
            return $this->response->setJSON(['success' => true, 'message' => 'Schedule entry saved without collisions.']);
        }
    }
}


