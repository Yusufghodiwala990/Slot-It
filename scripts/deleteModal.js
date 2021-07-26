"use strict";

// This block will run when the DOM is loaded (once elements exist).
window.addEventListener("DOMContentLoaded", () => {

    var modal = document.getElementById("ModalWindow");
    var deletion = document.getElementById("delete");
    var no = document.getElementById("no");


// When the user clicks the delete button, open the modal
deletion.addEventListener("click", function() {  //reference: https://www.w3schools.com/howto/howto_css_modals.asp
  modal.style.display = "block";
})

// When the user clicks on the "no" button, close the modal
no.addEventListener("click", function() {
  modal.style.display = "none";
})

// When the user clicks anywhere outside of the modal, close it
modal.addEventListener("click", function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
})
})