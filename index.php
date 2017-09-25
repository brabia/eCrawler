<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
		<link rel="stylesheet" href="css/eCrawler.css" />
	</head>
	<body>   
		<div class="container">
			<h1>eCrawler</h1>
			<div class="form-group">
				<label for="exampleInputEmail1">Page URL</label>
				<input value="http://www.zappingmag.com/" type="email" class="form-control" id="pageUrl" aria-describedby="Page URL" placeholder="Page URL">
				<small class="form-text text-muted">Page URL</small>
			</div>
			<button type="submit" class="btn emailList">Show Emails</button>
			<button type="submit" class="btn btn-primary">Submit</button>
			<p></p>
			<div class="ajax"></div>
			<table class="table table-striped">
				<thead>
					<tr>
						<th>Page URL</th><th>Email</th>
					</tr>
				</thead>
				<tbody></tbody>
			</table>
		</div>
		
		<script
		src="https://code.jquery.com/jquery-3.2.1.min.js"
		integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
		crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"
		integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4"
		crossorigin="anonymous"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js"
		integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1"
		crossorigin="anonymous"></script>
		<script src="js/eCrawler.js"></script>
		<script src="https://cdn.ravenjs.com/3.12.0/raven.min.js"></script>
		<script>Raven.config('https://8124449e10284bd19b0ef701553a085b@sentry.io/143897').install()</script>
	</body>
</html>