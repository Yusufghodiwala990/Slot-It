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

}


// const onSignIn = async googleUser => {
//   const profile = googleUser.getBasicProfile();

//   const body = new FormData();
//   document.cookie = "name=oeschger; SameSite=None; Secure";
//   body.append('name', profile.getName());
//   body.append('email', profile.getEmail());
//   body.append('submit-from-js', true);

//   const res = await fetch('guestlogin.php', {
//     method: 'POST', body
//   }).then(r => r.text());

// }
  

    // fetch('guestlogin.php',{
    //     method: 'post',
    //     body: googleAccountDetails
    // }).then(function (response){
    //   return response.text();
    // }).then(function (text){
    //     console.log(text);
    // }).catch(function (error){
    //   console.log(error);
    // })

    
  
    /*
    console.log('ID: ' + profile.getId()); // Do not send to your backend! Use an ID token instead.
    console.log('Name: ' + profile.getName());
    console.log('Image URL: ' + profile.getImageUrl());
    console.log('Email: ' + profile.getEmail()); // This is null if the 'email' scope is not present.*/
  
