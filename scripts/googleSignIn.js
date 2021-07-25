
// function header by Google API
function onSignIn(googleUser) {
  const profile = googleUser.getBasicProfile(); // get the profile of the google account


  // create an instance of FormData object
  const formData = new FormData();

  // append the info
   formData.append('name', profile.getName());
   formData.append('email', profile.getEmail());
   formData.append("submit-from-js", 1);


   // fetch call to POST data to guestlogin.php for processing, it will respond by a url
   // which will be handled in this script by redirecting to that url.
   
   fetch('guestlogin.php', { method: 'POST', body:formData, redirect:'follow' })
     .then(response =>{
       if(response.redirected){
         window.location.href = response.url;

       }
       
     })
  }
