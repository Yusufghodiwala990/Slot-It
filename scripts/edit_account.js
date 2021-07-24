// execute the script only when the DOM is loaded


/* ADD VALIDATION FOR THE FETCH REQUEST TO NOT CHECK FOR EMPTY USERNAMES */
window.addEventListener("DOMContentLoaded", () => {

    
    const fname = document.getElementsByTagName("input")[0];
    const username = document.getElementsByTagName("input")[1];
    const currUsername = username.value;

    const email = document.getElementsByTagName("input")[2];
    const password = document.getElementsByTagName("input")[3];
    const conpass = document.getElementsByTagName("input")[4];
    const profpic = document.getElementsByTagName("input")[5];
    const profpicError = profpic.nextElementSibling;
 

    
    const onSubmit = document.getElementById("submit1");
    const profpicUpload = document.getElementById("submit2");
    

    
    const passwordError = password.nextElementSibling.nextElementSibling;
    var total = 0;
    var error = false;
    var unameAvailable = false;
    
    function emailValid(email){
        return 	/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/.test(email);
    }

    
function validateFile() 
{   
    
    if(profpic.value!=""){
        
    var allowedExtension = ['jpeg', 'jpg','png'];
    var fileExtension = profpic.value.split('.').pop().toLowerCase();
    var isValidFile = false;

        for(var index in allowedExtension) {

            if(fileExtension === allowedExtension[index]) {
                isValidFile = true; 
                break;
            }
        }

        const filesize = profpic.files[0].size / 1024 / 1024; // in MB
        if(filesize > 2)
            isValidFile = false;

        return isValidFile;
    }
    else
        return true;
}
    
    // event on submission of form, adding validation
    onSubmit.addEventListener("click",(ev)=>{
    
        const fnameInput = fname.value;
        const fnameError = fname.nextElementSibling.nextElementSibling;
        const emailInput = email.value;
        const emailError = email.nextElementSibling.nextElementSibling;
        const usernameError = username.nextElementSibling.nextElementSibling;
        const conpassInput = conpass.value;
        const conpassError = conpass.nextElementSibling.nextElementSibling;
       

       
    
    
        if(fnameInput == ""){
            error = true;
            fnameError.classList.remove("hidden");
        }
        else
            fnameError.classList.add("hidden");
            
        if(!emailValid(emailInput)){
            error = true;
            emailError.classList.remove("hidden");
        }
        else
            emailError.classList.add("hidden");
    
        
        if (password.value != "") {
           

            if (total !== 5) {
                error = true;
                passwordError.classList.remove("hidden");
            }
            else
                passwordError.classList.add("hidden");
        }

        
            if(password.value != "" && password.value !== conpassInput){
                error = true;
                conpassError.classList.remove("hidden");
            }
            else
                conpassError.classList.add("hidden");
            
       console.log(error);
        
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

        
     
        
            
    
    
    
        
    
    if(error){
        ev.preventDefault();
    }
 })

 profpicUpload.addEventListener("click",(ev)=>{
     uploadError = false;
     console.log(profpicError);

    
     if(!validateFile()){
        uploadError = true;
        profpicError.classList.remove("hidden");  
    }
    else
        profpicError.classList.add("hidden");
      

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
            
           
    } else{

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
        if (validation.Length(password.value)) {
            rating.length = 1;

        }

        assessTotalScore(rating);

        
        
});
    
    function assessTotalScore(rating){

        
        var ratingElement = document.querySelector(".rating");
        total = rating.number + rating.lowercase + rating.uppercase + rating.specialChar + 
        rating.length;

        if (password.value !== "") {
            if (total === 1 || rating.total === 2 || rating.length !== 1) {
                ratingElement.innerHTML = "Weak";
                ratingElement.classList.remove("moderatePassword", "strongPassword");
                ratingElement.classList.add("weakPassword");
            } else if (total === 3 || rating.total === 4) {
                ratingElement.innerHTML = "Moderate";
                ratingElement.classList.remove("weakPassword", "strongPassword");
                ratingElement.classList.add("moderatePassword");
            } else if (total === 5) {
                ratingElement.innerHTML = "Strong";
                ratingElement.classList.remove("weakPassword", "moderatePassword");
                ratingElement.classList.add("strongPassword");
            }

            return total;
        }
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
       
        console.log("hello");
        if(username.value !== "" ){
        // check username is available if a new username was entered
        if(username.value !== currUsername){
        if(username.value.length === 0)
            error = true;
        else{
            const formData = new FormData();
            formData.append('username', username.value);
            check(formData)
                .then(availableMessage)
                .catch((err) => console.log(err.message));
        }
    }
}
    else{
        
        const message = document.getElementById("unameCheck");
        console.log(message);
        if(message == null){}
    
        else{
            message.nextElementSibling.remove();
            message.remove();
    
        }
    }
        
})
    
    async function check(formData){
        const response = await fetch('check.php', { method: 'POST', body:formData });
        const data = await handleErrors(response);
        return data;
    }
    
    function handleErrors(response) {
        if (!response.ok) {
          throw Error(response.statusText);
        }
        return response.text();
    }
    
    function availableMessage (data){
        
        const label = document.getElementsByTagName("label")[2];
        const msg = document.createElement("span");
        msg.classList.add("unameCheck");
        msg.id = "unameCheck";
        username.parentNode.insertBefore(msg,username.nextElementSibling.nextElementSibling.nextElementSibling);
    
        if(msg.nextElementSibling !== null){
            msg.nextElementSibling.remove();
            msg.nextElementSibling.remove();
           
            
           
        }
        
       
        if(data === "false"){
            error = true;
            msg.appendChild(document.createTextNode("Username not available")); 
            msg.className = "notAvailable";
            const icon = document.createElement("i");
            icon.className = "fas fa-times";
            msg.parentNode.insertBefore(icon,msg.nextElementSibling);
            
            
    
        }
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