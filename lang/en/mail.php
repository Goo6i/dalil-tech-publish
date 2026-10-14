<?php

return [
    'mentioned' => [
        'subject' => ':name mentioned you on Dalil Tech Publish',
        'title' => ':name mentioned you',
        'intro' => ':name mentioned you in a post comment.',
        'cta' => 'View comment',
    ],

    'workspace_connections_disconnected' => [
        'subject' => '{1} :count account needs to be reconnected in :workspace|[2,*] :count accounts need to be reconnected in :workspace',
        'title' => 'Accounts Need Reconnection',
        'intro' => 'The following social accounts in your <strong>:workspace</strong> workspace have been disconnected and need to be reconnected:',
        'reasons_title' => 'This may have happened because:',
        'reason_expired' => 'Access tokens expired',
        'reason_revoked' => 'You revoked access to Dalil Tech Publish on the platform',
        'reason_changed' => 'The platform changed their authentication requirements',
        'reconnect_cta' => 'Please reconnect these accounts to continue scheduling and publishing posts.',
        'button' => 'Reconnect Accounts',
    ],

    'footer' => [
        'tagline' => 'Schedule and publish to every network from one calendar.',
        'site' => 'dalil-tech.com/publish',
        'copyright' => '© :year Dalil Tech',
    ],

    'greeting' => 'Hi :name,',
    'signoff' => 'Best regards,',
    'team' => 'The Dalil Tech Team',

    'verify_email' => [
        'subject' => 'Verify your email address',
        'preview' => 'Please verify your email address.',
        'intro' => 'Please confirm your email address by clicking the button below:',
        'button' => 'Verify Email Address →',
        'ignore' => 'If you did not create an account, you can safely ignore this email.',
    ],

    'password_reset' => [
        'subject' => 'Reset your password',
        'preview' => 'Reset your password.',
        'intro' => 'We received a request to reset your password. Click the button below to create a new password:',
        'button' => 'Reset Password →',
        'expires' => "This link expires in 60 minutes. If you didn't request a password reset, you can safely ignore this email.",
    ],
];
