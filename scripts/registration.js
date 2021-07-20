window.addEventListener("DOMContentLoaded", () => {
const username = document.getElementsByTagName("input")[3];

username.addEventListener("focus",(ev)=>{
    console.log("focus achieved");
    
})

username.addEventListener("blur",(ev)=>{
    console.log(username.value);
    const formData = new FormData();
    formData.append('username',username.value);
    check(formData)
    .then(availableMessage)
    .catch((err) => console.log(err.message));
    

    
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
    console.log(label);
    const msg = document.createElement("span");
    username.parentNode.insertBefore(msg,username.nextElementSibling.nextElementSibling.nextElementSibling);
   
    if(msg.nextElementSibling.className !=="error hidden"){
        msg.nextElementSibling.nextElementSibling.remove();
        msg.nextElementSibling.remove();
       
    }
    
   
    if(data === "false"){
        msg.appendChild(document.createTextNode("Username not available")); 
        msg.className = "notAvailable";
        const icon = document.createElement("i");
        console.log(icon);
        icon.className = "fas fa-times";
        msg.parentNode.insertBefore(icon,msg.nextElementSibling);
        

    }
    else{
        msg.appendChild(document.createTextNode("Username available"));
        msg.className = "available";
        const icon = document.createElement("i");
        console.log(icon);
        icon.className = "fas fa-check";
         
        msg.parentNode.insertBefore(icon,msg.nextElementSibling);
    }
}
})