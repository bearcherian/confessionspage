<!DOCTYPE html>
<html>
<head>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>
function submitConfession() {
	var post = $("#confession").val();
	$("#confessionform").hide();
	$("#complete").html("Please wait...");
	$.ajax({
		url: "submit/",
		type: "POST",
		data: {confession: post},
		success: function() {
			$("#complete").html("<h4>Confession Submitted</h4><p>" + post + "</p>");
		},
		error: function() {
			$("#complete").html("<h4>Problem with submission</h4>");
		}
	});
	return false;
}

//Bind function to form submit
$(document).ready(function() {
	//$("#cform").on("submit",submitConfession);
});
</script>
<title>Confessions form</title>
</head>
<body>
<div id="confessionform">
	<form id="cform" method="post">
	<textarea id="confession" name="confession" rows="5" cols="50"></textarea>
	<input type="button" value="Submit" onclick="submitConfession();">
	</form>
</div>
<div id="complete"></div>
</body>
</html>
