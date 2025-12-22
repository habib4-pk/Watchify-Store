// Password validation for registration page
var myInput = document.getElementById("password");

var length = document.getElementById("length");
var char = document.getElementById("char");
var message = document.getElementById("message");

// When the user clicks on the password field, show the message box
myInput.onfocus = function() {
  message.style.display = "block";
}

// When the user clicks outside of the password field, hide the message box
myInput.onblur = function() {
  message.style.display = "none";
}

// When the user starts to type something inside the password field
myInput.onkeyup = function() {
  // Validate lowercase letters

  
  // Validate length
  if(myInput.value.length >= 8) {
    length.classList.remove("invalid");
    length.classList.add("valid");
  } else {
    length.classList.remove("valid");
    length.classList.add("invalid");
  }

  // Validate special characters
  var specialChars = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/g;
  if(myInput.value.match(specialChars)) {  
    char.classList.remove("invalid");
    char.classList.add("valid");
  } else {
    char.classList.remove("valid");
    char.classList.add("invalid");
  }
}