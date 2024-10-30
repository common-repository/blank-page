<!DOCTYPE html>
	<html>
	<head>
		<title>{{title}}</title>
		<style type="text/css">
			html {
				height: 100%;
			}
			body {
				min-height: 100%;
				font-family: sans-serif;
				font-size: 16px;
				display: flex;
				justify-content: center;
				align-items: center;
			}
			div {
				width: 100%;
				padding: 1em;
				@media (min-width: 600px) {
					width: 50%;
					padding: 0px;
				}
			}
			h1 {
				text-align: center;
				margin-bottom: 3em;
			}
			p {
				text-align: justify;
			}
		</style>
	</head>
	<body>
		<div>
			<h1>{{title}}</h1>
			<p>{{body}}</p>
		</div>

	<body>
</html>
