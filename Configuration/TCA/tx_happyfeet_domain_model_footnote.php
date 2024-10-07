<?php
defined('TYPO3') or die();

return [
    'ctrl' => [
        'title' => 'LLL:EXT:happy_feet/Resources/Private/Language/locallang_db.xlf:tx_happyfeet_domain_model_footnote',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'searchFields' => 'index_number,title,header,description',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden'
        ],
        'iconfile' => 'EXT:happy_feet/Resources/Public/Icons/TCA/Footnote.gif'
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => 1,
            'label'   => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config'  => [
                'type'                => 'select',
                'renderType'          => 'selectSingle',
                'foreign_table'       => 'sys_language',
                'foreign_table_where' => 'ORDER BY sys_language.title',
                'items'               => [
                    [
                        'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.allLanguages',
                        'value' => -1,
                    ],
                    [
                        'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.default_value',
                        'value' => 0,
                    ],
                ]
            ]
        ],
        'l18n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'exclude'     => 1,
            'label'       => 'LLL:EXT:lang/Resources/Private/Language/locallang_general.xml:LGL.l18n_parent',
            'config'      => [
                'type'        => 'select',
                'renderType'  => 'selectSingle',
                'items'       => [
                    [
                        'label' => '',
                        'value' => 0,
                    ],
                ],
                'foreign_table'       => 'tx_happyfeet_domain_model_footnote',
                'foreign_table_where' => 'AND tx_happyfeet_domain_model_footnote.pid=###CURRENT_PID### AND tx_happyfeet_domain_model_footnote.sys_language_uid IN (-1,0)',
            ]
        ],
        'l18n_diffsource'  => [
            'config' => [
                'type' => 'passthrough',
                'default' => '0'
            ]
        ],
        'hidden' => [
            'exclude' => 1,
            'label'   => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.hidden',
            'config'  => [
                'type'    => 'check',
                'default' => '0'
            ]
        ],
        'starttime' => [
            'exclude' => 1,
            'label'   => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
            'config'  => [
                'type'       => 'datetime',
                'format'     => 'date',
                'size'       => '20',
                'default'    => '0',
            ]
        ],
        'endtime' => [
            'exclude' => 1,
            'label'   => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
            'config'  => [
                'type'       => 'datetime',
                'format'     => 'date',
                'size'       => '20',
                'default'    => '0',
            ]
        ],
        'index_number' => [
            'exclude' => 1,
            'label'   => 'LLL:EXT:happy_feet/Resources/Private/Language/locallang_db.xlf:tx_happyfeet_domain_model_footnote.index_number',
            'config'  => [
                'type' => 'none',
                'size' => 30,
            ]
        ],
        'title' => [
            'exclude' => 0,
            'label'   => 'LLL:EXT:happy_feet/Resources/Private/Language/locallang_db.xlf:tx_happyfeet_domain_model_footnote.title',
            'required' => true,
            'config'  => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,unique'
            ]
        ],
        'header' => [
            'exclude' => 0,
            'label'   => 'LLL:EXT:happy_feet/Resources/Private/Language/locallang_db.xlf:tx_happyfeet_domain_model_footnote.header',
            'config'  => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ]
        ],
        'description' => [
            'exclude' => 0,
            'label'   => 'LLL:EXT:happy_feet/Resources/Private/Language/locallang_db.xlf:tx_happyfeet_domain_model_footnote.description',
            'required' => true,
            'config'  => [
                'type' => 'text',
                'cols' => '30',
                'rows' => '5',
                'enableRichtext' => true
            ],
        ]
    ],
    'types' => [
        '0' => ['showitem' => 'title, index_number, header, description, --div--;LLL:EXT:happy_feet/Resources/Private/Language/locallang_db.xlf:tx_happyfeet_domain_model_footnote.tab.access, hidden,--palette--;;1']
    ],
    'palettes' => [
        '1' => ['showitem' => 'starttime, endtime']
    ]
];
