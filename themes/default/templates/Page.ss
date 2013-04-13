<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	
	<head>
		
		<base href="$BaseHref"><!--[if IE 6]></base><![endif]-->
		
		<title><% if MetaTitle %>$MetaTitle<% else %>$Title<% end_if %> &raquo; $SiteConfig.Title - $SiteConfig.Tagline</title>
		
		$MetaTags(false)
		<meta http-equiv="X-UA-Compatible" content="IE=edge;chrome=1" />

		<link rel="icon" type="image/vnd.microsoft.icon" href="favicon.ico" />
		<link rel="stylesheet" type="text/css" href="$ThemeDir/css/typography.css" />
		<link rel="stylesheet" type="text/css" href="$ThemeDir/css/layout.css" />
		<link rel="stylesheet" type="text/css" href="$ThemeDir/css/form.css" />
		
		<!--[if IE 6]>
			<link rel="stylesheet" type="text/css" href="$ThemeDir/css/ie6.css" />
		<![endif]-->
		
		<!--[if IE 7]>
			<link rel="stylesheet" type="text/css" href="$ThemeDir/css/ie7.css" />
		<![endif]-->
		
		<script type="text/javascript" src="http://www.google.com/jsapi"></script>
		<script src="$ThemeDir/javascript/javascript.js" type="text/javascript"></script>
		
	</head>
	
	<body class="$ClassName">
		
		<div id="Container">
			
			<div id="Header">
				$SearchForm
				<h1>$SiteConfig.Title</h1>
				<p>$SiteConfig.Tagline</p>
			</div>
			
			<% include Navigation %>
			
			<div id="Layout">
				$Layout
			</div>
		
		</div>
		
		<div id="Footer">
			<% include Footer %>
		</div>
		
	</body>
	
</html>
