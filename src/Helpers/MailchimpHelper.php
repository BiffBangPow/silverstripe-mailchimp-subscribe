<?php

use DrewM\MailChimp\MailChimp;
use SilverStripe\SiteConfig\SiteConfig;

class MailchimpHelper
{
    private $defaultListID;

    private $mailChimp;

    /**
     * MailchimpHelper constructor.
     * @throws Exception
     */
    public function __construct()
    {
        $siteConfig = SiteConfig::current_site_config();
        $apiKey = $siteConfig->MailchimpAPIKey;
        $defaultListID = $siteConfig->MailchimpSubscribeListID;

        if (
            $apiKey === '' ||
            $apiKey === null
        ) {
            throw new Exception('You must enter an API key in Site Config to use this class');
        }

        if (
            $defaultListID === '' ||
            $defaultListID === null
        ) {
            throw new Exception('You must enter a default list ID in Site Config to use this class');
        }

        $this->defaultListID = $defaultListID;

        $this->mailChimp = new MailChimp($apiKey);
    }

    /**
     * @param string $emailAddress
     * @param string|null $listID
     * @return bool
     */
    public function subscribeUserToList(string $emailAddress, string $listID = null)
    {
        $listID = $listID ?? $this->defaultListID;
        $result = $this->mailChimp->post("lists/$listID/members", [
            'email_address' => $emailAddress,
            'status'        => 'subscribed',
        ]);

        $status = $result['status'];

        switch ($status) {
            case 200:
            case 400:
                return true;
                break;
            default:
                return false;
                break;
        }
    }


    public function sendNewPostAlert(string $listID = null)
    {
        $postData = [
            'Title'         => 'Test post title here',
            'Summary'       => '<p>Pellentesque id nulla nec orci volutpat congue id quis urna. Donec scelerisque consectetur lorem vitae euismod. Phasellus convallis mi augue, vitae commodo nunc lobortis a. Mauris facilisis facilisis dictum. Morbi in neque pulvinar, mollis quam et, suscipit metus.</p>',
            'PublishDate'   => '20/05/2019',
            'FeaturedImage' => 'https://images.pexels.com/photos/2287129/pexels-photo-2287129.jpeg',
        ];
        $postArrayData = new SilverStripe\View\ArrayData($postData);

        $listID = $listID ?? $this->defaultListID;

        $result = $this->mailChimp->post("campaigns", [
            'recipients' => [
                'list_id' => $listID,
            ],
            'type'       => 'regular',
            'settings'   => [
                'subject_line' => 'New post from SITE TITLE',
                'reply_to'     => 'james.h@biffbangpow.com',
                'from_name'    => 'SITE TITLE',
            ],
        ]);

        $campaignID = $result['id'];
        $html = $postArrayData->renderWith('NewPostEmailTemplate');

        echo $html;
        die();

        $result = $this->mailChimp->put(
            "/campaigns/$campaignID/content",
            [
                "html" => $html
            ]
        );

        echo '<pre>';
        var_dump($result);
        echo '</pre>';
        die();

        $result = $this->mailChimp->post("/campaigns/$campaignID/actions/send");

        echo '<pre>';
        var_dump($result);
        echo '</pre>';
        die();
    }

    public function dumpLists()
    {
        $result = $this->mailChimp->get("lists");
        echo '<pre>';
        var_dump($result);
        echo '</pre>';
        die();
    }
}