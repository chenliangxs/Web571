<?php require_once __DIR__ . '/php-graph-sdk-5.0.0/src/Facebook/autoload.php';$my_access_token='EAAZAaNfCxTeUBABf9zWrez30BZAeaJbAYgdCwQzVpnmaa2AinTA1xjNHmVjZC6OxwncRCwKdiyBizXGvFk7wMayvBA7Pnh9x130JomGlSg8YUHyQIbZBgIt8ft0TR2R24TvaGKcDv1jLsNraPbJRd5FEsQ7IGpgZD';$fb = new Facebook\Facebook(['app_id' => '1788037578182117','app_secret'=>'5c84d6cfed7a61551dbe38cf5b7ffe8a','default_graph_version' => 'v2.8',]);$fb->setDefaultAccessToken($my_access_token);
  
if(isset($_GET["keyword"])){
    $keyword=$_GET["keyword"];
    $keyword=rawurlencode($keyword);
$latitude=$_GET["latitude"];
$longitude=$_GET["longitude"];
$types=array("user","page","event","place","group");
echo "{";
foreach($types as $type){
   if($type=="place"){
       $searchURLUser='https://graph.facebook.com/v2.8/search?limit=10&q='.$keyword.'&type='.$type.'&center='.$latitude.','.$longitude.'&distance='.'1000'.'&fields=id,name,picture.width(700).height(700)&access_token='.$my_access_token;
   }
    else{
        $searchURLUser='https://graph.facebook.com/v2.8/search?limit=10&q='.$keyword.'&type='.$type.'&fields=id,name,picture.width(700).height(700)&access_token='.$my_access_token;
    }
    $response=file_get_contents($searchURLUser);
    echo "\"".$type."\"".":".$response;
    if($type!="group"){
        echo ",";
    }
}
echo "}";
}

else{
    $detailID=$_GET["detail"];
    $searchDetailURL="https://graph.facebook.com/v2.8/".$detailID."?fields=id,name,picture.width(700).height(700),albums.limit(5){name,photos.limit(2){name,picture}},posts.limit(5)&access_token=".$my_access_token;
    $response=file_get_contents($searchDetailURL);
    echo $response;

}


?>