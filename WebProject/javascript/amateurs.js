/*
 * Pledge Fom Validation
 * Handles the validation of form elements on the Join Form page.
 * 
 * Script: formValidate.js
 * Author: Alex Richardson
 * Version: 1.0
 * Date Created: 4.18.2018
 * Last Updated: 4.18.2018
*/

/*
 * Handles the submit event of the survey form
 *
 * param e  A reference to the event object
 * return   True if no validation errors; False if the form has
 *          validation errors
 */
function validate(e)
{
	hideErrors();

	if(formHasErrors()){
		e.preventDefault();

		return false;
	} 
	return true;
}

/*
 * Does all the error checking for the form.
 *
 * return   True if an error was found; False if no errors were found
 */
function formHasErrors()
{
	var errorFlag = false;

    var name = document.getElementById("firstname");

    if(!formFieldHasInput(name)){
        document.getElementById("name_error").style.display = "block";
        
        if(!errorFlag){
            name.focus();
        }
        errorFlag = true;
    }

    var phone = document.getElementById("phone");

    if(!formFieldHasInput(phone)){
        document.getElementById("phone_error").style.display = "block";
        
        if(!errorFlag){
            phone.focus();
        }
        errorFlag = true

    }
    else{
        
        var regexPhone = /^\d{10}$/;
        
        if(!regexPhone.test(phone.value)){
            document.getElementById("phone_invalid").style.display = "block";
            if(!errorFlag){
                phone.select();
                phone.focus();
            }
            errorFlag = true
        }
    }
     
     var email = document.getElementById("email");

	if(!formFieldHasInput(email)){
	  	document.getElementById("email_error").style.display = "block";

	  	if(!errorFlag){
	  		email.focus();
	  	}
	  	errorFlag = true;
    }
	else{

	  var regexMail = new RegExp(/^\w+@[a-zA-Z_]+?\.[a-zA-Z]{2,3}$/); 

	  if(!regexMail.test(email.value)){
	  	document.getElementById("email_invalid").style.display = "block";

	 	if(!errorFlag){
	 		email.focus();
	 		email.select();
	  	}
	 	errorFlag = true;
	  }
	}

	 var financeCheck = document.getElementById("box1").checked;
	 var fitnessCheck = document.getElementById("box2").checked;
     var skillCheck = document.getElementById("box3").checked;
     var otherCheck = document.getElementById("box4").checked;
     var comments = document.getElementById("comments");

    if(!financeCheck && !fitnessCheck && !skillCheck && !otherCheck){
            document.getElementById("check_error").style.display = "block";

		if(!errorFlag){
			document.getElementById("box1").focus();
		}
		errorFlag = true;
    }
    
    if(otherCheck && !formFieldHasInput(comments)){
        document.getElementById("comments_error").style.display = "block";
        if(!errorFlag){
			comments.focus();
		}
		errorFlag = true;
    }
 	return errorFlag;
}

/*
 * Hides all of the error elements.
 */
function hideErrors()
{
    var errorsR = document.getElementsByClassName("error");
	for(var i = 0; i < errorsR.length; i++){
        errorsR[i].style.display = "none";
    }
    
    var errorsI = document.getElementsByClassName("invalid");
	for(var i = 0; i < errorsI.length; i++){
        errorsI[i].style.display = "none";
    }   
}

/*
 * Handles the load event of the document.
 */
function load()
{
     hideErrors();
     document.getElementById("firstname").focus();
     var submit = document.getElementById("btnSubmit");
     submit.addEventListener("click", validate);
}

/*
 * Checks if a text input has a value contained inside of it
 * 
 * param   input A text input element object
 * return  True if the field contains input; False if nothing entered
 */
function formFieldHasInput(input)
{

	if(input.value == null || trim(input.value) == ""){
		return false;
	}
	return true;
}

/*
 * Removes white space from a string value.
 *
 * return  A string with leading and trailing white-space removed.
 */
function trim(str) 
{
	// Uses a regex to remove spaces from a string.
	return str.replace(/^\s+|\s+$/g,"");
}

// Add document load event listener
document.addEventListener("DOMContentLoaded", load, false);