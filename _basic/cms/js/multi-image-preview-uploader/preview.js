var phpArray = [];

//Using Ajax call php file
$("#upload_images").click(function(){
	var phpString = JSON.stringify(phpArray);
	document.getElementById("values").value = phpString;
});

//Call functions on change
var inputLocalFont = document.getElementById("files");
if (inputLocalFont.addEventListener) // W3C DOM
{
	inputLocalFont.addEventListener("change",previewImages,false);
}
else if (inputLocalFont.attachEvent) // IE DOM
{
	$("#files").change(function(){
		var IEvalue = (document.getElementById('files').value.replace(/^.*\\/, ''));
		phpArray.push(IEvalue);
	});
}

//Call removePreviewImages function
$('#files').click(function(){
  	removePreviewImages();
});

//Remove old images to display new ones
function removePreviewImages(){
	if( $('#preview-area').is(':empty') ) {
		$("#gallery-errors").empty();
		//Does nothing
	}else{
		$("#preview-area").empty();
		$("#gallery-errors").empty();
		phpArray.length = 0;
	}
}
//Preview the images
function previewImages(){
var fileList = this.files;
var anyWindow = window.URL || window.webkitURL;
var removeCount = 0;
var removedReason = '';
var maxReached = 0;
var count = 0;

	for(var i = 0; i < fileList.length; i++){
	  var objectUrl = anyWindow.createObjectURL(fileList[i]);
	  var name = fileList[i].name;
	  var size = (fileList[i].size/1024).toFixed(2);
	  var type = fileList[i].type.substring(6);

		//CHECK THAT ONLY 15 IMAGES ARE UPLOADED
		if(i <= 15){

			//CHECK THAT IMAGE TYPE IS ACCEPTED
			if(type == 'jpeg' || type == 'JPEG' || type == 'jpg' || type == 'JPG' || type == 'png' || type == 'PNG'){

				//IMAGES THAT ARE 4 MB OR LESS
				if(size <= 4000){

					phpArray.push(fileList[i].name);

					$('#preview-area').append('<div class="uploader_image_shade" id="img'+ i + '" ><div class="preview-images" style="background-image: url(' + objectUrl + ');"></div><a href="#" title="Remove Image" class="remove_image" onclick="RemoveMe('+i+',&apos;'+name+'&apos;);">Remove</a><div class="uploader_image_properties"><div class="module-form-titles">Image Title:</div><input type="text" name="imageTitle_'+ count +'" value="" maxlength="150"/><i>The image title has a maximum of 150 characters.</i></div><div class="clear"></div></div>');

					window.URL.revokeObjectURL(fileList[i]);
					count++;
				}else{

					//SET IMAGE SIZE
					if(size >= 1000)
					{
					  size = size.substring(0,1)+' MB';
					}
					else
					{
					  size = size+' KB' ;
					}

					removeCount = removeCount + 1;
					removedReason += '(<b>'+ size +'</b>) '+ name + ' <b> size is too big</b>.<br />';

					phpArray.push('');
				}

			}else{
				removeCount = removeCount + 1;
				removedReason += '(<b>'+ type +'</b>) '+ name + ' is in the <b>wrong format</b>. We only allow .JPEG, .jpeg, .JPG, .jpg, .PNG or .png<br />';

				phpArray.push('');
			}
		}else{
			maxReached = 1;
		}
	}

	//SET ERROR MESSAGE
	//SELECTED MORE THAN 20 IMAGES AND IMAGES FORMAT OR SIZE WAS NOT ACCPTED
	if(maxReached == 1 && removeCount != 0){
		$('#gallery-errors').append('<div class="gallery-images-errors"><b>You reached the max amount of images you can upload at once.</b><br /><br /><b>'+ removeCount +' Images have been removed:</b><br /><br />'+ removedReason +'</div>');
	}
	//IMAGES FORMAT OR SIZE WAS NOT ACCPTED
	else if(maxReached == 0 && removeCount != 0){
		$('#gallery-errors').append('<div class="gallery-images-errors"><b>'+ removeCount +' Images have been removed:</b><br /><br />'+ removedReason +'</div>');
	}
	//SELECTED MORE THAN 20 IMAGES
	else if(maxReached == 1 && removeCount == 0){
		$('#gallery-errors').append('<div class="gallery-images-errors"><b>You reached the max amount of images you can upload at once.</b></div>');
	}
}

//Removes specific image
function RemoveMe($number, $name){
	$("#img"+ $number).remove();

	var index = phpArray.indexOf($name);
	phpArray.splice(index, 1);

	//Reload page if array empty
	if(phpArray.length == 0){
		location.reload();
	}
}
