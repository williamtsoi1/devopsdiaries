function theChampAuthUserFB(){FB.getLoginStatus(theChampFBCheckLoginStatus)}function theChampFBCheckLoginStatus(response){if(response&&response.status=='connected'){theChampLoadingIcon();theChampFBLoginUser()}else{FB.login(theChampFBLoginUser,{scope:theChampFacebookScope})}}function theChampFBLoginUser(){FB.api('/me',function(response){if(!response.id){return}if(theChampFBFeedEnabled){theChampFBFeedPost()}theChampCallAjax(function(){theChampAjaxUserAuth(response,'facebook')})})}