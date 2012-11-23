<?php
$emailTo = 'your@email.com'; //Put your own email address here

//If the form is submitted
if(isset($_POST['submit'])) {

	//Check to make sure that the name field is not empty
	if(trim($_POST['contactname']) == '') {
		$hasError = true;
	} else {
		$name = trim($_POST['contactname']);
	}

	//Check to make sure that the subject field is not empty
	if(trim($_POST['subject']) == '') {
		$hasError = true;
	} else {
		$subject = trim($_POST['subject']);
	} 

	//Check to make sure sure that a valid email address is submitted
	if(trim($_POST['email']) == '')  {
		$hasError = true;
	} else if (!eregi("^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,4}$", trim($_POST['email']))) {
		$hasError = true;
	} else {
		$email = trim($_POST['email']);
	}

	//Check to make sure comments were entered
	if(trim($_POST['message']) == '') {
		$hasError = true;
	} else {
		if(function_exists('stripslashes')) {
			$comments = stripslashes(trim($_POST['message']));
		} else {
			$comments = trim($_POST['message']);
		}
	}

	//If there is no error, send the email
	if(!isset($hasError)) {
		$body = "Name: $name \n\nEmail: $email \n\nSubject: $subject \n\nComments:\n $comments";
		$headers = 'From: My Site <'.$emailTo.'>' . "\r\n" . 'Reply-To: ' . $email;

		mail($emailTo, $subject, $body, $headers);
		$emailSent = true;
	}
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head> 
    <title>Foundation - Premium HTML Template</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    
    <!-- Styles -->
    <link rel="stylesheet" type="text/css" href="/survey/res/css/style.css" />
    
    <!-- JavaScript -->
    <script type="text/javascript" src="/survey/res/js/jquery-1.6.2.min.js"></script>
    <script type="text/javascript" src="/survey/res/js/superfish.js"></script>
    <script type="text/javascript" src="/survey/res/js/jquery.nivo.slider.pack.js"></script>
    <script type="text/javascript" src="/survey/res/js/jquery.prettyPhoto.js"></script>
    <script type="text/javascript" src="/survey/res/js/jquery.prettySociable.js"></script>
    <script type="text/javascript" src="/survey/res/js/jquery.validate.min.js"></script>
    <script type="text/javascript" src="/survey/res/js/main.js"></script>
    
</head>

<body id="page-post"><!-- #home || #page-post || #blog || #portfolio -->

    <!-- Page Start -->
    <div id="page">
        
        <!-- Main Column Start -->
        <div id="wrap">
            <div id="main-col">
            
                <a href="#" id="share" rel="prettySociable" title="Drag to Share"><img src="/survey/res/img/share.png" alt="" /></a>
        	
                <!-- Breadcrumbs, Page Title -->
                <div id="page-head">
                    <h1>Contact</h1>
                    <ul id="breadcrumbs">
                        <li><a href="#">Home</a></li>
                        <li>Contact</li>
                    </ul>
                </div>
                
                <!-- Page Content Start -->
                <div class="full-page-text">
                
                    <p>At as in understood an remarkably solicitude. Mean them very seen she she. Use totally written the observe pressed justice. Instantly cordially far intention recommend estimable yet her his. Ladies stairs enough esteem add fat all enable.</p>
                    <h3>Our Locations</h3>
                    
                    <!-- Start Accordion With Maps and Addresses -->
                    <div class="accordion">
                    
                        <div class="accordionTitle">Minsk, Belarus</div>
                        <div class="accordionContent">
                            <div class="alignright">
                                <iframe width="260" height="160" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.com/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q=minsk+belarus&amp;aq=&amp;sll=37.0625,-95.677068&amp;sspn=54.357317,135.263672&amp;vpsrc=0&amp;ie=UTF8&amp;hq=&amp;hnear=Minsk,+Minski+rajon,+Minsk+Province,+Belarus&amp;t=m&amp;ll=53.899888,27.566757&amp;spn=0.064731,0.177841&amp;z=11&amp;iwloc=A&amp;output=embed"></iframe>
                            </div>
                        </div>
    
                        <div class="accordionTitle">Berlin, European Union</div>
                        <div class="accordionContent">
                            <div class="alignright">
                                <iframe width="260" height="160" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.com/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q=berlin&amp;aq=&amp;sll=53.9,27.566667&amp;sspn=0.319611,1.056747&amp;vpsrc=0&amp;ie=UTF8&amp;hq=&amp;hnear=Berlin,+Germany&amp;t=m&amp;ll=52.524577,13.406067&amp;spn=0.133686,0.355682&amp;z=10&amp;iwloc=A&amp;output=embed"></iframe>
                            </div>
                        </div>
    
                        <div class="accordionTitle">Washington, North America</div>
                        <div class="accordionContent">
                            <div class="alignright">
                                <iframe width="260" height="160" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.com/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q=Washington,+DC,+United+States&amp;aq=0&amp;sll=52.524268,13.40629&amp;sspn=0.660079,2.113495&amp;vpsrc=0&amp;ie=UTF8&amp;hq=&amp;hnear=Washington,+District+of+Columbia&amp;t=m&amp;ll=38.895041,-77.036476&amp;spn=0.042753,0.088921&amp;z=12&amp;iwloc=A&amp;output=embed"></iframe>
                            </div>
                        </div>
                        
                    </div>
                    <!-- Accordion With Maps and Addresses End -->
                    
                    <?php
                    if(isset($hasError)) { //If errors are found ?>
                        <p class="error">Please check if you've filled all the fields with valid information.</p><?php
                    }
                    
                    if(isset($emailSent) && $emailSent == true) { //If email is sent ?>
                        <p class="success"><strong>Email Successfully Sent!</strong></p>
                        <p>Thank you <strong><?php echo $name;?></strong> for contacting us! Your email was successfully sent and we will be in touch with you soon.</p><?php
                    } ?>
                    
                    <!-- Contact Form -->
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" id="contact">
                        <fieldset>
                            <label id="name_label" for="name">Name</label>
                            <input type="text" value="" size="50" id="contactname" name="contactname" class="required" />
                        </fieldset>
                        <fieldset>
                            <label id="email_label" for="email">E-mail</label>
                            <input type="text" value="" size="50" id="email" name="email" class="required email" />
                        </fieldset>
                        <fieldset>
                            <label id="subject_label" for="subject">Subject</label>
                            <input type="text" size="50" value="" id="subject" name="subject" />
                        </fieldset>
                        <fieldset>
                            <label id="msg_label" for="msg">Message</label>
                            <textarea id="message" name="message" rows="10" cols="10" class="required"></textarea>
                        </fieldset>
                        <input type="submit" value="Send Message" name="submit" />
                    </form>
    
                </div>
                <!-- Page Content End -->
    
            </div>
        </div>
        <!-- Main Column End -->
        
        <!-- Left Column Start -->
        <div id="left-col">
        
            <!-- Logo -->
            <a href="index.html" id="logo"><img src="/survey/res/img/colors/primary-blue/logo.png" alt="Foundation" /></a>
            
            <!-- Main Navigation (active - .act) -->
            <div id="main-nav">
                <ul>
                    <li><a href="index.html">Home</a></li>
                    <li>
                        <a href="#">Elements</a>
                        <ul>
                            <li><a href="typography.html">Typography</a></li>
                            <li><a href="lists.html">Lists</a></li>
                            <li><a href="tables.html">Tables</a></li>
                            <li><a href="columns.html">Columns</a></li>
                            <li><a href="images.html">Images</a></li>
                            <li><a href="tabs-toggles.html">Tabs, Toggles</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#">Templates</a>
                        <ul>
                            <li><a href="page.html">Page</a></li>
                            <li><a href="blog.html">Blog</a></li>
                            <li><a href="post.html">Post</a></li>
                            <li><a href="portfolio.html">Portfolio</a></li>
                            <li><a href="portfolio-item.html">Project</a></li>
                        </ul>
                    </li>
                    <li><a href="#">Empty</a></li>
                    <li class="current-menu-item"><a href="contact.html">Contact</a></li>
                </ul>
            </div>
            
            <!-- News Widget -->
            <div class="widget w-news">
                <h4 class="w-title title-light">Company News</h4>
                <div class="w-content">
                    <ul>
                        <li>
                            <span><strong>24</strong>May</span>
                            <a href="#">Do esteem object we called father excuse remove</a>
                        </li>
                        <li>
                            <span><strong>6</strong>Jan</span>
                            <a href="#">In reasonable compliment favourable is connection</a>
                        </li>
                        <li>
                            <span><strong>11</strong>Jan</span>
                            <a href="#">Topps Tiles sales continue downward trend</a>
                        </li>
                    </ul>
                </div>
            </div>
                
        </div>
        <!-- Left Column End -->
    
        <div class="clear clear-wrap"></div>
    
        <!-- Footer Start -->
        <div id="footer">
        
            <!-- Subscribe Form and Copyright Text -->
            <div id="f-left-col">
                <div id="sidebar-end">
                    <form action="#" id="subscribe">
                        <input type="text" value="" alt="Get latest news and offers!" title="Enter your e-mail" />
                        <input type="submit" value="" title="Subscribe" />
                    </form>
                </div>
                <div id="copyright">&copy; 2012 Foundation Company</div>
            </div>
            
            <!-- Footer Widgets -->
            <div id="f-main-col">
                <!-- Links -->
                <div class="widget col-25">
                    <ul>
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Our Team</a></li>
                        <li><a href="#">Site Map</a></li>
                        <li><a href="#">Contact Us</a></li>
                    </ul>
                </div>
                <!-- Social -->
                <div class="widget col-25">
                    <h5 class="w-title">Follow Us:</h5>
                    <ul>
                        <li><a href="#">Twitter</a></li>
                        <li><a href="#">Facebook</a></li>
                        <li><a href="#">Google+</a></li>
                    </ul>
                </div>
                <!-- Contact Info -->
                <div class="widget col-50 c-last">
                    <h5 class="w-title">Contact Options:</h5>
                    <div class="w-content">
                        <a href="#"><img src="/survey/res/img/pictures/office.jpg" alt="Our Building" class="alignright" /></a>
                        Country, City, Address<br />
                        Tel.: +44 20 7367 8000<br />
                        <a href="#">inbox@foundation.com</a>
                    </div>
                </div>
            </div>
            
            <div class="clear"></div>
        </div>
        <!-- Footer End -->
        
    </div>
    <!-- Page End -->

</body>
</html>