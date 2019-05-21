<?php

namespace BiffBangPow\SilverStripeMailchimpSubscribe\Extensions;

use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\FormAction;
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

    /**
     * @param FieldList $actions
     */
    public function updateCMSActions(FieldList $actions)
    {
        parent::updateCMSActions($actions);

        if ($this->owner->MailchimpMailoutSent === '1') {
            $buttonClasses = 'btn-outline-primary font-icon-tick';
            $buttonText = 'Mailout sent';
        } else {
            $buttonClasses = 'btn-primary font-icon-share';
            $buttonText = 'Send mailout';
        }

        $actions->fieldByName('MajorActions')
            ->push(
                FormAction::create('doMailout', $buttonText)
                    ->addExtraClass('btn action ' . $buttonClasses)
                    ->setUseButtonTag(true)
            );
    }
}
