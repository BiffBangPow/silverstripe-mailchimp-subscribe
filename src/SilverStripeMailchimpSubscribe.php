<?php

namespace BiffBangPow\SilverStripeMailchimpSubscribe;

use SilverStripe\Core\Config\Configurable;

class SilverStripeMailchimpSubscribe
{
    use Configurable;

    /**
     * @config
     */
    private static $email_field_class = 'col-12';

    /**
     * @config
     */
    private static $submit_button_class = 'btn btn-primary';
}