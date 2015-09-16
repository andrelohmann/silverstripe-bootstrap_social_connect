<div class="container">
    <div class="row">
        <div class="col-sm-12">
        <h1><%t FacebookConnect.EMAILEXISTSTITLE "FacebookConnect.EMAILEXISTSTITLE" %></h1>
		<p><%t FacebookConnect.EMAILEXISTS "FacebookConnect.EMAILEXISTS" %> $Member.Email</p>
		<p><%t FacebookConnect.EMAILEXISTSSERVICE "FacebookConnect.EMAILEXISTSSERVICE" %></p>
		<% if $Member.SocialConnectType == 'facebook' %>
		<p><% include FacebookLogin %></p>
		<% else_if $Member.SocialConnectType == 'google' %>
		<p><% include GoogleLogin %></p>
		<% else_if $Member.SocialConnectType == 'instagram' %>
		<p><% include InstagramLogin %></p>
		<% else_if $Member.SocialConnectType == 'twitter' %>
		<p><% include TwitterLogin %></p>
		<% else %>
		<p><a href="Security/login" class="btn btn-default btn-lg btn-block"><%t SocialConnect.LOGINBYEMAIL "SocialConnect.LOGINBYEMAIL" %></a></p>
        <% end_if %>
        </div>
    </div>
</div>
