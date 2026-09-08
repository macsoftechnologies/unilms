<?php

namespace App\Controllers;

class LmsCareer extends BaseController
{
    /**
     * Career & Employability Suite — Hub Landing Page
     */
    public function index()
    {
        return view('lms/career/index');
    }

    /**
     * Module 1: AI-Powered ATS Resume Builder
     */
    public function resumeBuilder()
    {
        return view('lms/career/resume');
    }

    /**
     * Module 2: AI Mock Interview Simulator
     */
    public function mockInterview()
    {
        return view('lms/career/mock_interview');
    }

    /**
     * Module 3: Verifiable Digital Web Portfolio
     */
    public function portfolio()
    {
        return view('lms/career/portfolio');
    }
}
