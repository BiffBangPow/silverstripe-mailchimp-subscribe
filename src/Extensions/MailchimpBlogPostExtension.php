<?php

namespace BiffBangPow\SilverStripeMailchimpSubscribe\Extensions;

use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\ReadonlyField;
use SilverStripe\ORM\DataExtension;
use SilverStripe\ORM\FieldType\DBBoolean;

class MailchimpBlogPostExtension extends DataExtension
{
    /**
     * @var array
     */
    private static $db = [
        'MailchimpMailoutSent' => DBBoolean::class,
    ];

    /**
     * @param FieldList $fields
     * @return void
     */
    public function updateCMSFields(FieldList $fields)
    {
        if ($this->owner->MailchimpMailoutSent === '1') {
            $mailchimpMailoutSent = 'Yes';
        } else {
            $mailchimpMailoutSent = 'No';
        }

        $fields->removeByName('MailchimpMailoutSent');

        $fields->addFieldsToTab('Root.Mailchimp', [
            ReadonlyField::create('MailchimpMailout', 'Mailchimp Mailout Sent')->setValue($mailchimpMailoutSent),
        ]);
    }
}
