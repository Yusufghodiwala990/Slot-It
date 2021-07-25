// execute the script only when the DOM is loaded


/* ADD VALIDATION FOR THE FETCH REQUEST TO NOT CHECK FOR EMPTY USERNAMES */
window.addEventListener("DOMContentLoaded", () => {

    // get all the inputs
    const fname = document.getElementsByTagName("input")[0];
    const username = document.getElementsByTagName("input")[1];

     // get the current Username of the user. This will be used to validate uname availability
     // i.e if a new username was entered besides the current one.
    const currUsername = username.value; 

    const email = document.getElementsByTagName("input")[2];
    const password = document.getElementsByTagName("input")[3];
    const conpass = document.getElementsByTagName("input")[4];
    const profpic = document.getElementsByTagName("input")[5];
    const profpicError = profpic.nextElementSibling;
 

    // get the two form submission inputs
    const onSubmit = document.getElementById("submit1");
    const profpicUpload = document.getElementById("submit2");
    

    
    const passwordError = password.nextElementSibling.nextElementSibling;
    var total = 0;          // rating of the password
    var error = false;      // to store if there were any errors on validation
    var unameAvailable = false;     // to check if the uname was available
    
    // function to validate email using REGEX
//  Retrieved from https://www.regular-expressions.info/email.html;

    function emailValid(email){
        return 	/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/.test(email);
    }


// function to validate the file upload
function validateFile() 
{   

    // only validate if something was uploaded(optional)
    if(profpic.value!=""){
    
    // allowed extensions
    var allowedExtension = ['jpeg', 'jpg','png'];

    // split the filenanme, return the last element as lowercase
    var fileExtension = profpic.value.split('.').pop().toLowerCase();
    var isValidFile = false;

      // check if the extension is valid by comparing with the allowedExtensions array.
        for(var index in allowedExtension) {

            if(fileExtension === allowedExtension[index]) {
                isValidFile = true; 
                break;
            }
        }

               // get the filesize using the FileList object
        const filesize = profpic.files[0].size / 1024 / 1024; // in MB

         // mark it as invalid if it's greater than 2MB
        if(filesize > 2)
            isValidFile = false;

        return isValidFile;
    }
    else
        return true;
}
    
    // event on submission of form, adding validation
    onSubmit.addEventListener("click",(ev)=>{
        

        // get all the span errors of the respective inputs
        const fnameInput = fname.value;
        const fnameError = fname.nextElementSibling.nextElementSibling;
        const emailInput = email.value;
        const emailError = email.nextElementSibling.nextElementSibling;
        const usernameError = username.nextElementSibling.nextElementSibling;
        const conpassInput = conpass.value;
        const conpassError = conpass.nextElementSibling.nextElementSibling;
       

       
    
        // validate full name
        if(fnameInput == ""){
            error = true;
            fnameError.classList.remove("hidden");
        }
        else
            fnameError.classList.add("hidden");
        
            // validate email
        if(!emailValid(emailInput)){
            error = true;
            emailError.classList.remove("hidden");
        }
        else
            emailError.classList.add("hidden");
    
        // if password rating total is not 5 (strong), it does not meet reqs
        // only check for password strength if the input is not empty
        if (password.value != "") {
            if (total !== 5) {
                error = true;
                passwordError.classList.remove("hidden");
            }
            else
                passwordError.classList.add("hidden");
        }

            // only compare password and confirmpass if the password inp is not empty
         if(password.value != "" && password.value !== conpassInput){
             error = true;
             conpassError.classList.remove("hidden");
        }
        else
            conpassError.classList.add("hidden");

        
        if(username.value == ""){
            usernameError.classList.remove("hidden");
        }
        else
            usernameError.classList.add("hidden");
        
        // check username is available if a new username was entered
        if (username.value !== currUsername) {
            if (username.value.length === 0 || !unameAvailable)
                error = true;
        }

    // prevent submission if there were errors
    if(error){
        ev.preventDefault();
    }
 })

 // event on submission of profile picture
 profpicUpload.addEventListener("click",(ev)=>{
     uploadError = false;

     // validate file
     if(!validateFile()){
        uploadError = true;
        profpicError.classList.remove("hidden");  
    }
    else
        profpicError.classList.add("hidden");
      

// prevent submission if file is invalid
if(uploadError)
    ev.preventDefault();
 })

    
    
password.addEventListener("focus", ()=>{
    passwordError.classList.add("hidden");
    
 })
password.addEventListener("blur",(ev)=>{
    if(password.value !=""){
     if(total !== 5){
        error = true;
        passwordError.classList.remove("hidden");
        }
     else
         passwordError.classList.add("hidden");
            
           
    }
})
    
    
    
    
     // Password plugin function from https://github.com/caitlindaitch/passwordStrengthMeter, permisson
        // granted under MIT license.
    
    password.addEventListener("keyup", function(){

        var passwordArray = password.value.split('');

        var rating = {
            number: 0,
            lowercase: 0,
            uppercase: 0,
            specialChar: 0,
            length: 0,
            total: 0
        }
        // checking if the character matches any of the REGEX patterns and reqs
        var validation = {
            isNumber: function (val) {
                var pattern = /^\d+$/;
                return pattern.test(val);
            },
            isLowercase: function (val) {
                var pattern = /[a-z]/;
                return pattern.test(val);
            },
            isUppercase: function (val) {
                var pattern = /[A-Z]/;
                return pattern.test(val);
            },
            isSpecialChar: function (val) {
                var pattern = /^[!@#\$%\^\&*\)\(+=._-]+$/g;
                return pattern.test(val);
            },
            Length: function (val) {
                if (val.length < 8)
                    return false;
                else
                    return true;
            }
        }
        // loop through all the characters and check if satisfies reqs.
        for (var i = 0; i < passwordArray.length; i++) {
            if (validation.isNumber(passwordArray[i])) {
                rating.number = 1;
            } else if (validation.isLowercase(passwordArray[i])) {
                rating.lowercase = 1;
            } else if (validation.isUppercase(passwordArray[i])) {
                rating.uppercase = 1;
            } else if (validation.isSpecialChar(passwordArray[i])) {
                rating.specialChar = 1;

            }

        }
            // validating the length
        if (validation.Length(password.value)) {
            rating.length = 1;

        }

        assessTotalScore(rating);        // calling the function to display the strength of the password

        
        
});
    
    function assessTotalScore(rating){

        
        var ratingElement = document.querySelector(".rating");
        total = rating.number + rating.lowercase + rating.uppercase + rating.specialChar + 
        rating.length;          // adding up all the ratings to get a total

        // only display strength if the password was no empty
        if (password.value !== "") {

            // if the length is <8, it's weak regardless of input
            if (total === 1 || rating.total === 2 || rating.length !== 1) {
                ratingElement.innerHTML = "Weak";
                ratingElement.classList.remove("moderatePassword", "strongPassword");
                ratingElement.classList.add("weakPassword");

                  // if 3/5 or 4/5 checks are met, moderate password
            } else if (total === 3 || rating.total === 4) {
                ratingElement.innerHTML = "Moderate";
                ratingElement.classList.remove("weakPassword", "strongPassword");
                ratingElement.classList.add("moderatePassword");

                 // if 5/5 checks are met, strong password.
            } else if (total === 5) {
                ratingElement.innerHTML = "Strong";
                ratingElement.classList.remove("weakPassword", "moderatePassword");
                ratingElement.classList.add("strongPassword");
            }

            return total;
        }

        // removing the strength indicator(if available) if password is empty
    else{
        ratingElement.innerHTML="";
        ratingElement.classList.remove("weakPassword");
        ratingElement.classList.remove("moderatePassword");
        ratingElement.classList.remove("strongPassword");
    }
}
    
    
    username.addEventListener("focus",(ev)=>{
         
    })
    

    
    username.addEventListener("blur",(ev)=>{
       
      
        if(username.value !== "" ){
        // check username is available if a new username was entered
        if(username.value !== currUsername){
        if(username.value.length === 0)
            error = true;
        else{

             // creating an instance of FormData to send as POST data to the php file
            const formData = new FormData();
            formData.append('username', username.value);
            check(formData)                      // calling the check function to make the fetch
                .then(availableMessage)            // displaying the message after fetch
                .catch((err) => console.log(err.message));       // catching any errors 
        }
    }
}
    else{
           // removing previous instances of availability messages once a new one is displayed
        const message = document.getElementById("unameCheck");
        if(message == null){}
    
        else{
            message.nextElementSibling.remove();
            message.remove(); // for the icon to be removed too
    
        }
    }
        
})
    // async function to check uname availability
    async function check(formData){
         // post the username to check.php and send to handleErrors to retrieve the response data
        const response = await fetch('check.php', { method: 'POST', body:formData });
        const data = await handleErrors(response);
        return data;
    }
    
    function handleErrors(response) {
        if (!response.ok) {
          throw Error(response.statusText);
        }
        return response.text();      // return the response text. Response is either true or false.
    }
    
    /// function to display the availability message
    function availableMessage (data){
        
          // create a span element and insert it after the username input
        const msg = document.createElement("span");
        msg.classList.add("unameCheck");
        msg.id = "unameCheck";

        // using insertBefore to utilize it as an insertAfter by referencing the nextElement
    // sibling of the target

        username.parentNode.insertBefore(msg,username.nextElementSibling.nextElementSibling.nextElementSibling);
        
        // removing previous instances of the availability messages
        if(msg.nextElementSibling !== null){
            msg.nextElementSibling.remove();
            msg.nextElementSibling.remove();
           
            
           
        }
        
       // if response was false, display the apt message
        if(data === "false"){
            error = true;
            msg.appendChild(document.createTextNode("Username not available")); 
            msg.className = "notAvailable";
            const icon = document.createElement("i");
            icon.className = "fas fa-times";
            msg.parentNode.insertBefore(icon,msg.nextElementSibling);
            
            
    
        }
            // else display the available message
        else{
            msg.appendChild(document.createTextNode("Username available"));
            msg.className = "available";
            const icon = document.createElement("i");
            icon.className = "fas fa-check";
            msg.parentNode.insertBefore(icon,msg.nextElementSibling);
            unameAvailable = true;
        }
    }
    })