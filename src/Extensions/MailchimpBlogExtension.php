<?php

namespace BiffBangPow\SilverStripeMailchimpSubscribe\Extensions;

use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\ReadonlyField;
use SilverStripe\ORM\DataExtension;
use SilverStripe\ORM\FieldType\DBBoolean;

class MailchimpBlogExtension extends DataExtension
{
    /**
     * @param FieldList $fields
     * @return void
     */
    public function updateCMSFields(FieldList $fields)
    {

    }
}
