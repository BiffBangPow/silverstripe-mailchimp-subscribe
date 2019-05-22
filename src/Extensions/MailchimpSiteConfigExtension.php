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
        'MailchimpAPIKey'                        => 'Varchar(200)',
        'MailchimpSubscribeListID'               => 'Varchar(200)',
        'MailchimpMailoutHeaderBackgroundColour' => 'Varchar(200)',
        'MailchimpMailoutHeaderTextColour'       => 'Varchar(200)',
        'MailchimpMailoutBodyBackgroundColour'   => 'Varchar(200)',
        'MailchimpMailoutBodyTextColour'         => 'Varchar(200)',
        'MailchimpMailoutButtonBackgroundColour' => 'Varchar(200)',
        'MailchimpMailoutButtonTextColour'       => 'Varchar(200)',
        'MailchimpMailoutButtonBorderRadius'     => 'Varchar(200)',
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
            TextField::create('MailchimpMailoutHeaderBackgroundColour'),
            TextField::create('MailchimpMailoutHeaderTextColour'),
            TextField::create('MailchimpMailoutBodyBackgroundColour'),
            TextField::create('MailchimpMailoutBodyTextColour'),
            TextField::create('MailchimpMailoutButtonBackgroundColour'),
            TextField::create('MailchimpMailoutButtonTextColour'),
            TextField::create('MailchimpMailoutButtonBorderRadius'),
        ]);
    }
}
