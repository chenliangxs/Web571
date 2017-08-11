<?php require_once __DIR__ . '/php-graph-sdk-5.0.0/src/Facebook/autoload.php';$my_access_token='EAAZAaNfCxTeUBAPECymzeMuMVtNhxBZCKUZBhanOIe26PGAaWsmj1kHzlQeQMC6j6KSYYMXPdHKJw3kYBsitNPX6zXXsUlMsImosZBSEG98SRHXriaFqIrkjRgI0toKLXBLBxhHyFJLpc35ftLzdrnCbaLWsOC8ZD';$fb = new Facebook\Facebook(['app_id' => '1788037578182117','app_secret'=>'5c84d6cfed7a61551dbe38cf5b7ffe8a','default_graph_version' => 'v2.8',]);
         
        $fb->setDefaultAccessToken($my_access_token); ?>

<html>
    <head>Facebook Search</head>   
    
    <body>
        
        <div style="background-color: lightgray; width: 1000px; margin: auto">
            <h1 style="text-align: center; border-bottom: 3px solid darkgray"><I>Facebook Search</I></h1>
            <div style="padding-left: 10px; padding-bottom: 20px">
                <Form method="POST">
                    <table>
                        <tr><td>Keyword</td><td><input type=text name="Keyword" placeholder="Keyword" size=30 required></td>
                        <tr><td>Type:</td><td><select name="Type" id="type" onchange="additionalInfo()">
                        <option value="user" selected>Users</option>
                        <option value="page">Pages</option>
                        <option value="event">Events</option>
                        <option value="group">Groups</option>
                        <option value="place">Places</option>
                        </select></td>
                        <tr id="infoForPlace" hidden><td>Location</td><td><input type=text name="Location" size=30 ></td><td>Distance(meters)</td><td><input type=text name="Distance" size=30></td>
                        <tr><td></td><td><input type=submit name="submit_1" value="Search"><input type=reset value="Clear" onclick="clearAll()"></td>
                    </table>
                </Form>
            </div>
        </div>
        <script language="javascript">
            function additionalInfo(){
                if(document.getElementById("type").value==="place"){
                    document.getElementById("infoForPlace").removeAttribute("hidden");
                }
            }
            function clearAll(){
                document.getElementById("infoForPlace").setAttribute("hidden");
            }
            function getDetails(userid){
                document.getElementById(userid).submit();
            }
            function showAlbums(){
                if ((document.getElementByClassName("alb").style.display == 'none')) {
                    document.getElementByClassName("alb").style.display = '';
                    event.preventDefault()
                } else { 
                    document.getElementByClassName("alb").style.display = 'none'; 
                    event.preventDefault()
                }
            }
            
        </script>
        
        <?php if(isset($_POST["submit_1"])){ ?>
        results: 
       <?php
        
        $keyword=$_POST["Keyword"];
        $type=$_POST["Type"];
        if($type=="place"){
            $address=$_POST["Location"];
            $address=str_replace(' ','+', $address);
            $googleURL='https://maps.googleapis.com/maps/api/geocode/json?address='.$address.'&key=AIzaSyBExF91-JJYp_NqI1P1r3pVDIVjAh_UNzE';
            $ggResponse=file_get_contents($googleURL);
            $googleJasonData=json_decode($ggResponse,true);
            $latti=$googleJasonData['results'][0]['geometry']['location']['lat'];
            $longi=$googleJasonData['results'][0]['geometry']['location']['lng'];
            $searchURL='https://graph.facebook.com/v2.8/search?q='.$keyword.'&type='.$type.'&center='.$latti.','.$longi.'&distance='.$_POST["Distance"].'&fields=id,name,picture.width(700).height(700)&access_token='.$my_access_token;
            //echo 'FB:'.$searchURL.'<br>';
        }
        else{
            $searchURL='https://graph.facebook.com/v2.8/search?q='.$keyword.'&type='.$type.'&fields=id,name,picture.width(700).height(700)&access_token='.$my_access_token;
            //echo 'FB:'.$searchURL.'<br>';
        }
        $response=file_get_contents($searchURL);
        $jasonData = json_decode($response,true);
        $length=count($jasonData['data']);
        ?>
        <table id="basicInfo">
            <?php if($length==0): echo '<tr><th>No Records have been Found</th>'; ?>
            <?php else: ?>
            <tr><th>Profile Photo</th><th>Name</th><th>Details</th>
                <?php for($i=0;$i<$length;$i++){
                        $cell=$jasonData['data'][$i];
                        $imgLink=$cell['picture']['data']['url'];
                        $userid=$cell['id']; ?>
            <tr><td><a href="<?php echo $imgLink; ?>" target=_blank><img src="<?php echo $imgLink; ?>" height=30px width=40px></a></td>
                <td><form method="POST" id="<?php echo $cell['id']; ?>"><?php echo $cell['name']; ?><input type="text" name="identfyId" value="<?php echo $cell['id']; ?>" hidden></form></td>
                <td><a href='#' onclick="getDetails('<?php echo $cell['id']; ?>')">detail</a></td>
                <?php } ?>
            <?php endif; ?>
        </table>
        <?php } ?>
        
        <?php if(isset($_POST["identfyId"])){ ?>
           <?php $searchID=$_POST["identfyId"];
                 $detailURL="https://graph.facebook.com/v2.8/".$searchID."?fields=id,name,picture.width(700).height(700),albums.limit(5){name,photos.limit(2){name,picture}},posts.limit(5)&access_token=".$my_access_token;

                $response=file_get_contents($detailURL);
                $jasonData_2 = json_decode($response,true); ?>
                
        <table id="Albums" style="margin: auto">
            <tr><th style="text-align: center"><a href="#" onclick="showAlbums()">Albums</a></th>
                <?php if(array_key_exists("albums",$jasonData_2)): ?>
            <?php $albs=$jasonData_2['albums']['data']; ?>
                <?php for($i=0;$i<5;$i++){
                    if($i<count($albs)){ ?>
                        <tr><td><?php echo $albs[$i]['name']; ?><br>
                            <?php for($j=0;$j<2;$j++){
                               if($j<count($albs[$i]['photos']['data'])){ ?>
                            <?php $imgURL="https://graph.facebook.com/v2.8/".$albs[$i]['photos']['data'][$j]['id']."/picture?access_token=".$my_access_token; ?>   
                            <a href="<?php echo $imgURL; ?>" target=_blank><img src="<?php echo $albs[$i]['photos']['data'][$j]['picture']; ?>" height=80px width=80px></a>
                            <?php } ?>
                            <?php } ?>
                            </td>
                <?php } ?>
                <?php } ?>
                <?php else: ?>
            <tr><td>No albums</td>
                <?php endif; ?>
        </table>
        <table id="posts" style="margin: auto">
            <tr><th style="text-align: center"><a href="#" onclick="showPosts()">Posts</a></th>
            <?php if(array_key_exists("posts",$jasonData_2)): ?>
            <?php $posts=$jasonData_2['posts']['data']; ?>
                <?php for($i=0;$i<5;$i++){
                    if($i<count($posts)){ ?>
                        <tr><td><?php echo $posts[$i]['message']; ?></td>
                <?php } ?>
                <?php } ?>
                <?php else: ?>
            <tr><td>No posts</td>
                <?php endif; ?>
        </table>
        <?php } ?>
    </body>
</html>