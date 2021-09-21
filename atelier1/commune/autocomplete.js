function autocomplet() {
	var min_length = 3; // nombre de caractère avant lancement recherche
	var keyword = $('#departement').val();  // departement fait référence au champ de recherche puis lancement de la recherche grace ajax_refres
    if (keyword.length >= min_length) {
		$.ajax({
			url: 'ajax_refresh.php',
			type: 'POST',
			data: {keyword:keyword},
			success:function(data){
				$('#departement_list').show();
				$('#departement_list').html(data);
			}
		});
	} else {
		$('#departement_list').hide();
	}
}

// Lors du choix dans la liste
function set_item(item) {
	// change input value
	$('#departement').val(item);
	// hide proposition list
	$('#departement_list').hide();
}