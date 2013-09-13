<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="css/bootstrap-theme.min" media="print" />
	 <script type="text/javascript" src="js/jquery.js"></script>
	<title>Post From Your wall</title>
	

<script>
$(document).ready(function(){
$("#postonwall").click(function(){
	var userid = $("#userid").val();
	var link = 'http://google.com';
	$("#postonwall").hide();
	$("#sharing").show();
        var link = 'statusupdates.herokuapp.com';
		var msg = "I have got all of my recent status and total no of likes and comments using this link. You can also get for yours.";
		$.ajax({
		dataType : 'html',
		type: 'POST',
		url : '#',
		//cache: false,
		
		data :
		{
			"link":link,
			"msg":msg
		},
		complete : function() { },
		success: function(data) 
		{
			$("#success").show();
			$("#sharing").hide();
			$("#postonwall").show();
		}
});
});
});
</script>
</head>

<body>

<div class="container" id="page">

    <div class="navbar navbar-inverse navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">Post On Wall</a>
            </div>
            <div class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li class="active"><a href="#">Home</a></li>
                    

                </ul>

            </div><!--/.navbar-collapse -->
        </div>
    </div>



        <div class="container" style="margin-top: 45px;">
			
<div class="col-md-12" style="margin-top:25px;margin-bottom:45px;">
<div class="col-md-9">
Here is the list of your status updates. You can see the likers or commentor name also.
this feature is coming soon ...... 
</div>

<div class="col-md-3">
<div id='success' class='alert alert-success' style='display:none;'>Share on your wall successfully</div>
<div id='sharing' style='display:none;'>Wait !!! Posting on Your wall .....
<img class="image marginR10" src="images/loading.gif"; ?>"/>
</div>
<input type="button" class="btn btn-primary" id='postonwall' value="Share with friends">
</div>
</div>

<?php
include "facebookAllInOne.php";
$url = "https://".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
$facebook = new facebookAllInOne('XXXXXXXX','XXXXXXXXXXXXXXXXXXXXXXXXXXX',$url);

$pagefeed = $facebook->GetPostOnWall();

$user_details = $facebook->getUserDetailWithEmail();
$user_id = json_decode($user_details)->id;


$i = 0;
$header = '';
foreach($pagefeed['data'] as $post) {
 
if ($post['type'] == 'status' || $post['type'] == 'link' || $post['type'] == 'photo') {

	// open up an fb-update div
	$header = $header;

		// post the time

		// check if post type is a status
		if ($post['type'] == 'status') {
			if(isset($post['message'])){
			$header = $header."<h2>Status updated on: " . date("jS M, Y", (strtotime($post['created_time']))) . "</h2>";
		   // echo "id : ".$post['id']."<br/>";
			if(isset($post['likes']))	
				$header = $header."<h3>Total No of Likes =".count($post['likes']['data'])."</h3>";
			else
				$header = $header."<h3>Total No of Likes = 0 </h3>";
				
			$header = $header."<br/>";
			
			//print_r($post['comments']);
			if(isset($post['comments']))
			{				
				$header = $header."<h3>Total No of Comments =".count($post['comments']['data'])."</h3>";
						
			}
			else
				$header = $header."<h3>Total No of Comments = 0</h3>";
			$header = $header."<br/>";
			$header = $header."<pre>".$post['message']."</pre><hr/>";
			//break;
			
			}
			
		}

	  

	$header = $header; // close fb-update div

	$i++; // add 1 to the counter if our condition for $post['type'] is met
}
	//$header = $header."<br/>";
	
    //  break out of the loop if counter has reached 10
    if ($i == 100) {
        break;
    }
} // end the foreach statement
 
$header = $header;
echo $header;
?>
<input type="hidden" name="userid" id="userid" value="<?php echo $user_id;?>">
<?php
if(isset($_POST['link']))
{
	$link = $_POST['link'];
	$msg = $_POST['msg'];
	
	$facebook->postOnWall($link,$msg);
}
?>


        </div>



</div><!-- page -->

</body>
</html>