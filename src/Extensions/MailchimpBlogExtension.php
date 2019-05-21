<?php

namespace BiffBangPow\SilverStripeMailchimpSubscribe\Extensions;

use BiffBangPow\SilverStripeMailchimpSubscribe\Actions\GridFieldBlogPostMailoutAction;
use SilverStripe\Forms\FieldList;
use SilverStripe\ORM\DataExtension;

class MailchimpBlogExtension extends DataExtension
{
    /**
     * @param FieldList $fields
     * @return void
     */
    public function updateCMSFields(FieldList $fields)
    {
        $childPages = $fields->fieldByName('Root.ChildPages');
        $childPagesGrid = $childPages->Fields()->fieldByName('ChildPages');
        $childPagesGrid->getConfig()->addComponent(new GridFieldBlogPostMailoutAction());
    }
}
