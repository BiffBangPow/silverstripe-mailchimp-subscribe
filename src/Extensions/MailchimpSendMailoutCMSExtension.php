<?php

namespace BiffBangPow\SilverStripeMailchimpSubscribe\Extensions;

use BiffBangPow\SilverStripeMailchimpSubscribe\Helpers\MailchimpHelper;
use SilverStripe\Admin\LeftAndMainExtension;
use SilverStripe\Blog\Model\BlogPost;
use SilverStripe\CMS\Controllers\CMSPageEditController;
use SilverStripe\Control\Controller;

class MailchimpSendMailoutCMSExtension extends LeftAndMainExtension
{
    /**
     * @var array
     */
    private static $allowed_actions = [
        'doMailout'
    ];

    /**
     * @param $data
     * @param $form
     * @throws \Exception
     */
    public function doMailout($data, $form)
    {
        $id = $data['ID'];
        $blogPost = BlogPost::get()->filter(['ID' => $id])->first();

        if (!$blogPost) {
            throw new \Exception('Blog post not found');
        }

        $mailchimp = new MailchimpHelper();
        $mailchimp->sendNewPostAlert($blogPost);

        Controller::curr()->getResponse()->setStatusCode(
            200,
            'Mailout sent'
        );
    }

}