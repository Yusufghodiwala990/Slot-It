const username = document.getElementsByTagName("input")[0];
const usernameError = username.nextElementSibling.nextElementSibling;
const password = document.getElementsByTagName("input")[1];
const passwordError = password.nextElementSibling.nextElementSibling;
const onSubmit = document.getElementById("submit");

onSubmit.addEventListener("click",(ev)=>{
    let error = false
    if(username.value == ""){
        error = true;
        usernameError.classList.remove("hidden");
    }
    else
        usernameError.classList.add("hidden");

    if(password.value == ""){
        error = true;
        passwordError.classList.remove("hidden");
    }
    else
        passwordError.classList.add("hidden");
    
if(error){
    ev.preventDefault();
}
})

