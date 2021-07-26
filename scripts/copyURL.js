"use strict";

// This block will run when the DOM is loaded (once elements exist)
window.addEventListener("DOMContentLoaded", () => {
    var copyText = document.getElementsByClassName("button");
  
    for (let i = 0; i < copyText.length; i++) {
    copyText[i].addEventListener("click",function(){
        var text = "https://loki.trentu.ca/~yusufghodiwala/3420/project/scripts" + copyText[i].value;
        var copy = document.createElement("input");  //reference: https://orclqa.com/copy-url-clipboard/
        document.body.appendChild(copy); //inserting a new node 
        copy.value = text; 
        copy.select(); //selecting the URL
        document.execCommand("copy"); //The actual copy function
        document.body.removeChild(copy); //removing the input element

  
    //alerting the user when the URL is copied
    var alert = document.createElement("p");
    alert.appendChild(document.createTextNode("Copied"));
    copyText[i].insertBefore(alert,copyText[i].nextElementSibling);
    setTimeout(function(){ alert.remove() }, 2000);
    })
}
  })