	<% if Menu(2) %>
		<% include SideBar %>
	<% end_if %>
			
	<% if Level(2) %>
	  	<% include BreadCrumbs %>
	<% end_if %>
	
	<div id="Content" class="typography">
		
		<h2>$Title</h2>
		$Content
		$Form
		$PageComments
		
	</div>