<?php

namespace App\Controllers;

use App\Models\TimetableTemplateModel;
use App\Models\TimetablePeriodModel;
use App\Models\TimetableScheduleModel;
use App\Models\TimetableEntryModel;
use App\Models\SubjectModel;
use App\Models\OrgUserModel;

class LmsTimetable extends BaseController
{
    public function index()
    {
        $orgId = $this->org_id ?: (session('org_id') ?: 5);
        $cohortId = session('cohort_id') ?: (session('lms_cohort_id') ?: 1);

        $scheduleModel = new TimetableScheduleModel();
        
        $schedule = $scheduleModel->where('org_id', $orgId)
                                  ->where('cohort_id', $cohortId)
                                  ->where('status', 'published')
                                  ->first();

        if (!$schedule) {
            $schedule = $scheduleModel->where('org_id', $orgId)
                                      ->where('status', 'published')
                                      ->first();
        }
                                  
        $periods = [];
        $entries_map = [];
        
        if ($schedule) {
            $periodModel = new TimetablePeriodModel();
            $periods = $periodModel->where('template_id', $schedule['template_id'])
                                   ->orderBy('start_time', 'ASC')
                                   ->findAll();
                                   
            $entryModel = new TimetableEntryModel();
            $entries = $entryModel->where('schedule_id', $schedule['id'])->findAll();
            
            $subjectModel = new SubjectModel();
            $subjects = $subjectModel->where('org_id', $orgId)->findAll();
            $s_map = [];
            foreach($subjects as $s) $s_map[$s['id']] = $s;
            
            $userModel = new OrgUserModel();
            $faculty = $userModel->where('org_id', $orgId)->findAll();
            $f_map = [];
            foreach($faculty as $f) $f_map[$f['id']] = $f;
            
            foreach($entries as $e) {
                if ($e['subject_id']) {
                    $e['subject'] = $s_map[$e['subject_id']] ?? null;
                }
                if ($e['faculty_user_id']) {
                    $e['faculty'] = $f_map[$e['faculty_user_id']] ?? null;
                }
                $entries_map[$e['day_of_week']][$e['period_id']] = $e;
            }
        }

        return view('lms/timetable/index', [
            'schedule' => $schedule,
            'periods' => $periods,
            'entries_map' => $entries_map
        ]);
    }
}

