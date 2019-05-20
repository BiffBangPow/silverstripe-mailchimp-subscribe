<?php

namespace BiffBangPow\SilverStripeMailchimpSubscribe\Extensions;

use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\DataExtension;

class MailchimpSiteConfigExtension extends DataExtension
{
    /**
     * @var array
     */
    private static $db = [
        'MailchimpAPIKey'          => 'Varchar(200)',
        'MailchimpSubscribeListID' => 'Varchar(200)',
    ];

    /**
     * @param FieldList $fields
     * @return void
     */
    public function updateCMSFields(FieldList $fields)
    {
        $fields->addFieldsToTab('Root.Mailchimp', [
            TextField::create('MailchimpAPIKey', 'Mailchimp API Key'),
            TextField::create('MailchimpSubscribeListID', 'Mailchimp Subscribe List ID'),
        ]);
    }
}
