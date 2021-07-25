// execute the script only when the DOM is loaded

window.addEventListener("DOMContentLoaded", () => {


// get all the inputs in a variable
const profpic = document.getElementsByTagName("input")[1];
const fname = document.getElementsByTagName("input")[2];
const username = document.getElementsByTagName("input")[3];
const email = document.getElementsByTagName("input")[4];
const password = document.getElementsByTagName("input")[5];
const conpass = document.getElementsByTagName("input")[6];
const onSubmit = document.querySelector("#submit");
const passwordError = password.nextElementSibling.nextElementSibling;

var total = 0;         // to store the rating of the pasword
var error = false;     // to store if there were any errors on validation
var unameAvailable = false;      // to check if the uname was available


// function to validate email using REGEX
//  Retrieved from https://www.regular-expressions.info/email.html;
function emailValid(email){
    return 	/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/.test(email);
}

// function to validate the file upload
function validateFile() 
{   

    // only validate it if there was something in the profpic input since it's optional.
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
                break;             // break if it's one of the specified extensions
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
// event on submission of form
onSubmit.addEventListener("click",(ev)=>{


    // get all the span errors of the respective inputs
    const profpicError = profpic.nextElementSibling.nextElementSibling;
    const fnameInput = fname.value;
    const fnameError = fname.nextElementSibling.nextElementSibling;
    const emailInput = email.value;
    const emailError = email.nextElementSibling.nextElementSibling;
    const conpassInput = conpass.value;
    const conpassError = conpass.nextElementSibling.nextElementSibling;
    const usernameInput = username.value;
    const usernameError = username.nextElementSibling.nextElementSibling.nextElementSibling;


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
    if(total!==5){
        error = true;
        passwordError.classList.remove("hidden");
    }
    else
        passwordError.classList.add("hidden");


        // validate confirm pass
     if(password.value !== conpassInput){
        error = true;
         conpassError.classList.remove("hidden");
    }
     else
         conpassError.classList.add("hidden");


    // validate username
    if(username.value == ""){
        error = true;
        usernameError.classList.remove("hidden");
    } else{
        usernameError.classList.add("hidden");
    }

    // check availability of username NOTE: this is on submission of form, username
    //  availability is also checked in this script when focus is lost on the input.

    if(username.value!=="" &&!unameAvailable){
        error = true;
        username.nextElementSibling.nextElementSibling.classList.remove("hidden");
    } else{
        
        username.nextElementSibling.nextElementSibling.classList.add("hidden");
    }

   // validate file
    if(!validateFile()){
        error = true;
        profpicError.classList.remove("hidden");  
    }
    else
        profpicError.classList.add("hidden");
      
    
        

// prevent submission if there are errors
if(error){
    ev.preventDefault();
}
})


// remove the errors on the password field on focus
password.addEventListener("focus", ()=>{
    passwordError.classList.add("hidden");

})

// event on password when focus is lost
password.addEventListener("blur",(ev)=>{

    // display errors if password's rating(total) is not 5(strong)
    if(total !== 5){
        error = true;
        passwordError.classList.remove("hidden");
    }
    else
        passwordError.classList.add("hidden");
        
       
})




 // Password plugin function from https://github.com/caitlindaitch/passwordStrengthMeter, permisson
    // granted under MIT license.

    // event on keyup to check for password strength
password.addEventListener("keyup", function(){

    var passwordArray = password.value.split('');
    
    // storing the rating of the password
    var rating = {
        number: 0,
        lowercase: 0,
        uppercase: 0,
        specialChar: 0,
        length:0,
        total: 0
    }

    // checking if the character matches any of the REGEX patterns and reqs
    var validation = {
        isNumber: function(val){
            var pattern = /^\d+$/;
            return pattern.test(val);
        },
        isLowercase: function(val){
            var pattern = /[a-z]/;
            return pattern.test(val);
        },
        isUppercase: function(val){
            var pattern = /[A-Z]/;
            return pattern.test(val);
        },
        isSpecialChar: function(val){
            var pattern = /^[!@#\$%\^\&*\)\(+=._-]+$/g;
            return pattern.test(val);
        },
        Length: function(val){   // length validation 
            if(val.length < 8)
                return false;
            else
                return true;  
        }
    }

    // loop through all the characters and check if satisfies reqs.
    for (var i=0; i<passwordArray.length; i++){
        if (validation.isNumber(passwordArray[i])){
            rating.number = 1;
        } else if (validation.isLowercase(passwordArray[i])){
            rating.lowercase = 1;
        } else if (validation.isUppercase(passwordArray[i])){
            rating.uppercase = 1;
        } else if (validation.isSpecialChar(passwordArray[i])){
            rating.specialChar = 1;
           
        } 
        
        
        
    }
        // validating the length
     if (validation.Length(password.value)){
        rating.length = 1;

    }

    
    assessTotalScore(rating);   // calling the function to display the strength of the password
});

function assessTotalScore(rating){
    var ratingElement = document.querySelector(".rating");
    total = rating.number + rating.lowercase + rating.uppercase + rating.specialChar + 
    rating.length; // adding up all the ratings to get a total
    

    // if the length is <8, it's weak regardless of input
    if (total === 1 || rating.total === 2 || rating.length !==1){
        ratingElement.innerHTML = "Weak";
        ratingElement.classList.remove("moderatePassword", "strongPassword");
        ratingElement.classList.add("weakPassword");
        
        // if 3/5 or 4/5 checks are met, moderate password
    } else if (total === 3 || rating.total === 4){
        ratingElement.innerHTML = "Moderate";
        ratingElement.classList.remove("weakPassword", "strongPassword");
        ratingElement.classList.add("moderatePassword");

    // if 5/5 checks are met, strong password.
    } else if (total === 5){
        ratingElement.innerHTML = "Strong";
        ratingElement.classList.remove("weakPassword", "moderatePassword");
        ratingElement.classList.add("strongPassword");
    }

    return total;
}


username.addEventListener("focus",(ev)=>{
    
    
})

username.addEventListener("blur",(ev)=>{

    // only check for availability if username was not empty
    if(username.value != ""){
      
    if(username.value.length !== 0)
        
    {   
        // creating an instance of FormData to send as POST data to the php file
        const formData = new FormData();
        formData.append('username', username.value);  
        check(formData)                             // calling the check function to make the fetch
            .then(availableMessage)                 // displaying the message after fetch
            .catch((err) => console.log(err.message));  // catching any errors
    }
}
else{
    

    // removing previous instances of availability messages once a new one is displayed
    const message = document.getElementById("unameCheck");
    if(message == null){}

    else{
        message.nextElementSibling.remove();
        message.remove();

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
    return response.text();  // return the response text. Response is either true or false.
}


// function to display the availability message
function availableMessage (data){

    // create a span element and insert it after the username input
    const msg = document.createElement("span");
    msg.classList.add("unameCheck");
    msg.id = "unameCheck";

    // using insertBefore to utilize it as an insertAfter by referencing the nextElement
    // sibling of the target

    username.parentNode.insertBefore(msg,username.nextElementSibling.nextElementSibling.nextElementSibling.nextElementSibling);
    


    // removing previous instances of the availability messages
    if(msg.nextElementSibling != null){
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
        unameAvailable = false;
        
        

    }
    // else display the available message
    else{
        msg.appendChild(document.createTextNode("Username available"));
        msg.className = "available";
        const icon = document.createElement("i");
        icon.className = "fas fa-check";
        msg.parentNode.insertBefore(icon,msg.nextElementSibling);
        unameAvailable = true;
        error = false;
    }
}
})