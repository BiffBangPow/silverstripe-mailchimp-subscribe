<h2>$Title</h2>
<h4>$PublishDate</h4>
<% if $FeaturedImage %>
    <img src="$FeaturedImage.ScaleMaxWidth(795).Fill(795,530).Link" style="max-width: 100%;" />
<% end_if %>
$Summary.RAW