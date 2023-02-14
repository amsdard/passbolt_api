<?php

return [
    'ldap' => [
        'host' => env('LDAP_HOST', 'localhost'),
        'dn' => env('LDAP_BASE_DN', 'dc=localhost,dc=com'),
        'username' => env('LDAP_USERNAME', 'user'),
        'password' => env('LDAP_PASSWORD', 'password'),
        'tool_cn' => env('LDAP_TOOL_CN', 'passbolt'),
    ]
];
