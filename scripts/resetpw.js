window.addEventListener("DOMContentLoaded", () => {

// function to validate email using REGEX
//  Retrieved from https://www.regular-expressions.info/email.html;

function emailValid(email){
    return 	/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/.test(email);
}

const onSubmit = document.getElementById("submit");
onSubmit.addEventListener("click",(ev)=>{
    let error = false;
    const email = document.getElementsByTagName("input")[0];
    const emailError = email.nextElementSibling.nextElementSibling;

    // validate email
    if(!emailValid(email.value)){
        error = true;
        emailError.classList.remove("hidden");
    }
    else
        emailError.classList.add("hidden");



if(error)
    ev.preventDefault();
})

})