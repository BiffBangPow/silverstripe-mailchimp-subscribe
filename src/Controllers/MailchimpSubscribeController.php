<?php

namespace BiffBangPow\SilverStripeMailchimpSubscribe\Controllers;

use DNADesign\Elemental\Controllers\ElementController;
use SilverStripe\Control\Controller;
use SilverStripe\Forms\Form;
use SilverStripe\Control\HTTPResponse;
use SilverStripe\Forms\EmailField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\FormAction;
use SilverStripe\Forms\RequiredFields;
use SilverStripe\Forms\TextField;
use BiffBangPow\SilverStripeMailchimpSubscribe\Helpers\MailchimpHelper;

class MailchimpSubscribeController extends ElementController
{
    /**
     * @var array
     */
    private static $allowed_actions = [
        'SubscribeForm',
    ];

    /**
     * @return Form
     */
    public function SubscribeForm()
    {
        $fields = FieldList::create([
            EmailField::create('Email')->addExtraClass('col-12'),
        ]);

        $actions = FieldList::create(
            FormAction::create('sendSubscribeForm', 'Submit')
        );

        $form = Form::create(
            $this,
            __FUNCTION__,
            $fields,
            $actions,
            new RequiredFields([
                    'Email',
                ]
            ));

        $current = Controller::curr();
        $form->setFormAction(
            Controller::join_links(
                $current->Link(),
                'element',
                $this->owner->ID,
                'SubscribeForm'
            )
        );

        return $form;
    }

    /**
     * @param $data
     * @param $form
     * @return HTTPResponse
     * @throws Exception
     */
    public function sendSubscribeForm($data, $form)
    {
        $element = $this->getElement();

        $listID = null;
        if ($element->SubscribeListIDOverride !== null && $element->SubscribeListIDOverride !== '') {
            $listID = $element->SubscribeListIDOverride;
        }

        $data = $form->getData();
        $mailchimp = new MailchimpHelper();

        $result = $mailchimp->subscribeUserToList($data['Email'], $listID);

        if ($result === true) {
            $this->flashMessage('Thankyou for subscribing', 'good');
        } else {
            $this->flashMessage('You could not be subscribed, please check your email address and try again', 'danger');
        }

        $link = $current = Controller::curr()->Link();

        // Using get page is better as it does not include the id of the element
        if ($this->hasMethod('getPage')) {
            if ($page = $this->getPage()) {
                $link = $page->Link();
            }
        }

        return $this->redirect($link);
    }

}