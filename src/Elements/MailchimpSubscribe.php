<?php

namespace BiffBangPow\SilverStripeMailchimpSubscribe\Elements;

use BiffBangPow\SilverStripeMailchimpSubscribe\Controllers\MailchimpSubscribeController;
use DNADesign\Elemental\Models\BaseElement;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\FieldType\DBVarchar;

class MailchimpSubscribe extends BaseElement
{
    /**
     * @var string
     */
    private static $table_name = 'ElementMailchimpSubscribe';

    private static $singular_name = 'mailchimp subscribe element';

    private static $plural_name = 'mailchimp subscribe elements';

    private static $description = 'A simple form to signup to a Mailchimp mailing list';

    private static $controller_class = MailchimpSubscribeController::class;

    /**
     * @var array
     */
    private static $db = [
        'SubscribeListIDOverride' => DBVarchar::class,
    ];

    /**
     * @return FieldList
     */
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->addFieldsToTab('Root.Main', [
            TextField::create('SubscribeListIDOverride', 'Subscribe List ID Override')
                ->setDescription('If this is left blank the Mailchimp Subscribe List ID from Settings will be used'),
        ]);

        return $fields;
    }

    public function SubscribeForm()
    {
        return $this->getController()->SubscribeForm();
    }

    /**
     * @return string
     */
    public function getType()
    {
        return 'Mailchimp Subscribe';
    }

    public function getSimpleClassName()
    {
        return 'mailchimp-subscribe-element';
    }
}
