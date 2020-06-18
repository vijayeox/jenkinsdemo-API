<?php
return array(
    "rpsCyber" => [
        "text" =>
        [
            'breachname' => 'BBR Contact Name',
            'breachphone' => 'BBR Contact Phone',
            'breachemail' => 'BBR Contact Email',
            'annualsales' => 'Sales',
            'pleaseexplain' => 'Explain',
            'secinsurer' => 'Insurer',
            'columnslimit' => 'Limit',
            'columnsdeductible' => 'Deductible',
            'policyperiod' => 'Policy Period',
            'secretrodate' => 'Retro Date',
            'secpremium' => 'Premium',
            'revenuecreditcard' => '% of rev from CC',
            'additionalcontrols' => 'Additional Controls',
            'backingupandstorageother' => 'Other backup',
            'proposedinsureddetails' => 'Yes to claims info',
            'actionnotification' => 'Details on claims',
            'physicalsecurity' => 'physical controls',
            'namedInsured' => 'Full Applicant Name Additional space on page 3',
            'mailingAddress' => 'Mailing Address',
            'city' => 'City',
            'zip' => 'Zip',
            'state' => 'State',
            'website' => 'Website',
            'ofRecordsContaining' => '# of Records',
            'numberOfEmployees' => " of Employees"
        ],
        "radioYN" =>
        [
            "natureofsize" => [
                "fieldname" => "Any changes?",
                "options" => ["yes" => "Yes", "no" => "No"]
            ],
            "insurancecoverage" => [
                "fieldname" => "Current Coverage?",
                "options" => ["yes" => "Yes", "no" => "No"]
            ],
            "policiesprocedures" => [
                "fieldname" => "Computer procedures",
                "options" => ["yes" => "Yes", "no" => "No"]
            ],
            "contractedservices" => [
                "fieldname" => "Employee leaves",
                "options" => ["yes" => "Yes", "no" => "No"]
            ],
            "creditcards" => [
                "fieldname" => "CC's",
                "options" => ["yes" => "Yes", "no" => "No"]
            ],
            "datasecurity" => [
                "fieldname" => "PCI",
                "options" => ["yes" => "Yes", "no" => "No"]
            ],
            "externalcommunication" => [
                "fieldname" => "Encryption",
                "options" => ["yes" => "Yes", "no" => "No"]
            ],
            "portablecomputers" => [
                "fieldname" => "Portable Media",
                "options" => ["yes" => "Yes", "no" => "No"]
            ],
            "portablemedia" => [
                "fieldname" => "Laptop",
                "options" => ["yes" => "Yes", "no" => "No"]
            ],
            "backupmaterials" => [
                "fieldname" => "Backups encrypted",
                "options" => ["yes" => "Yes", "no" => "No"]
            ],
            "securedstorage" => [
                "fieldname" => "Portable Media Stored",
                "options" => ["yes" => "Yes", "no" => "No"]
            ],
            "offsitetransportation" => [
                "fieldname" => "logs mainted",
                "options" => ["yes" => "Yes", "no" => "No"]
            ],
            "thirdpartysprivacy" => [
                "fieldname" => "allegations",
                "options" => ["yes" => "Yes", "no" => "No"]
            ],
            "infringingcontent" => [
                "fieldname" => "content",
                "options" => ["yes" => "Yes", "no" => "No"]
            ],
            "applicantscreened" => [
                "fieldname" => "Infringement",
                "options" => ["yes" => "Yes", "no" => "No"]
            ],
            "applicantacquired" => [
                "fieldname" => "Trademarks",
                "options" => ["yes" => "Yes", "no" => "No"]
            ],
            "trademarkservice" => [
                "fieldname" => "screened",
                "options" => ["yes" => "Yes", "no" => "No"]
            ],
            "otherproposedinsured" => [
                "fieldname" => "Prior Claims 1",
                "options" => ["yes" => "Yes", "no" => "No"]
            ],
            "claimsorcomplaints" => [
                "fieldname" => "Prior Claims 2",
                "options" => ["yes" => "Yes", "no" => "No"]
            ],
            "privacylaworregulation" => [
                "fieldname" => "Prior Claims b",
                "options" => ["yes" => "Yes", "no" => "No"]
            ],
            "databreachincident" => [
                "fieldname" => "Prior Claims 2c",
                "options" => ["yes" => "Yes", "no" => "No"]
            ],
            "attemptedextortion" => [
                "fieldname" => "Prior Claims 2d",
                "options" => ["yes" => "Yes", "no" => "No"]
            ],
            "firewallCheck" => [
                "fieldname" => "Firewalls?",
                "options" => ["yes" => "Yes", "no" => "No"]
            ]
        ],
        "date" =>
        [
            "secretrodate" =>  "Retro Date",
        ],
        "checkbox" => [
            'tapeOrOtherMedia' =>  [
                "parentKey" => "backingupandstorage",
                "fieldname" => 'Tape or other media',
                "options" => ["true" => "On", "false" => "Off"]
            ],
            'onlineBackupService' =>  [
                "parentKey" => "backingupandstorage",
                "fieldname" => 'Online backup service',
                "options" => ["true" => "On", "false" => "Off"]
            ],
            'other' =>  [
                "parentKey" => "backingupandstorage",
                "fieldname" => 'Other',
                "options" => ["true" => "On", "false" => "Off"]
            ],
            'noWebsite' =>  [
                "parentKey" => "websitecontent",
                "fieldname" => 'No W ebsite',
                "options" => ["true" => "On", "false" => "Off"]
            ],
            'streamingVideoOrMusicContent' =>  [
                "parentKey" => "websitecontent",
                "fieldname" => 'Streaming video or music content',
                "options" => ["true" => "On", "false" => "Off"]
            ],
            'blogMessageBoardsCustomerReviews' =>  [
                "parentKey" => "websitecontent",
                "fieldname" => 'BlogMessage BoardsCustomer Reviews',
                "options" => ["true" => "On", "false" => "Off"]
            ],
            'informationCreatedByTheApplicant' =>  [
                "parentKey" => "websitecontent",
                "fieldname" => 'Information created by the Applicant',
                "options" => ["true" => "On", "false" => "Off"]
            ],
            'contentUnderLicenseFromAThirdParty' =>  [
                "parentKey" => "websitecontent",
                "fieldname" => 'Content under license from a third party',
                "options" => ["true" => "On", "false" => "Off"]
            ],
        ]
    ],

    "epli" => [
        "text" =>
        [
            'namedInsured' => 'Name of Company',
            'mailingAddress' => 'Mailing Address 1',
            'website' => 'Internet Web site address',
            'numYearsOfOwnership' => 'Years in Operation',
            'salaryexpenses' => 'Total salary expense for the most recent yearend',
            'annualturnoverrate' => 'Most recent annual turnover rate',
            'pastavgturnoverrate' => 'Historical average annual turnover rate',
            'stateone' => 'a State',
            'employeeone' => 'Number of employees',
            'statetwo' => 'b State',
            'employeetwo' => 'Number of employees_2',
            'statethree' => 'c State',
            'employeethree' => 'Number of employees_3',
            'cityStateZip' => 'City, State, Zip',
            'employeefour' => 'Number of employees_4',
            'officerone' => 'Number of Officers',
            'employeefive' => 'Number of employees_5',
            'officertwo' => 'Number of Officers_2',
            'fullTimeEmployee' => 'a  full time employees',
            'partTimeEmployee' => 'b  part time employees',
            'contractDriversNonEmployees' => 'c leasedcontract employees',
            'emppremium' => 'Premium',
            'empinsurername' => 'Insurer',
            'LimitCount' => 'Limit',
            'retentionrate' => 'Retention',
            'policyeffectiveperiod' => 'Policy Period',
        ],
        "radioYN" =>
        [
            'independentcontractors' => [
                "fieldname" => 'Group2',
                "options" => ["yes" => "Choice1", "no" => "Choice2"]
            ],
            'layoffdata' => [
                "fieldname" => 'Group3',
                "options" => ["yes" => "Choice1", "no" => "Choice2"]
            ],
            'outplacement' => [
                "fieldname" => 'Group4',
                "options" => ["yes" => "Choice1", "no" => "Choice2"]
            ],
            'plannedtransactions' => [
                "fieldname" => 'Group5',
                "options" => ["yes" => "Choice1", "no" => "Choice2"]
            ],
            'employementapplication' => [
                "fieldname" => 'Group6',
                "options" => ["yes" => "Choice1", "no" => "Choice2"]
            ],
            'screeningemployee' => [
                "fieldname" => 'Group7',
                "options" => ["yes" => "Choice1", "no" => "Choice2"]
            ],
            'humanresources' => [
                "fieldname" => 'Group8',
                "options" => ["yes" => "Choice1", "no" => "Choice2"]
            ],
            'humanresourcesmanual' => [
                "fieldname" => 'Group9',
                "options" => ["yes" => "Choice1", "no" => "Choice2"]
            ],
            'disabilitiesact' => [
                "fieldname" => 'Group10',
                "options" => ["yes" => "Choice1", "no" => "Choice2"]
            ],
            'civilrightsact' => [
                "fieldname" => 'Group11',
                "options" => ["yes" => "Choice1", "no" => "Choice2"]
            ],
            'familymedical' => [
                "fieldname" => 'Group12',
                "options" => ["yes" => "Choice1", "no" => "Choice2"]
            ],
            'prohibiteddiscriminatory' => [
                "fieldname" => 'Group13',
                "options" => ["yes" => "Choice1", "no" => "Choice2"]
            ],
            'empperfomev' => [
                "fieldname" => 'Group14',
                "options" => ["yes" => "Choice1", "no" => "Choice2"]
            ],
            'performanceevaluations' => [
                "fieldname" => 'Group15',
                "options" => ["yes" => "Choice1", "no" => "Choice2"]
            ],
            'sexualharassment' => [
                "fieldname" => 'Group16',
                "options" => ["yes" => "Choice1", "no" => "Choice2"]
            ],
            'reportingresolution' => [
                "fieldname" => 'Group17',
                "options" => ["yes" => "Choice1", "no" => "Choice2"]
            ],
            'hrmanual' => [
                "fieldname" => 'Group18',
                "options" => ["yes" => "Choice1", "no" => "Choice2"]
            ],
            'receivetraining' => [
                "fieldname" => 'Group19',
                "options" => ["yes" => "Choice1", "no" => "Choice2"]
            ],
            'acknowledgereciept' => [
                "fieldname" => 'Group20',
                "options" => ["yes" => "Choice1", "no" => "Choice2"]
            ],
            'compsubsidiaries' => [
                "fieldname" => 'Group21',
                "options" => ["yes" => "Choice1", "no" => "Choice2"]
            ],
            'capacitydirector' => [
                "fieldname" => 'Group22',
                "options" => ["yes" => "Choice1", "no" => "Choice2"]
            ],
            'civilrights' => [
                "fieldname" => 'Group23',
                "options" => ["yes" => "Choice1", "no" => "Choice2"]
            ],
            'emppracticesclaim' => [
                "fieldname" => 'Group24',
                "options" => ["yes" => "Choice1", "no" => "Choice2"]
            ]
        ]
    ]
);
