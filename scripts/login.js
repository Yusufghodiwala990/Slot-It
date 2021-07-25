// get all the inputs and span errors declared after the inputs
const username = document.getElementsByTagName("input")[0];
const usernameError = username.nextElementSibling.nextElementSibling;
const password = document.getElementsByTagName("input")[1];
const passwordError = password.nextElementSibling.nextElementSibling;
const onSubmit = document.getElementById("submit");


// on Form submission
onSubmit.addEventListener("click",(ev)=>{
    let error = false

    // validate username
    if(username.value == ""){
        error = true;
        usernameError.classList.remove("hidden");
    }
    else
        usernameError.classList.add("hidden");


    // validate password
    if(password.value == ""){
        error = true;
        passwordError.classList.remove("hidden");
    }
    else
        passwordError.classList.add("hidden");
    

// prevent submission if there are errors
if(error){
    ev.preventDefault();
}
})

