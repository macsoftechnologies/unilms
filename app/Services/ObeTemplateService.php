<?php

namespace App\Services;

use App\Models\PoDefinitionModel;
use App\Models\ProgramModel;

class ObeTemplateService
{
    /**
     * Standard NAAC, NBA & UGC Accreditation Outcome Templates
     */
    public static function getTemplates(): array
    {
        return [
            'btech' => [
                'name' => 'B.Tech / B.E. (NBA 12 Graduate Attributes)',
                'degree_level' => 'UG Engineering',
                'outcomes' => [
                    [
                        'code' => 'PO1',
                        'type' => 'PO',
                        'description' => 'Engineering Knowledge: Apply the knowledge of mathematics, science, engineering fundamentals, and an engineering specialization to the solution of complex engineering problems.'
                    ],
                    [
                        'code' => 'PO2',
                        'type' => 'PO',
                        'description' => 'Problem Analysis: Identify, formulate, review research literature, and analyze complex engineering problems reaching substantiated conclusions using first principles of mathematics, natural sciences, and engineering sciences.'
                    ],
                    [
                        'code' => 'PO3',
                        'type' => 'PO',
                        'description' => 'Design/Development of Solutions: Design solutions for complex engineering problems and design system components or processes that meet the specified needs with appropriate consideration for public health and safety, and cultural, societal, and environmental considerations.'
                    ],
                    [
                        'code' => 'PO4',
                        'type' => 'PO',
                        'description' => 'Conduct Investigations of Complex Problems: Use research-based knowledge and research methods including design of experiments, analysis and interpretation of data, and synthesis of the information to provide valid conclusions.'
                    ],
                    [
                        'code' => 'PO5',
                        'type' => 'PO',
                        'description' => 'Modern Tool Usage: Create, select, and apply appropriate techniques, resources, and modern engineering and IT tools including prediction and modeling to complex engineering activities with an understanding of the limitations.'
                    ],
                    [
                        'code' => 'PO6',
                        'type' => 'PO',
                        'description' => 'The Engineer and Society: Apply reasoning informed by the contextual knowledge to assess societal, health, safety, legal and cultural issues and the consequent responsibilities relevant to the professional engineering practice.'
                    ],
                    [
                        'code' => 'PO7',
                        'type' => 'PO',
                        'description' => 'Environment and Sustainability: Understand the impact of the professional engineering solutions in societal and environmental contexts, and demonstrate the knowledge of, and need for sustainable development.'
                    ],
                    [
                        'code' => 'PO8',
                        'type' => 'PO',
                        'description' => 'Ethics: Apply ethical principles and commit to professional ethics and responsibilities and norms of the engineering practice.'
                    ],
                    [
                        'code' => 'PO9',
                        'type' => 'PO',
                        'description' => 'Individual and Team Work: Function effectively as an individual, and as a member or leader in diverse teams, and in multidisciplinary settings.'
                    ],
                    [
                        'code' => 'PO10',
                        'type' => 'PO',
                        'description' => 'Communication: Communicate effectively on complex engineering activities with the engineering community and with society at large, such as, being able to comprehend and write effective reports and design documentation, make effective presentations, and give and receive clear instructions.'
                    ],
                    [
                        'code' => 'PO11',
                        'type' => 'PO',
                        'description' => 'Project Management and Finance: Demonstrate knowledge and understanding of the engineering and management principles and apply these to one’s own work, as a member and leader in a team, to manage projects and in multidisciplinary environments.'
                    ],
                    [
                        'code' => 'PO12',
                        'type' => 'PO',
                        'description' => 'Life-long Learning: Recognize the need for, and have the preparation and ability to engage in independent and life-long learning in the broadest context of technological change.'
                    ],
                    [
                        'code' => 'PSO1',
                        'type' => 'PSO',
                        'description' => 'Professional Core Competence: Ability to specify, design, implement and test specialized software, hardware, and multidisciplinary systems aligned with current industry standards.'
                    ],
                    [
                        'code' => 'PSO2',
                        'type' => 'PSO',
                        'description' => 'Innovation & Employability: Demonstrate research, problem solving, innovation and leadership skills to successfully pursue careers in industry, higher studies, or entrepreneurial ventures.'
                    ]
                ]
            ],
            'mtech' => [
                'name' => 'M.Tech / M.E. (AICTE PG Engineering)',
                'degree_level' => 'PG Engineering',
                'outcomes' => [
                    [
                        'code' => 'PO1',
                        'type' => 'PO',
                        'description' => 'Advanced Knowledge & Research: An ability to independently carry out research / investigation and development work to solve practical engineering and scientific problems.'
                    ],
                    [
                        'code' => 'PO2',
                        'type' => 'PO',
                        'description' => 'Technical Writing & Dissemination: An ability to write and present a substantial technical report/document and publish in peer-reviewed venues.'
                    ],
                    [
                        'code' => 'PO3',
                        'type' => 'PO',
                        'description' => 'Domain Mastery: Demonstrate a high degree of mastery over the specialized discipline at an advanced postgraduate level.'
                    ],
                    [
                        'code' => 'PO4',
                        'type' => 'PO',
                        'description' => 'Modern Research Methodologies: Apply cutting-edge computational, analytical, and experimental methods to design sophisticated technological solutions.'
                    ],
                    [
                        'code' => 'PO5',
                        'type' => 'PO',
                        'description' => 'Professional Ethics & Societal Responsibility: Practice professional ethics, research integrity, and uphold environmental and societal safety standards.'
                    ],
                    [
                        'code' => 'PO6',
                        'type' => 'PO',
                        'description' => 'Project & Innovation Leadership: Lead complex technology projects with sound economic, financial, and multidisciplinary management acumen.'
                    ]
                ]
            ],
            'mba' => [
                'name' => 'MBA (AICTE / NBA Management)',
                'degree_level' => 'PG Management',
                'outcomes' => [
                    [
                        'code' => 'PO1',
                        'type' => 'PO',
                        'description' => 'Management Knowledge: Apply knowledge of management theories and practices to solve business problems in dynamic domestic and global markets.'
                    ],
                    [
                        'code' => 'PO2',
                        'type' => 'PO',
                        'description' => 'Analytical & Critical Thinking: Foster analytical and critical thinking abilities for data-driven strategic decision making and risk assessment.'
                    ],
                    [
                        'code' => 'PO3',
                        'type' => 'PO',
                        'description' => 'Leadership & Team Dynamics: Develop value-based leadership ability and work effectively in collaborative, cross-functional, and diverse team environments.'
                    ],
                    [
                        'code' => 'PO4',
                        'type' => 'PO',
                        'description' => 'Business Environment & Ethics: Understand, analyze and communicate global, economic, legal, social, and ethical aspects of business governance.'
                    ],
                    [
                        'code' => 'PO5',
                        'type' => 'PO',
                        'description' => 'Entrepreneurship & Innovation: Identify viable business opportunities, design innovative business models, and manage sustainable organizational growth.'
                    ]
                ]
            ],
            'mca' => [
                'name' => 'MCA / BCA (Computer Applications)',
                'degree_level' => 'Computer Applications',
                'outcomes' => [
                    [
                        'code' => 'PO1',
                        'type' => 'PO',
                        'description' => 'Computational Knowledge: Apply knowledge of computing fundamentals, mathematics, and algorithmic principles to complex software applications.'
                    ],
                    [
                        'code' => 'PO2',
                        'type' => 'PO',
                        'description' => 'Problem Analysis: Identify, formulate, and analyze complex software problems to reach validated system architectures.'
                    ],
                    [
                        'code' => 'PO3',
                        'type' => 'PO',
                        'description' => 'Design & Development of Solutions: Design, evaluate, and implement scalable computer-based systems meeting industry standards.'
                    ],
                    [
                        'code' => 'PO4',
                        'type' => 'PO',
                        'description' => 'Modern Tool Usage: Select and apply cutting-edge software engineering tools, cloud frameworks, and development environments.'
                    ],
                    [
                        'code' => 'PO5',
                        'type' => 'PO',
                        'description' => 'Professional Ethics & Cyber Law: Adhere to professional code of conduct, ethical practices, and cyber regulations in computing.'
                    ],
                    [
                        'code' => 'PO6',
                        'type' => 'PO',
                        'description' => 'Life-long Learning: Engage in continuous learning to adapt to evolving technologies, methodologies, and market demands.'
                    ],
                    [
                        'code' => 'PO7',
                        'type' => 'PO',
                        'description' => 'Project Management & Agile Delivery: Manage software development lifecycles, team sprints, budgets, and deliver client-ready solutions.'
                    ],
                    [
                        'code' => 'PO8',
                        'type' => 'PO',
                        'description' => 'Communication Skills: Communicate technical specifications, documentation, and user guidelines clearly to stakeholders.'
                    ],
                    [
                        'code' => 'PO9',
                        'type' => 'PO',
                        'description' => 'Societal & Environmental Impact: Evaluate societal, health, safety, and environmental impact of IT solutions for sustainable progress.'
                    ],
                    [
                        'code' => 'PO10',
                        'type' => 'PO',
                        'description' => 'Teamwork & Collaboration: Function productively as a member or leader in multidisciplinary and agile technical teams.'
                    ]
                ]
            ],
            'ugc_general' => [
                'name' => 'B.Sc / B.A. / B.Com (UGC LOCF Framework)',
                'degree_level' => 'UG Arts, Science & Commerce',
                'outcomes' => [
                    [
                        'code' => 'PO1',
                        'type' => 'PO',
                        'description' => 'Critical Thinking: Take informed actions after identifying assumptions and analyzing diverse perspectives in scientific and social domains.'
                    ],
                    [
                        'code' => 'PO2',
                        'type' => 'PO',
                        'description' => 'Effective Communication: Speak, read, write and listen clearly in person and through modern digital media to diverse audiences.'
                    ],
                    [
                        'code' => 'PO3',
                        'type' => 'PO',
                        'description' => 'Social Interaction: Elicit views of others, mediate disagreements, and reach collaborative conclusions in societal and community contexts.'
                    ],
                    [
                        'code' => 'PO4',
                        'type' => 'PO',
                        'description' => 'Effective Citizenship: Demonstrate empathetic social concern and contribute constructively to equity-centred national development.'
                    ],
                    [
                        'code' => 'PO5',
                        'type' => 'PO',
                        'description' => 'Ethics: Recognize different value systems and apply moral and ethical principles in personal and professional conduct.'
                    ],
                    [
                        'code' => 'PO6',
                        'type' => 'PO',
                        'description' => 'Environment & Sustainability: Understand environmental issues and actively participate in sustainable conservation initiatives.'
                    ],
                    [
                        'code' => 'PO7',
                        'type' => 'PO',
                        'description' => 'Self-directed & Life-long Learning: Acquire the ability to engage in independent and lifelong learning across changing socio-technological horizons.'
                    ]
                ]
            ],
            'bpharm' => [
                'name' => 'B.Pharm (Pharmacy Council of India / NBA)',
                'degree_level' => 'UG Pharmacy',
                'outcomes' => [
                    [
                        'code' => 'PO1',
                        'type' => 'PO',
                        'description' => 'Pharmacy Knowledge: Apply comprehensive knowledge of pharmaceutical sciences to formulation, synthesis, and patient care.'
                    ],
                    [
                        'code' => 'PO2',
                        'type' => 'PO',
                        'description' => 'Planning Abilities: Demonstrate effective time management, resource planning, and delegation skills in pharmaceutical operations.'
                    ],
                    [
                        'code' => 'PO3',
                        'type' => 'PO',
                        'description' => 'Problem Analysis: Utilize principles of analytical thinking and scientific inquiry to resolve drug delivery and healthcare challenges.'
                    ],
                    [
                        'code' => 'PO4',
                        'type' => 'PO',
                        'description' => 'Modern Tool Usage: Learn and apply modern pharmacy instruments, analytical techniques, and computational software.'
                    ],
                    [
                        'code' => 'PO5',
                        'type' => 'PO',
                        'description' => 'Leadership Skills: Exhibit leadership qualities in healthcare teams and community pharmacy networks.'
                    ],
                    [
                        'code' => 'PO6',
                        'type' => 'PO',
                        'description' => 'Professional Identity: Understand, analyze, and communicate the value of pharmacy in healthcare delivery systems.'
                    ],
                    [
                        'code' => 'PO7',
                        'type' => 'PO',
                        'description' => 'Pharmaceutical Ethics: Honor personal values and apply ethical principles in drug manufacturing, trials, and dispensing.'
                    ],
                    [
                        'code' => 'PO8',
                        'type' => 'PO',
                        'description' => 'Communication: Communicate effectively with patients, healthcare professionals, and regulatory bodies.'
                    ],
                    [
                        'code' => 'PO9',
                        'type' => 'PO',
                        'description' => 'The Pharmacist and Society: Apply contextual knowledge to assess health, safety, and legal responsibilities in patient care.'
                    ],
                    [
                        'code' => 'PO10',
                        'type' => 'PO',
                        'description' => 'Environment and Sustainability: Understand the environmental impact of pharmaceutical waste and promote sustainable practices.'
                    ],
                    [
                        'code' => 'PO11',
                        'type' => 'PO',
                        'description' => 'Life-long Learning: Recognize the need for, and have the ability to engage in independent learning amidst healthcare advancements.'
                    ]
                ]
            ]
        ];
    }

    /**
     * Auto-detect matching template key based on Program name/code
     */
    public static function detectTemplateKey(string $programName, string $programCode = ''): string
    {
        $haystack = strtolower($programName . ' ' . $programCode);
        
        if (strpos($haystack, 'b.tech') !== false || strpos($haystack, 'btech') !== false || strpos($haystack, 'b.e') !== false || strpos($haystack, 'engineering') !== false) {
            return 'btech';
        }
        if (strpos($haystack, 'm.tech') !== false || strpos($haystack, 'mtech') !== false || strpos($haystack, 'm.e') !== false) {
            return 'mtech';
        }
        if (strpos($haystack, 'mba') !== false || strpos($haystack, 'management') !== false || strpos($haystack, 'bba') !== false) {
            return 'mba';
        }
        if (strpos($haystack, 'mca') !== false || strpos($haystack, 'bca') !== false || strpos($haystack, 'computer application') !== false) {
            return 'mca';
        }
        if (strpos($haystack, 'pharm') !== false) {
            return 'bpharm';
        }
        
        return 'ugc_general';
    }

    /**
     * Apply template outcomes to a specific program in an organization
     */
    public function applyTemplateToProgram(int $orgId, int $programId, string $templateKey): int
    {
        $templates = self::getTemplates();
        if (!isset($templates[$templateKey])) {
            $templateKey = 'btech';
        }

        $outcomes = $templates[$templateKey]['outcomes'];
        $poModel = new PoDefinitionModel();
        
        $insertedCount = 0;
        foreach ($outcomes as $outcome) {
            // Check if this code already exists for this program
            $existing = $poModel->where('org_id', $orgId)
                                ->where('program_id', $programId)
                                ->where('code', $outcome['code'])
                                ->first();

            if ($existing) {
                // Update description if existing
                $poModel->update($existing['id'], [
                    'description' => $outcome['description'],
                    'type' => $outcome['type']
                ]);
            } else {
                $poModel->insert([
                    'org_id' => $orgId,
                    'program_id' => $programId,
                    'code' => $outcome['code'],
                    'description' => $outcome['description'],
                    'type' => $outcome['type']
                ]);
            }
            $insertedCount++;
        }

        return $insertedCount;
    }
}
