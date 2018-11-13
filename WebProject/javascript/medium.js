/*
 * 
 * 
 * 
*/

function load()
{
	
	

    sort.addEventListener("change", function(){
		sortValue = document.getElementById("sort").value;

		window.location.replace(header = "index.php?sort=" + sort.value);

		document.getElementById("sort").value = sortValue;
	});

	document.getElementById("all").addEventListener("click", function(){

		var result="<?php setCounter();?>";
			
	});

	
	
}



// Add document load event listener
document.addEventListener("DOMContentLoaded", load, false);