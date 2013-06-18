var islrsharing = true; var islrsocialcounter = true;
// prepare rearrange provider list
function loginRadiusHorizontalRearrangeProviderList(elem){
	var ul = document.getElementById('horsortable');
	if(elem.checked){
		var listItem = document.createElement('li');
		listItem.setAttribute('id', 'lrhorizontal_'+elem.value);
		listItem.setAttribute('title', elem.value);
		listItem.setAttribute('class', 'lrshare_iconsprite32 lrshare_'+elem.value);
		// append hidden field
		var provider = document.createElement('input');
		provider.setAttribute('type', 'hidden');
		provider.setAttribute('name', 'horizontal_rearrange[]');
		provider.setAttribute('value', elem.value);
		listItem.appendChild(provider);
		ul.appendChild(listItem);
	}
	else{
		if(document.getElementById('lrhorizontal_'+elem.value)){
			ul.removeChild(document.getElementById('lrhorizontal_'+elem.value));
		 }
	}
}
// prepare rearrange provider list
function loginRadiusVerticalRearrangeProviderList(elem){
	var ul = document.getElementById('versortable');
	if(elem.checked){
		var listItem = document.createElement('li');
		listItem.setAttribute('id', 'lrvertical_'+elem.value);
		listItem.setAttribute('title', elem.value);
		listItem.setAttribute('class', 'lrshare_iconsprite32 lrshare_'+elem.value);
		// append hidden field
		var provider = document.createElement('input');
		provider.setAttribute('type', 'hidden');
		provider.setAttribute('name', 'vertical_rearrange[]');
		provider.setAttribute('value', elem.value);
		listItem.appendChild(provider);
		ul.appendChild(listItem);
	}else{
		if(document.getElementById('lrvertical_'+elem.value)){
			ul.removeChild(document.getElementById('lrvertical_'+elem.value));
		 }
	}
}
// check provider more then 9 select
function loginRadiusVerticalSharingLimit(elem){
	var provider = $("#sharevprovider").find(":checkbox");
	var checkCount = 0;
	for(var i = 0; i < provider.length; i++){
		if(provider[i].checked){
			// count checked providers
			checkCount++;
			if(checkCount >= 10){
				elem.checked = false;
				$("#loginRadiusVerticalSharingLimit").show('slow');
				setTimeout(function() {
					$("#loginRadiusVerticalSharingLimit").hide('slow');
				}, 5000);
				//document.getElementById('loginRadiusSharingLimit').style.display = 'block';
				return;
			}
		}
	}
}
function loginRadiusHorizontalSharingLimit(elem){
	var provider = $("#sharehprovider").find(":checkbox");
	var checkCount = 0;
	for(var i = 0; i < provider.length; i++){
		if(provider[i].checked){
			// count checked providers
			checkCount++;
			if(checkCount >= 10){
				elem.checked = false;
				$("#loginRadiusHorizontalSharingLimit").show('slow');
				setTimeout(function() {
					$("#loginRadiusHorizontalSharingLimit").hide('slow');
				}, 5000);
				//document.getElementById('loginRadiusSharingLimit').style.display = 'block';
				return;
			}
		}
	}
}
//select counter in checkbox and rearrange
function createhorsharprovider() {
  document.getElementById('lrhorizontalshareprovider').style.display="block";
  document.getElementById('lrhorizontalsharerearange').style.display="block";
  document.getElementById('lrhorizontalcounterprovider').style.display="none";
  document.getElementById('horizontalPageSelect').style.background="#EBEBEB";
  }
//single image in provider
function singleimgsharprovider(){
	document.getElementById('lrhorizontalshareprovider').style.display="none";
	document.getElementById('lrhorizontalsharerearange').style.display="none";
	document.getElementById('lrhorizontalcounterprovider').style.display="none";
	document.getElementById('horizontalPageSelect').style.background="#EBEBEB";
}
//select counter in checkbox
function createhorcounprovider() {
  document.getElementById('lrhorizontalcounterprovider').style.display="block";
  document.getElementById('lrhorizontalsharerearange').style.display="none";
  document.getElementById('lrhorizontalshareprovider').style.display="none";
  document.getElementById('horizontalPageSelect').style.background="#FFFFFF";
  }
function createversharprovider() {
  document.getElementById('lrverticalshareprovider').style.display="block";
  document.getElementById('lrverticalsharerearange').style.display="block";
  document.getElementById('lrverticalcounterprovider').style.display="none";
  document.getElementById('verticalPageSelect').style.background="#EBEBEB";
  }
//select counter in checkbox
function createvercounprovider() {
  document.getElementById('lrverticalcounterprovider').style.display="block";
  document.getElementById('lrverticalsharerearange').style.display="none";
  document.getElementById('lrverticalshareprovider').style.display="none";
  document.getElementById('verticalPageSelect').style.background="#FFFFFF";
  }
function Makevertivisible() {
  document.getElementById('sharevertical').style.display="block";
  document.getElementById('sharehorizontal').style.display="none";
  document.getElementById('arrow').style.cssText = "position:absolute; border-bottom:8px solid #ffffff; border-right:8px solid transparent; border-left:8px solid transparent; margin:-18px 0 0 70px;";
  document.getElementById('mymodal2').style.color = "#00CCFF";
  document.getElementById('mymodal1').style.color = "#000000";
}
function Makehorivisible() {
  document.getElementById('sharehorizontal').style.display="block";
  document.getElementById('sharevertical').style.display="none";
  document.getElementById('arrow').style.cssText = "position:absolute; border-bottom:8px solid #ffffff; border-right:8px solid transparent; border-left:8px solid transparent; margin:-18px 0 0 2px;";
  document.getElementById('mymodal1').style.color = "#00CCFF";
  document.getElementById('mymodal2').style.color = "#000000";
}
function MakeRequest()
{
	document.getElementById("ajaxDiv").innerHTML = '<div id ="loading">Contacting API - please wait ...</div>'; 	
	var connection_url = document.getElementById('connection_url').value;
	var apikey = document.getElementById('apikey').value;
	if (apikey == '') {
	   document.getElementById('ajaxDiv').innerHTML = '<div id="Error">please enter api key</div>';
	   return false;
	}
	var apisecret = document.getElementById('apisecret').value;
	if (apisecret == '') {
	   document.getElementById('ajaxDiv').innerHTML = '<div id="Error">please enter api secret</div>';
	   return false;
	}
	if (document.getElementById('curl').checked) {
	   var api_request = 'curl';
	}
	else {
	   var api_request = 'fsockopen';   
	}
	jQuery.ajax({
		type: 'POST',
		url: connection_url+"administrator/components/com_socialloginandsocialshare/views/socialloginandsocialshare/checkapi.php",
		data: {
			apikey : apikey,
			apisecret : apisecret,
			api_request : api_request
		},
		success: function(data) {
			jQuery("#ajaxDiv").html(data);
		}
	});
}