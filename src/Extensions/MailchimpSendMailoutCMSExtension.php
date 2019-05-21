<?php

namespace BiffBangPow\SilverStripeMailchimpSubscribe\Extensions;

use SilverStripe\Admin\LeftAndMainExtension;

class MailchimpSendMailoutCMSExtension extends LeftAndMainExtension
{

    private static $allowed_actions = [
        'doMailout'
    ];

    public function doMailout()
    {
        // Create the web
    }

}