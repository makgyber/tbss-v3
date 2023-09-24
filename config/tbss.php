<?php

use function Ramsey\Uuid\v1;

return [
    'tracker_polling_interval' => 30000,

    'client_type' => [
        'residential' => 'Residential',
        'commercial' => 'Commercial',
    ],

    'payment_status' => [
        'pending' => 'pending',
        'paid' => 'paid',
        'with balance' => 'with balance',
        'free' => 'free',
        'void' => 'void',
    ],

    'visit_status' => [
        'done' => 'done',
        'postponed' => 'postponed',
        'cancelled' => 'cancelled',
        'for schedule' => 'for schedule',
    ],

    'visit_frequency' => [
        'adhoc' => 'Ad-hoc',
        'weekly' => 'Weekly',
        'biweekly' => 'Biweekly',
        'monthly' => 'Monthly',
        'bimonthly' => 'Bimonthly',
        'quarterly' => 'Quarterly',
        'regular' => 'Regular',
    ],

    'lead_status' => [
        'inquiry' => 'inquiry',
        'pending entom' => 'pending entom',
        'entom done' => 'entom done',
        'declined' => 'declined',
        'pending proposal' => 'pending proposal',
        'proposal submitted' => 'proposal submitted',
        'closed' => 'closed',
    ],

    'lead_source' => [
        'website' => 'website',
        'fb' => 'fb',
        'referral' => 'referral',
        'existing client' => 'existing client',
        'returning client' => 'returning client',
    ],

    'entom_status' => [
        'pending' => 'pending',
        'completed' => 'completed',
        'cancelled' => 'cancelled',
    ],

    'contract_status' => [
        'pending' => 'pending',
        'submitted' => 'submitted',
        'closed' => 'closed',
        'declined' => 'declined',
        'expired' => 'expired',
        'cancelled' => 'cancelled',
        'extended' => 'extended',
    ],

    'job_order_status' => [
        'unscheduled' => 'unscheduled',
        'scheduled' => 'scheduled',
        'confirmed' => 'confirmed',
        'started' => 'started',
        'completed' => 'completed',
        'cancelled' => 'cancelled',
        'postponed' => 'postponed',
        'serviced' => 'serviced',
    ],

    'job_order_type' => [
        'entom' => 'entom',
        'installation' => 'installation',
        'inspection' => 'inspection',
        '1st overall' => '1st overall',
        'overall' => 'overall',
        'with termite' => 'with termite',
        'soil poisoning' => 'soil poisoning',
        'wood treatment' => 'wood treatment',
        'qtcp' => 'qtcp',
        're-insp' => 're-insp',
        'evaluation' => 'evaluation',
        'mtcp' => 'mtcp',
        'full tcp' => 'full tcp',
        'renewal overall' => 'renewal overall',
        'renewal qtcp' => 'renewal qtcp',
        'gpc' => 'gpc',
        'gpc/mistblower' => 'gpc/mistblower',
        'gpc/fogging' => 'gpc/fogging',
        'gpc follow-up' => 'gpc follow-up',
        'rat baiting' => 'rat baiting',
        'rat baiting follow-up' => 'rat baiting follow-up',
        'cockroach treatment' => 'cockroach treatment',
        'cockroach treatment follow-up' => 'cockroach treatment follow-up',
        'mosquito treatment' => 'mosquito treatment',
        'mosquito treatment follow-up' => 'mosquito treatment follow-up',
        'bee treatment' => 'bee treatment',
        'disinfaction/sanitation' => 'disinfaction/sanitation',
        'bee treatment' => 'bedbugs treatment',
        'ants treatment' => 'ants treatment',
        'mosquito treatment' => 'mosquito treatment',
        'deliver billing' => 'deliver billing',
        'pick-up check' => 'pick-up check',
        'deliver letter/report' => 'deliver letter/report',
        'meeting' => 'meeting',
    ],

    'priority' => [
        'low' => 'low',
        'medium' => 'medium',
        'high' => 'high',
        'critical' => 'critical',
    ],

    'infestation_degree' => [
        'none' => 'none',
        'light' => 'light',
        'medium' => 'medium',
        'heavy' => 'heavy',
    ],

    'operations' => [
        'technician' => [
            'role' => 'technician',
        ]
    ],

    'hr' => [
        'user' => [
            'leave_types' => [
                'day off' => 'day off',
                'absent' => 'absent',
                'vacation leave' => 'vacation leave',
                'sick leave' => 'sick leave',
                'maternity leave' => 'maternity leave',
                'holiday' => 'holiday',
            ]
        ]
    ],

    'client_classification' => [
        'A' => 'A',
        'B' => 'B',
        'C' => 'C',
        'D' => 'D',
        'E' => 'E',
    ],

    'client_tagging' => [
        'watchlist' => 'watchlist',
        'vip' => 'VIP',
        'big_contract' => 'big contract',
    ],

    'pest_type' => [
        'Copto' => 'Copto',
        'Micro' => 'Micro',
        'Macro' => 'Macro',
        'Luzonicus' => 'Luzonicus',
        'Drywood' => 'Drywood',
        'German cockroach' => 'German cockroach',
        'American cockroach' => 'American cockroach',
        'Norway rat' => 'Norway rat',
        'Roof rat' => 'Roof rat',
        'House mouse' => 'House mouse',
        'Fruit flies' => 'Fruit flies',
        'House fly' => 'House fly',
        'Horse fly' => 'Horse fly',
        'Bed bugs' => 'Bed bugs',
        'Bee' => 'Bee',
    ],

    'service_type' => [
        'Installation' => 'Installation',
        'Inspection' => 'Inspection',
        'Overall' => 'Overall',
        'Re-inspection' => 'Re-inspection',
        'QTCP' => 'QTCP',
        'Soil poisoning' => 'Soil poisoning',
        'Evaluation' => 'Evaluation',
        'Cockroach treatment' => 'Cockroach treatment',
        'Mosquito treatment' => 'Mosquito treatment',
        'Rat baiting' => 'Rat baiting',
        'GPC/mistblower' => 'GPC/mistblower',
        'GPC' => 'GPC',
        'Follow-up' => 'Follow-up',
        'Bee treatment' => 'Bee treatment',
        'Pick-up check' => 'Pick-up check',
        'Deliver billing' => 'Deliver billing',
        'Deliver envelope' => 'Deliver envelope',

    ],

    'contract_type' => [
        'General Pest Control' => 'General Pest Control',
        'Xterm' => 'Xterm',
        'TCP' => 'TCP',
        'Pre-con' => 'Pre-con',
        'One-time GPC' => 'One-time GPC',
        'Bed bugs' => 'Bed bugs',
        'Disinfection' => 'Disinfection',
        'Cockroach Treatment' => 'Cockroach Treatment',
        'Mosquito Treatment' => 'Mosquito Treatment',
        'Bee Treatment' => 'Bee Treatment',
        'MTCP' => 'MTCP',
        'Sentricon' => 'Sentricon',
        'Renewal Overall' => 'Renewal Overall',
        'QTCP Renewal' => 'QTCP Renewal',
    ],

    'engagement_type' => [
        'single' => 'single',
        'long-term' => 'long-term',
    ],

    'lead_service_type' => [
        'termite' => 'termite',
        'gpc' => 'gpc',
        'disinfection' => 'disinfection',
        'Cockroach Treatment' => 'Cockroach Treatment',
        'Mosquito Treatment' => 'Mosquito Treatment',
        'Bee Treatment' => 'Bee Treatment',
        'MTCP' => 'MTCP',
        'Sentricon' => 'Sentricon',
        'Renewal Overall' => 'Renewal Overall',
        'QTCP Renewal' => 'QTCP Renewal',
        'Xterm' => 'Xterm',
        'TCP' => 'TCP',
        'Pre-con' => 'Pre-con',
        'One-time GPC' => 'One-time GPC',
        'Bed bugs' => 'Bed bugs',
    ]
];
