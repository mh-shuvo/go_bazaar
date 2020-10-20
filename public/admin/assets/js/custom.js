/*
  CORE JS FOR IIT School Application
  Developed By: Mehedi Hasan
  Position: Software Engineer
*/

function convertToString(input) {
  
	if(input) { 
	  
		 if(typeof input === "string") {

			  return input;
		  }
		
		 return String(input);
	}
	return '';
}


// convert string to words
function toWords(input) {							

 input = convertToString(input);
 
 var regex = /[A-Z\xC0-\xD6\xD8-\xDE]?[a-z\xDF-\xF6\xF8-\xFF]+|[A-Z\xC0-\xD6\xD8-\xDE]+(?![a-z\xDF-\xF6\xF8-\xFF])|\d+/g;
 
 return input.match(regex);

}


// convert the input array to camel case
function toCamelCase(inputArray) {

  let result = "";

  for(let i = 0 , len = inputArray.length; i < len; i++) {

	let currentStr = inputArray[i];
  
	let tempStr = currentStr.toLowerCase();

	if(i != 0) {
  
	  // convert first letter to upper case (the word is in lowercase) 
		tempStr = tempStr.substr(0, 1).toUpperCase() + tempStr.substr(1);

	 }
	
	 result +=tempStr;
	
  }

  return result;
}


// this function call all other functions

function toCamelCaseString(input) {						

let words = toWords(input);

return toCamelCase(words);

}
function isEmpty(str) {
  return (!str || 0 === str.length);
}
function removeSpecialCharecter(str){
  return str.replace("_", " ");
}

function removeWordAfterDelimeter(input,delimeter=' '){
let tempStr = '';

if(typeof input != 'string'){
  input = String (input);
}

 if(input.indexOf(delimeter) == -1){
  tempStr = input;
 }
 else{
	for (var i = 0; i <= input.length; i++) {
	  if(input[i] == delimeter){
		break;
	  } 
	  else{
		tempStr+=input[i];
	  } 
	}
 }

return tempStr;

}

function validation(parameters) {
  let status = 0;
  $.each(parameters, function(key, value) {
	  let el = "#" + key;

	  if ($(el).parent().children('#' + key + '_error')) {
		  $(el).parent().children('#' + key + '_error').html('');
	  }

	  if (value == 'required' && isEmpty($(el).val())) {
		  var html = '';
		  var field = removeSpecialCharecter(key);
		  field = removeWordAfterDelimeter(field);
		  field = field.charAt(0).toUpperCase() + field.slice(1);
		  html = '<p class="field_error" style="color:red;" id="' + key + '_error">' + field + ' field is required</p>';
		  $(el).parent().append(html);
		  status = 1;
	  }

  });
  return status;
}

function readURL(input,el) {
	if (input.files && input.files[0]) {
		var reader = new FileReader();
		reader.onload = function(e) {
		$(el).attr('src', e.target.result);
		}

		reader.readAsDataURL(input.files[0]);
	}
}
$(document).on('keyup','input',function(){
	if($(".field_error").length > 0){
		$(".field_error").css('display','none');
	}
});
