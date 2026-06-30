




function autocompletcaisse() {
	var keyword = $('#country_caisse').val();
	$.ajax({
		url: 'ajax_refresh_caisse.php',
		type: 'POST',
		data: {keyword:keyword},
		success:function(data){
			$('#country_list_caisse').show();
			$('#country_list_caisse').html(data);
			if(document.getElementById('country_caisse').value=="")
			{
				$('#country_list_caisse').hide();
			

			}

		}
	});
	
}


// set_item : this function will be executed when we select an item
function set_item_caisse(item) {

	// change input value
	$('#country_caisse').val(item);

	// hide proposition list
	$('#country_list_caisse').hide();
}






function autocompletcaisseste() {
	var keyword = $('#country_caisse').val();
	$.ajax({
		url: 'ajax_refresh_caisseste.php',
		type: 'POST',
		data: {keyword:keyword},
		success:function(data){
			$('#country_list_caisse').show();
			$('#country_list_caisse').html(data);
			if(document.getElementById('country_caisse').value=="")
			{
				$('#country_list_caisse').hide();
			

			}

		}
	});
	
}


// set_item : this function will be executed when we select an item
function set_item_caisseste(item) {

	// change input value
	$('#country_caisse').val(item);

	// hide proposition list
	$('#country_list_caisse').hide();
}






///////////////////////////////////////////////////////////////////////////////////////// CARNET

function autocomplet_add() {
	var keyword = $('#country_id1').val();
	$.ajax({
		url: 'ajax_refresh_add.php',
		type: 'POST',
		data: {keyword:keyword},
		success:function(data){
			$('#country_list_id1').show();
			$('#country_list_id1').html(data);
			if(document.getElementById('country_id1').value=="")
			{
				$('#country_list_id1').hide();
			

			}

		}
	});
	
}


// set_item : this function will be executed when we select an item
function set_item_add(item) {

	// change input value
	$('#country_id1').val(item);

	// hide proposition list
	$('#country_list_id1').hide();
}


///////////////////////////////////////////////////////////////////////////////////////// CARNET

function autocomplet_adde() {
	var keyword = $('#country_id1e').val();
	$.ajax({
		url: 'ajax_refresh_adde.php',
		type: 'POST',
		data: {keyword:keyword},
		success:function(data){
			$('#country_list_id1e').show();
			$('#country_list_id1e').html(data);
			if(document.getElementById('country_id1e').value=="")
			{
				$('#country_list_id1e').hide();
			

			}

		}
	});
	
}


// set_item : this function will be executed when we select an item
function set_item_adde(item) {

	// change input value
	$('#country_id1e').val(item);

	// hide proposition list
	$('#country_list_id1e').hide();
}











function autocomplet1() {
	var keyword = $('#country_id1').val();
	$.ajax({
		url: 'ajax_refresh1.php',
		type: 'POST',
		data: {keyword:keyword},
		success:function(data){
			$('#country_list_id1').show();
			$('#country_list_id1').html(data);
			if(document.getElementById('country_id1').value=="")
			{
				$('#country_list_id1').hide();
			

			}

		}
	});
	
}

// set_item : this function will be executed when we select an item
function set_item1(item) {

	// change input value
	$('#country_id1').val(item);

	// hide proposition list
	$('#country_list_id1').hide();
}














function autocomplet2() {
	var keyword = $('#country_id2').val();
	$.ajax({
		url: 'ajax_refresh2.php',
		type: 'POST',
		data: {keyword:keyword},
		success:function(data){
			$('#country_list_id2').show();
			$('#country_list_id2').html(data);
			if(document.getElementById('country_id2').value=="")
			{
				$('#country_list_id2').hide();
			

			}

		}
	});
	
}

// set_item : this function will be executed when we select an item
function set_item2(item) {

	// change input value
	$('#country_id2').val(item);

	// hide proposition list
	$('#country_list_id2').hide();
}










function autocomplet3() {
	var keyword = $('#country_id3').val();
	$.ajax({
		url: 'ajax_refresh3.php',
		type: 'POST',
		data: {keyword:keyword},
		success:function(data){
			$('#country_list_id3').show();
			$('#country_list_id3').html(data);
			if(document.getElementById('country_id3').value=="")
			{
				$('#country_list_id3').hide();
			

			}

		}
	});
	
}

// set_item : this function will be executed when we select an item
function set_item3(item) {

	// change input value
	$('#country_id3').val(item);

	// hide proposition list
	$('#country_list_id3').hide();
}









function autocomplet4() {
	var keyword = $('#country_id4').val();
	$.ajax({
		url: 'ajax_refresh4.php',
		type: 'POST',
		data: {keyword:keyword},
		success:function(data){
			$('#country_list_id4').show();
			$('#country_list_id4').html(data);
			if(document.getElementById('country_id4').value=="")
			{
				$('#country_list_id4').hide();
			

			}

		}
	});
	
}

// set_item : this function will be executed when we select an item
function set_item4(item) {

	// change input value
	$('#country_id4').val(item);

	// hide proposition list
	$('#country_list_id4').hide();
}

































function autocompletlibelletsortie() {
	var keyword = $('#country_idlibelletsortie').val();
	$.ajax({
		url: 'ajax_refreshlibelletsortie.php',
		type: 'POST',
		data: {keyword:keyword},
		success:function(data){
			$('#country_list_idlibelletsortie').show();
			$('#country_list_idlibelletsortie').html(data);
			if(document.getElementById('country_idlibelletsortie').value=="")
			{
				$('#country_list_idlibelletsortie').hide();
			

			}

		}
	});
	
}

// set_item : this function will be executed when we select an item
function set_itemlibelletsortie(item) {

	// change input value
	$('#country_idlibelletsortie').val(item);

	// hide proposition list
	$('#country_list_idlibelletsortie').hide();
}




function autocompletlibelletsortiefiltre() {
	var keyword = $('#country_idlibelletsortiefiltre').val();
	$.ajax({
		url: 'ajax_refreshlibelletsortiefiltre.php',
		type: 'POST',
		data: {keyword:keyword},
		success:function(data){
			$('#country_list_idlibelletsortiefiltre').show();
			$('#country_list_idlibelletsortiefiltre').html(data);
			if(document.getElementById('country_idlibelletsortiefiltre').value=="")
			{
				$('#country_list_idlibelletsortiefiltre').hide();
			

			}

		}
	});
	
}

// set_item : this function will be executed when we select an item
function set_itemlibelletsortiefiltre(item) {

	// change input value
	$('#country_idlibelletsortiefiltre').val(item);

	// hide proposition list
	$('#country_list_idlibelletsortiefiltre').hide();
}



function autocompletlibelletsortiemodif() {
	var keyword = $('#country_idlibelletsortiemodif').val();
	$.ajax({
		url: 'ajax_refreshlibelletsortiemodif.php',
		type: 'POST',
		data: {keyword:keyword},
		success:function(data){
			$('#country_list_idlibelletsortiemodif').show();
			$('#country_list_idlibelletsortiemodif').html(data);
			if(document.getElementById('country_idlibelletsortiemodif').value=="")
			{
				$('#country_list_idlibelletsortiemodif').hide();
			

			}

		}
	});
	
}

// set_item : this function will be executed when we select an item
function set_itemlibelletsortiemodif(item) {

	// change input value
	$('#country_idlibelletsortiemodif').val(item);

	// hide proposition list
	$('#country_list_idlibelletsortiemodif').hide();
}
















function autocompletlibelletentree() {
	var keyword = $('#country_idlibelletentree').val();
	$.ajax({
		url: 'ajax_refreshlibelletentree.php',
		type: 'POST',
		data: {keyword:keyword},
		success:function(data){
			$('#country_list_idlibelletentree').show();
			$('#country_list_idlibelletentree').html(data);
			if(document.getElementById('country_idlibelletentree').value=="")
			{
				$('#country_list_idlibelletentree').hide();
			

			}

		}
	});
	
}

// set_item : this function will be executed when we select an item
function set_itemlibelletentree(item) {

	// change input value
	$('#country_idlibelletentree').val(item);

	// hide proposition list
	$('#country_list_idlibelletentree').hide();
}




function autocompletlibelletentreefiltre() {
	var keyword = $('#country_idlibelletentreefiltre').val();
	$.ajax({
		url: 'ajax_refreshlibelletentreefiltre.php',
		type: 'POST',
		data: {keyword:keyword},
		success:function(data){
			$('#country_list_idlibelletentreefiltre').show();
			$('#country_list_idlibelletentreefiltre').html(data);
			if(document.getElementById('country_idlibelletentreefiltre').value=="")
			{
				$('#country_list_idlibelletentreefiltre').hide();
			

			}

		}
	});
	
}

// set_item : this function will be executed when we select an item
function set_itemlibelletentreefiltre(item) {

	// change input value
	$('#country_idlibelletentreefiltre').val(item);

	// hide proposition list
	$('#country_list_idlibelletentreefiltre').hide();
}



function autocompletlibelletentreemodif() {
	var keyword = $('#country_idlibelletentreemodif').val();
	$.ajax({
		url: 'ajax_refreshlibelletentreemodif.php',
		type: 'POST',
		data: {keyword:keyword},
		success:function(data){
			$('#country_list_idlibelletentreemodif').show();
			$('#country_list_idlibelletentreemodif').html(data);
			if(document.getElementById('country_idlibelletentreemodif').value=="")
			{
				$('#country_list_idlibelletentreemodif').hide();
			

			}

		}
	});
	
}

// set_item : this function will be executed when we select an item
function set_itemlibelletentreemodif(item) {

	// change input value
	$('#country_idlibelletentreemodif').val(item);

	// hide proposition list
	$('#country_list_idlibelletentreemodif').hide();
}










































































function autocomplet5() {
	var keyword = $('#country_id5').val();
	$.ajax({
		url: 'ajax_refresh5.php',
		type: 'POST',
		data: {keyword:keyword},
		success:function(data){
			$('#country_list_id5').show();
			$('#country_list_id5').html(data);
			if(document.getElementById('country_id5').value=="")
			{
				$('#country_list_id5').hide();
			

			}

		}
	});
	
}

// set_item : this function will be executed when we select an item
function set_item5(item) {

	// change input value
	$('#country_id5').val(item);

	// hide proposition list
	$('#country_list_id5').hide();
}






function autocompletref() {
	var keyword = $('#country_idref').val();
	$.ajax({
		url: 'ajax_refreshref.php',
		type: 'POST',
		data: {keyword:keyword},
		success:function(data){
			$('#country_list_idref').show();
			$('#country_list_idref').html(data);
			if(document.getElementById('country_idref').value=="")
			{
				$('#country_list_idref').hide();
			

			}

		}
	});
	
}

// set_item : this function will be executed when we select an item
function set_itemref(item) {

	// change input value
	$('#country_idref').val(item);

	// hide proposition list
	$('#country_list_idref').hide();
}







function autocompletref1() {
	var keyword = $('#country_idref1').val();
	$.ajax({
		url: 'ajax_refreshref1.php',
		type: 'POST',
		data: {keyword:keyword},
		success:function(data){
			$('#country_list_idref1').show();
			$('#country_list_idref1').html(data);
			if(document.getElementById('country_idref1').value=="")
			{
				$('#country_list_idref1').hide();
			

			}

		}
	});
	
}

// set_item : this function will be executed when we select an item
function set_itemref1(item) {

	// change input value
	$('#country_idref1').val(item);

	// hide proposition list
	$('#country_list_idref1').hide();
}











function autocomplet6() {
	var keyword = $('#country_id6').val();
	$.ajax({
		url: 'ajax_refresh6.php',
		type: 'POST',
		data: {keyword:keyword},
		success:function(data){
			$('#country_list_id6').show();
			$('#country_list_id6').html(data);
			if(document.getElementById('country_id6').value=="")
			{
				$('#country_list_id6').hide();
			

			}

		}
	});
	
}

// set_item : this function will be executed when we select an item
function set_item6(item) {

	// change input value
	$('#country_id6').val(item);

	// hide proposition list
	$('#country_list_id6').hide();
}





function autocomplet66() {
	var keyword = $('#country_id66').val();
	$.ajax({
		url: 'ajax_refresh66.php',
		type: 'POST',
		data: {keyword:keyword},
		success:function(data){
			$('#country_list_id66').show();
			$('#country_list_id66').html(data);
			if(document.getElementById('country_id66').value=="")
			{
				$('#country_list_id66').hide();
			

			}

		}
	});
	
}

// set_item : this function will be executed when we select an item
function set_item66(item) {

	// change input value
	$('#country_id66').val(item);

	// hide proposition list
	$('#country_list_id66').hide();
}






function autocomplet8() {
	var keyword = $('#country_id8').val();
	$.ajax({
		url: 'ajax_refresh8.php',
		type: 'POST',
		data: {keyword:keyword},
		success:function(data){
			$('#country_list_id8').show();
			$('#country_list_id8').html(data);
			if(document.getElementById('country_id8').value=="")
			{
				$('#country_list_id8').hide();
			

			}

		}
	});
	
}

// set_item : this function will be executed when we select an item
function set_item8(item) {

	// change input value
	$('#country_id8').val(item);

	// hide proposition list
	$('#country_list_id8').hide();
}




/////////////////////////////////////////////////////////////////////////////////////////


function autocomplet() {
	var keyword = $('#country_id').val();
	$.ajax({
		url: 'ajax_refresh.php',
		type: 'POST',
		data: {keyword:keyword},
		success:function(data){
			$('#country_list_id').show();
			$('#country_list_id').html(data);
			if(document.getElementById('country_id').value=="")
			{
				$('#country_list_id').hide();
			

			}

		}
	});
	
}

// set_item : this function will be executed when we select an item
function set_item(item) {

	// change input value
	$('#country_id').val(item);

	// hide proposition list
	$('#country_list_id').hide();
}



/////////////////////////////////////////////////////////////////////////////////////////


function autocomplet_mad_agence() {
	var keyword = $('#country_mad_agence').val();
	$.ajax({
		url: 'ajax_refresh_mad_agence.php',
		type: 'POST',
		data: {keyword:keyword},
		success:function(data){
			$('#country_list_mad_agence').show();
			$('#country_list_mad_agence').html(data);
			if(document.getElementById('country_mad_agence').value=="")
			{
				$('#country_list_mad_agence').hide();
			

			}

		}
	});
	
}

// set_item : this function will be executed when we select an item
function set_item_mad_agence(item) {

	// change input value
	$('#country_mad_agence').val(item);

	// hide proposition list
	$('#country_list_mad_agence').hide();
}

/////////////////////////////////////////////////////////////////////////////////////////


function autocomplet_mad_compte() {
	var keyword = $('#country_mad_compte').val();
	$.ajax({
		url: 'ajax_refresh_mad_compte.php',
		type: 'POST',
		data: {keyword:keyword},
		success:function(data){
			$('#country_list_mad_compte').show();
			$('#country_list_mad_compte').html(data);
			if(document.getElementById('country_mad_compte').value=="")
			{
				$('#country_list_mad_compte').hide();
			

			}

		}
	});
	
}

// set_item : this function will be executed when we select an item
function set_item_mad_compte(item) {

	// change input value
	$('#country_mad_compte').val(item);

	// hide proposition list
	$('#country_list_mad_compte').hide();
}


/////////////////////////////////////////////////////////////////////////////////////////


function autocomplet_mad_beneficiaire() {
	var keyword = $('#country_mad_beneficiaire').val();
	$.ajax({
		url: 'ajax_refresh_mad_beneficiaire.php',
		type: 'POST',
		data: {keyword:keyword},
		success:function(data){
			$('#country_list_mad_beneficiaire').show();
			$('#country_list_mad_beneficiaire').html(data);
			if(document.getElementById('country_mad_beneficiaire').value=="")
			{
				$('#country_list_mad_beneficiaire').hide();
			

			}

		}
	});
	
}

// set_item : this function will be executed when we select an item
function set_item_mad_beneficiaire(item) {

	// change input value
	$('#country_mad_beneficiaire').val(item);

	// hide proposition list
	$('#country_list_mad_beneficiaire').hide();
}

/////////////////////////////////////////////////////////////////////////////////////////


function autocomplet_mad_cin() {
	var keyword = $('#country_mad_cin').val();
	$.ajax({
		url: 'ajax_refresh_mad_cin.php',
		type: 'POST',
		data: {keyword:keyword},
		success:function(data){
			$('#country_list_mad_cin').show();
			$('#country_list_mad_cin').html(data);
			if(document.getElementById('country_mad_cin').value=="")
			{
				$('#country_list_mad_cin').hide();
			

			}

		}
	});
	
}

// set_item : this function will be executed when we select an item
function set_item_mad_cin(item) {

	// change input value
	$('#country_mad_cin').val(item);

	// hide proposition list
	$('#country_list_mad_cin').hide();
}



























/////////////////////////////////////////////////////////////////////////////////////////


function autocomplet_mad_agencem() {
	var keyword = $('#country_mad_agencem').val();
	$.ajax({
		url: 'ajax_refresh_mad_agencem.php',
		type: 'POST',
		data: {keyword:keyword},
		success:function(data){
			$('#country_list_mad_agencem').show();
			$('#country_list_mad_agencem').html(data);
			if(document.getElementById('country_mad_agencem').value=="")
			{
				$('#country_list_mad_agencem').hide();
			

			}

		}
	});
	
}

// set_item : this function will be executed when we select an item
function set_item_mad_agencem(item) {

	// change input value
	$('#country_mad_agencem').val(item);

	// hide proposition list
	$('#country_list_mad_agencem').hide();
}

/////////////////////////////////////////////////////////////////////////////////////////


function autocomplet_mad_comptem() {
	var keyword = $('#country_mad_comptem').val();
	$.ajax({
		url: 'ajax_refresh_mad_comptem.php',
		type: 'POST',
		data: {keyword:keyword},
		success:function(data){
			$('#country_list_mad_comptem').show();
			$('#country_list_mad_comptem').html(data);
			if(document.getElementById('country_mad_comptem').value=="")
			{
				$('#country_list_mad_comptem').hide();
			

			}

		}
	});
	
}

// set_item : this function will be executed when we select an item
function set_item_mad_comptem(item) {

	// change input value
	$('#country_mad_comptem').val(item);

	// hide proposition list
	$('#country_list_mad_comptem').hide();
}


/////////////////////////////////////////////////////////////////////////////////////////


function autocomplet_mad_beneficiairem() {
	var keyword = $('#country_mad_beneficiairem').val();
	$.ajax({
		url: 'ajax_refresh_mad_beneficiairem.php',
		type: 'POST',
		data: {keyword:keyword},
		success:function(data){
			$('#country_list_mad_beneficiairem').show();
			$('#country_list_mad_beneficiairem').html(data);
			if(document.getElementById('country_mad_beneficiairem').value=="")
			{
				$('#country_list_mad_beneficiairem').hide();
			

			}

		}
	});
	
}

// set_item : this function will be executed when we select an item
function set_item_mad_beneficiairem(item) {

	// change input value
	$('#country_mad_beneficiairem').val(item);

	// hide proposition list
	$('#country_list_mad_beneficiairem').hide();
}

/////////////////////////////////////////////////////////////////////////////////////////


function autocomplet_mad_cinm() {
	var keyword = $('#country_mad_cinm').val();
	$.ajax({
		url: 'ajax_refresh_mad_cinm.php',
		type: 'POST',
		data: {keyword:keyword},
		success:function(data){
			$('#country_list_mad_cinm').show();
			$('#country_list_mad_cinm').html(data);
			if(document.getElementById('country_mad_cinm').value=="")
			{
				$('#country_list_mad_cinm').hide();
			

			}

		}
	});
	
}

// set_item : this function will be executed when we select an item
function set_item_mad_cinm(item) {

	// change input value
	$('#country_mad_cinm').val(item);

	// hide proposition list
	$('#country_list_mad_cinm').hide();
}









/////////////////////////////////////////////////////////////////////////////////////////


function autocomplet6() {
	var keyword = $('#country_id6').val();
	var ste = $('#id_ste').val();
	$.ajax({
		url: 'ajax_refresh6.php',
		type: 'POST',
		data: {keyword:keyword, ste:ste},
		success:function(data){
			$('#country_list_id6').show();
			$('#country_list_id6').html(data);
			if(document.getElementById('country_id6').value=="")
			{
				$('#country_list_id6').hide();
	
			}
			
		}
	});
	
}

// set_item : this function will be executed when we select an item
function set_item6(item) {

	// change input value
	$('#country_id6').val(item);
	// hide proposition list
	$('#country_list_id6').hide();
}


/////////////////////////////////////////////////////////////////////////////////////////


function autocomplet7() {
	var keyword = $('#country_id7').val();
	var ste = $('#id_ste').val();
	$.ajax({
		url: 'ajax_refresh7.php',
		type: 'POST',
		data: {keyword:keyword, ste:ste},
		success:function(data){
			$('#country_list_id7').show();
			$('#country_list_id7').html(data);
			if(document.getElementById('country_id7').value=="")
			{
				$('#country_list_id7').hide();
	
			}
			
		}
	});
	
}

// set_item : this function will be executed when we select an item
function set_item7(item) {

	// change input value
	$('#country_id7').val(item);
	// hide proposition list
	$('#country_list_id7').hide();
}







 
















 /////////////////////////////////////////////////////////////////////////////////////////


function autocompletbeneficiairefop() {
	var keyword = $('#country_id7').val();
	var ste = $('#id_ste').val();
	$.ajax({
		url: 'ajax_refreshbeneficiairefop.php',
		type: 'POST',
		data: {keyword:keyword, ste:ste},
		success:function(data){
			$('#country_list_idbeneficiairefop').show();
			$('#country_list_idbeneficiairefop').html(data);
			if(document.getElementById('country_idbeneficiairefop').value=="")
			{
				$('#country_list_idbeneficiairefop').hide();
	
			}
			
		}
	});
	
}

// set_item : this function will be executed when we select an item
function set_itembeneficiairefop(item) {

	// change input value
	$('#country_idbeneficiairefop').val(item);
	// hide proposition list
	$('#country_list_idbeneficiairefop').hide();
}


