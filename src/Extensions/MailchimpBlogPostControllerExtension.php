<?php

namespace BiffBangPow\SilverStripeMailchimpSubscribe\Extensions;

use SilverStripe\Core\Extension;

class MailchimpBlogPostControllerExtension extends Extension
{
    public function domailout()
    {
        var_dump('mailout');
    }
}
