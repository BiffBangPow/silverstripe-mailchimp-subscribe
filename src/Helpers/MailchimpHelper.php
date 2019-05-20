<?php

use DrewM\MailChimp\MailChimp;
use SilverStripe\SiteConfig\SiteConfig;

class MailchimpHelper
{
    private $defaultListID;

    private $mailChimp;

    /**
     * MailchimpHelper constructor.
     * @throws Exception
     */
    public function __construct()
    {
        $siteConfig = SiteConfig::current_site_config();
        $apiKey = $siteConfig->MailchimpAPIKey;
        $defaultListID = $siteConfig->MailchimpSubscribeListID;

        if (
            $apiKey === '' ||
            $apiKey === null
        ) {
            throw new Exception('You must enter an API key in Site Config to use this class');
        }

        if (
            $defaultListID === '' ||
            $defaultListID === null
        ) {
            throw new Exception('You must enter a default list ID in Site Config to use this class');
        }

        $this->defaultListID = $defaultListID;

        $this->mailChimp = new MailChimp($apiKey);
    }

    /**
     * @param string $emailAddress
     * @param string|null $listID
     * @return bool
     */
    public function subscribeUserToList(string $emailAddress, string $listID = null)
    {
        $listID = $listID ?? $this->defaultListID;
        $result = $this->mailChimp->post("lists/$listID/members", [
            'email_address' => $emailAddress,
            'status'        => 'subscribed',
        ]);

        $status = $result['status'];

        switch ($status) {
            case 200:
            case 400:
                return true;
                break;
            default:
                return false;
                break;
        }
    }
}