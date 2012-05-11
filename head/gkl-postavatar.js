function chPostAvatar() {
	var pAvaImg  = document.getElementById('postuserpic').value;
	if (pAvaImg == 'no_avatar.png')
		document.getElementById('postavatar').src = gkl_postavatar_text.avatar_img + '/no_avatar.png';
	else
		document.getElementById('postavatar').src = gkl_postavatar_text.avatar_url + pAvaImg;
	
	return true;
}

function nextPostAvatar() {	
	if (document.getElementById('postuserpic').selectedIndex < document.getElementById('postuserpic').length) {
		document.getElementById('postuserpic').selectedIndex++;
	}
	if ( document.getElementById('postuserpic').options[document.getElementById('postuserpic').selectedIndex].text == gkl_postavatar_text.noavatar_msg )
		document.getElementById('postavatar').src = gkl_postavatar_text.avatar_img + '/no_avatar.png';
	else
		document.getElementById('postavatar').src = gkl_postavatar_text.avatar_url + document.getElementById('postuserpic').options[document.getElementById('postuserpic').selectedIndex].text;

}

function prevPostAvatar() {
	if (document.getElementById('postuserpic').selectedIndex > 1) {
		document.getElementById('postuserpic').selectedIndex--;
	}
	if ( document.getElementById('postuserpic').options[document.getElementById('postuserpic').selectedIndex].text == 'No Avatar selected' )
		document.getElementById('postavatar').src = gkl_postavatar_text.avatar_img + '/no_avatar.png';
	else
		document.getElementById('postavatar').src = gkl_postavatar_text.avatar_url + document.getElementById('postuserpic').options[document.getElementById('postuserpic').selectedIndex].text;	
}