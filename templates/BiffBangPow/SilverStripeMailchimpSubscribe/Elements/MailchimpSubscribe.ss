<div class="py-4 py-md-6 py-lg-7">
    <div class="container">
        <div class="mailchimp-subscribe">
            <% if $ShowTitle %>
                <h3 class="mb-4 title">$MarkdownText.Title.RAW</h3>
            <% end_if %>
            $SubscribeForm
        </div>
    </div>
</div>
