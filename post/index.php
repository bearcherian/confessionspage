<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" src="//normalize-css.googlecode.com/svn/trunk/normalize.css" />
<link rel="stylesheet" type="text/css" href="css/styles.css">
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<link href='http://fonts.googleapis.com/css?family=Libre+Baskerville:400,700,400italic' rel='stylesheet' type='text/css'>
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
			$("#complete").html("<h4>We got it!</h4><p id='confirmation'>" + post + "</p>");
		},
		error: function() {
			$("#complete").html("<h4>Sorry, something broke. Try agian in a litte bit.</h4>");
		}
	});
	return false;
}

$(document).ready(function() {  
    $("textarea[maxlength]").bind("keyup input paste", function() {
        var limit = parseInt($(this).attr('maxlength'));  
        var text = $(this).val();  
        var chars = text.length;  
  
        if(chars > limit){  
            var new_text = text.substr(0, limit);   
            $(this).val(new_text);  
        }  
    });  
});

</script>
<title>Confessions form</title>
</head>
<body>
<div id="confessionform">
	<p>
	Submit your confession:
	</p>
	<form id="cform" method="post">
	<textarea maxlength="512" id="confession" name="confession" rows="10" cols="50"></textarea>
	<button id="submit" type="button" value="Submit" onclick="submitConfession();">
	Submit
	</button>
	</form>
</div>
<div id="complete"></div>
</body>
</html>
