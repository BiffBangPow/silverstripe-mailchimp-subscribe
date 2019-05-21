<?php

namespace BiffBangPow\SilverStripeMailchimpSubscribe\Extensions;

use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\FieldList;
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
        $fields->addFieldsToTab('Root.Mailchimp', [
            CheckboxField::create('MailchimpMailoutSent', 'Mailchimp Mailout Sent')->setReadonly(true),
        ]);
    }
}
