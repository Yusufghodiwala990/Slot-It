function onSignIn(googleUser) {
  const profile = googleUser.getBasicProfile();

  const formData = new FormData();
   formData.append('name', profile.getName());
   formData.append('email', profile.getEmail());
   formData.append("submit-from-js", 1);

   fetch('guestlogin.php', { method: 'POST', body:formData, redirect:'follow' })
     .then(response =>{
       if(response.redirected){
         window.location.href = response.url;

       }
       
     })


  
    /*
    console.log('ID: ' + profile.getId()); // Do not send to your backend! Use an ID token instead.
    console.log('Name: ' + profile.getName());
    console.log('Image URL: ' + profile.getImageUrl());
    console.log('Email: ' + profile.getEmail()); // This is null if the 'email' scope is not present.*/
  }
