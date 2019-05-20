<?php

use DrewM\MailChimp\MailChimp;
use SilverStripe\SiteConfig\SiteConfig;

class MailchimpHelper
{
    /**
     * MailchimpHelper constructor.
     * @throws Exception
     */
    public function __construct()
    {
        $siteConfig = SiteConfig::current_site_config();
        $apiKey = $siteConfig->MailchimpAPIKey;

        if ($apiKey === '' || $apiKey === null) {
            throw new Exception('You must enter an API key in Site Config to use this class');
        }

        $mailChimp = new MailChimp($apiKey);

        $result = $mailChimp->get('lists');
        print_r($result);
        var_dump($result);
        die();
    }
}