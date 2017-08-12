var latitude;
var longitude;
function success(position){
    latitude=position.coords.latitude;
    longitude=position.coords.longitude;
            
};            
function error(){               
    alert("Cannot get location");         
};         
navigator.geolocation.getCurrentPosition(success,error);      
            
var app=angular.module('myApp',[]);           
app.controller('myCtrl',function($scope, $http){             
    $scope.Phid=true;               
    $scope.Shid=false;                
    $scope.dataArray=[];            
    $scope.submitKey=function(){                
        var keyData={"keyword": $scope.keyword, "latitude": latitude, "longitude": longitude};             
        var config={params:keyData};              
        $http.get("index.php", config).then(function(response){
                       
            $scope.usrs=response.data.user.data;           
            $scope.usrPaging=response.data.user.paging;
                                 
            $scope.pages=response.data.page.data;           
            $scope.pagesPaging=response.data.page.paging;           
                       
            $scope.events=response.data.event.data;          
            $scope.eventsPaging=response.data.event.paging;
                                   
            $scope.places=response.data.place.data;          
            $scope.placesPaging=response.data.place.paging;
                                            
            $scope.groups=response.data.group.data;                
            $scope.groupsPaging=response.data.group.paging;               
        });         
    };
                             
    $scope.submitUsrPaging=function(url){            
        if(url!=null){
            $http.get(url).then(function(response){
                $scope.usrs=response.data.data;                      
                $scope.usrPaging=response.data.paging;           
            });              
        }          
    };
                
    $scope.submitPagePaging=function(url){                   
        if(url!=null){           
            $http.get(url).then(function(response){
                $scope.pages=response.data.data;                      
                $scope.pagesPaging=response.data.paging;               
            });               
        }        
    }
               
    $scope.submitEventPaging=function(url){                   
        if(url!=null){                      
            $http.get(url).then(function(response){                         
                $scope.events=response.data.data;                        
                $scope.eventsPaging=response.data.paging;                
            });         
        }         
    }         
    $scope.submitPlacePaging=function(url){           
        if(url!=null){                   
            $http.get(url).then(function(response){
                $scope.places=response.data.data;                  
                $scope.placesPaging=response.data.paging;         
            });          
      }        
    }
               
    $scope.submitGroupPaging=function(url){             
        if(url!=null){          
            $http.get(url).then(function(response){                   
                $scope.groups=response.data.data;                  
                $scope.groupsPaging=response.data.paging;              
            });                   
        }          
    }
               
    $scope.submitDetail=function(detailID){             
        $scope.Phid=false;
        $scope.Shid=true;                
        $scope.info=[];                 
        $scope.albums=[];                
        $scope.posts=[];                 
        var detailData={"detail": detailID};                  
        var config={params:detailData};                
        $http.get("index.php", config).then(function(response){           
            $scope.info=response.data;                  
            $scope.albums=response.data.albums.data;                   
            $scope.posts=response.data.posts.data;                 
        });               
    };
                            
    $scope.addFavorite=function(id){                  
        var detailData={"detail": id};                
        var config={params:detailData};                  
        var getid=id;         
        $http.get("index.php", config).then(function(response){         
            $scope.responseData=response.data;                       
            $scope.dataArray.push($scope.responseData);                  
        });                                
    };                             
    $scope.backBut=function(){             
        $scope.Phid=true;               
        $scope.Shid=false;               
    }            
});         
           
$(document).ready(function(){              
    $(".nav-tabs a").click(function(){                  
        $(this).tab('show');                
    });         
});