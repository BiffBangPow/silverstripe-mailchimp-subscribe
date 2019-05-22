<?php

namespace BiffBangPow\SilverStripeMailchimpSubscribe\Helpers;

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
     * @throws \Exception
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
            throw new \Exception('You must enter an API key in Site Config to use this class');
        }

        if (
            $defaultListID === '' ||
            $defaultListID === null
        ) {
            throw new \Exception('You must enter a default list ID in Site Config to use this class');
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
            case 'subscribed':
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
     * @throws \Exception
     */
    public function sendNewPostAlert(BlogPost $blogPost, string $listID = null)
    {
        $result = $this->mailChimp->post("campaigns", [
            'recipients' => [
                'list_id' => $listID ?? $this->defaultListID,
            ],
            'type'       => 'regular',
            'settings'   => [
                'subject_line' => sprintf('%s: %s', $this->siteConfig->Title, $blogPost->Title),
                'from_name'    => $this->siteConfig->Title,
                'reply_to'     => $this->siteConfig->MailchimpFromEmail,
            ],
        ]);

        if (!array_key_exists('status', $result) || $result['status'] !== 'save') {
            throw new \Exception('An error has occured finding the list ' . $listID . ' please check this is correct in settings, also check your "Mailchimp From Email" is correct');
        }

        $campaignID = $result['id'];

        $result = $this->mailChimp->put(
            "/campaigns/$campaignID/content",
            [
                "html" => $this->getEmailHTML($blogPost),
            ]
        );

        // The API doesn't return status if it is successful it returns the HTML, if it returns status here has been an error
        if (array_key_exists('status', $result)) {
            throw new \Exception('An error has occurred adding content to the campaign ' . $campaignID);
        }

        $result = $this->mailChimp->post("/campaigns/$campaignID/actions/send");

        // The API just returns true if it successful, so we can only check the status of the error if it is not successful
        if (is_array($result)) {
            // The API doesn't return status if it is successful it returns the HTML, if it returns status here has been an error
            if (array_key_exists('status', $result)) {
                throw new \Exception(
                    sprintf(
                        'A %s error has occured with the message "%s"',
                        $result['status'],
                        $result['detail']
                    )
                );
            }
        }

        if ($result === true) {
            $blogPost->MailchimpMailoutSent = true;
            $blogPost->write();
            $blogPost->publishSingle();
        }

        return $result;
    }

    /**
     * @param BlogPost $blogPost
     * @return string
     */
    public function getEmailHTML(BlogPost $blogPost)
    {
        $summary = $blogPost->Summary;

        if (!$summary) {
            $summary = sprintf('<p>%s</p>', $blogPost->Excerpt(100));
        }

        $publishDate = \DateTime::createFromFormat('Y-m-d H:i:s', $blogPost->PublishDate);
        $siteTitle = $this->siteConfig->Title;

        $postData = [
            'Title'         => $blogPost->Title,
            'Summary'       => $summary,
            'PublishDate'   => $publishDate->format('jS F Y'),
            'FeaturedImage' => $blogPost->FeaturedImage(),
            'SiteTitle'     => $siteTitle,
            'Link'          => $blogPost->AbsoluteLink(),
        ];

        $postArrayData = new ArrayData($postData);
        $html = $postArrayData->renderWith('NewPostEmailTemplate');
        return $html->forTemplate();
    }

    /**
     * This is very useful for building the templates
     *
     * @param BlogPost $blogPost
     */
    public function echoEmailHTML(BlogPost $blogPost)
    {
        echo $this->getEmailHTML($blogPost);
    }
}