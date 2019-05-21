<?php

use DrewM\MailChimp\MailChimp;
use SilverStripe\SiteConfig\SiteConfig;
use SilverStripe\View\ArrayData;
use SilverStripe\Blog\Model\BlogPost;

class MailchimpHelper
{
    private $defaultListID;

    private $mailChimp;

    private $siteConfig;

    /**
     * MailchimpHelper constructor.
     * @throws Exception
     */
    public function __construct()
    {
        $this->siteConfig = SiteConfig::current_site_config();
        $apiKey = $this->siteConfig->MailchimpAPIKey;
        $defaultListID = $this->siteConfig->MailchimpSubscribeListID;

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

    /**
     * @param BlogPost $blogPost
     * @param string|null $listID
     * @return boolean
     * @throws Exception
     */
    public function sendNewPostAlert(BlogPost $blogPost, string $listID = null)
    {
        $summary = $blogPost->Summary;
        if (!$summary) {
            $summary = sprintf('<p>%s</p>', $blogPost->Excerpt(100));
        }

        $publishDate = DateTime::createFromFormat('Y-m-d H:i:s', $blogPost->PublishDate);

        $postData = [
            'Title'         => $blogPost->Title,
            'Summary'       => $summary,
            'PublishDate'   => $publishDate->format('jS F Y'),
            'FeaturedImage' => $blogPost->FeaturedImage()->scaleMaxWidth(795)->fill(795,530)->Link(),
        ];

        $postArrayData = new SilverStripe\View\ArrayData($postData);

        $listID = $listID ?? $this->defaultListID;
        $siteTitle = $this->siteConfig->Title;
        $replyTo = $this->siteConfig->ContactFromEmail;

        $result = $this->mailChimp->post("campaigns", [
            'recipients' => [
                'list_id' => $listID,
            ],
            'type'       => 'regular',
            'settings'   => [
                'subject_line' => sprintf('%s: %s', $siteTitle, $postData['Title']),
                'from_name'    => $siteTitle,
                'reply_to'     => $replyTo,
            ],
        ]);

        $campaignID = $result['id'];
        $html = $postArrayData->renderWith('NewPostEmailTemplate');

        $result = $this->mailChimp->put(
            "/campaigns/$campaignID/content",
            [
                "html" => $html->forTemplate(),
            ]
        );

        // The API doesn't return status if it is successful it returns the HTML, if it returns status here has been an error
        if (array_key_exists('status', $result)) {
            throw new Exception('An error has occurred adding content to the campaign ' . $campaignID);
        }

        $result = $this->mailChimp->post("/campaigns/$campaignID/actions/send");

        // The API just returns true if it successful, so we can only check the status of the error if it is not successful
        if (is_array($result)) {
            // The API doesn't return status if it is successful it returns the HTML, if it returns status here has been an error
            if (array_key_exists('status', $result)) {
                throw new Exception(
                    sprintf(
                        'A %s error has occured with the message "%s"',
                        $result['status'],
                        $result['detail']
                    )
                );
            }
        }

        return $result;
    }
}