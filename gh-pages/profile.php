<!-- <?php session_start(); ?> -->
<!DOCTYPE html>
<html>

    <?php  
        include ('databaseConn.php');
        $db = new Database();
        $db->return_details($_SESSION["username"]);
        $db->get_fav_story($_SESSION["username"]);
        $db->get_family($_SESSION["username"]);
    ?>


    <head>
        <title>Stories on the web</title>
        <meta charset="UTF-8">
        <link rel = "stylesheet" href = "styles.css">
    </head>
    <body background = "images/mainBackground2.jpg">
        <img src = "images/logo.png" class = "logo">
        <div class = "upper_menu">
			<a href = "loggedIn.html">Home</a>
            <a href = "upload.html">Upload</a>
			
        </div> 
        <div class = "sidenav">
            <section>
				<br>
                <p class = "sidenav_bullet">Hello, <?php echo $_SESSION["username"];?> !</p> 
				<img src="images/standard_prof_img.png" class = "story_image2">
           
            </section>
            
            
              
     
        </div>
        <form name="form2" action="update_profile.php" method="post">
        <section class = "site_content">
            <div class = "age_section">General presentation</div>
			<div>
			
			<section>

                
                <div class="form_sign_up_row">
                    <div class="form_sign_up_labels">
                        <label style="color:white">First Name</label>
                    </div>
                    <div class="form_sign_up_inputs">
                        <input type="text" value="<?php echo $_SESSION['firstName'] ?>" name = "firstName">
                    </div>
                </div>

				<div class="form_sign_up_row">
                    <div class="form_sign_up_labels">
                        <label style="color:white">Last Name</label>
                    </div>
                    <div class="form_sign_up_inputs">
                        <input type="text" value="<?php echo $_SESSION['lastName'] ?>" name = "lastName">
                    </div>
                 </div>

                <div class="form_sign_up_row">

                    <div class="form_sign_up_labels">
                        <label style="color:white">Nickname</label>
                    </div>

                        <div class="form_sign_up_inputs">
                         <input type="text" value="<?php echo $_SESSION['nickname'] ?>" name = "nickname">
                        </div>
                        <!-- <center><button class="button button2"><a href = "update_profile.php">UPDATE</a></button></center> -->
                    <br><br><br>

                    <center><button class="button button2" type="submit" style = "margin: 0 auto;">UPDATE</button></center>
                    
                                    
                    
                </div>
            
            </section>
			</div>
           
            <div class = "despre">Family members </div>
			<div>
			
			<section>
            

                <div class="form_sign_up_row">
                    <div class="form_sign_up_labels">
                        <label style="color:white">Familie</label>
                    </div>
                    <div class="form_sign_up_inputs">
                        <input type="text" value="<?php echo $_SESSION["family"] ?>" name = "family">
                    </div>
                 </div>
                 <br>

            <center><button class="button button2" type="submit" style = "margin: 0 auto;">UPDATE</button></center>
            
            </section>
			</div>


			


            <div class = "despre">Favorite stories</div>
			<div>    
                <div class = "story">
                        <?php
                           echo '<img src="'.$_SESSION['url'].'" class = "story_image"> ';
                        ?>
                       <!--  <a href = "HanselAndGretel.html" class ="story_title"><?php echo $_SESSION['title'] ?></a>
 -->
                        <?php
                           echo '<a href = "' . substr(substr($_SESSION['url'], 0, -3),7) . 'html" class ="story_title"> ' . $_SESSION['title'] . '</a>';
                        ?>  
                         <!-- TREBUIE NEAPARAT CA POVESTEA .HTML SA AIBE ACELASI NUME CU IMAGINEA .JPG -->


                        <p class = "story_author"> <?php echo $_SESSION["author"] ?> </p>
                        <p class = "story_description"></p>
                </div>
                

            </div>
        </form>

        <form name="form3" action="delete_fav.php" method="post">
            <center><button class="button button2" type="submit" style = "margin: 0 auto;">STERGERE</button></center>
        </form>

        </section>

    </body>

</html>