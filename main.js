$(document).ready(function() {	
	$("#addNew").on('click', function() {
		$("#tableManager").modal('show');
	});

	getExistingData(0, 10);
});

function viewRedit(rowID) {
	$.ajax({
			url: 'ajax.php',
			method: 'POST',
			datatype: 'json',
			data: {
				key: 'getRowData',
				rowID: rowID
			}, success: function (response) {
				if (type == "view") {
					
				} else {
					$("#editRowID").val(response.rowID);
					$("#breaker").val(response.breaker);
					$("#lunch").val(response.lunch);
					$("#dinner").val(response.dinner);
					$("#anythingElse").val(response.anythingElse);
					$("#manageBtn").attr("value", "Save Change").attr("onclick", "managerData('updateRow')");
				}
					$(".modal-title").html(response.breaker);
					$("#tableManager").modal('show');
			}
		});
}


function getExistingData(start, limit) {
	$.ajax({
		url: 'ajax.php',
		method: 'POST',
		dataType: 'text',
		data: {
			key: 'getExistingData',
			start: start,
			limit: limit
		},success: function (response) {
			if (response != "reachedMax") {
				$('tbody').append(response);
				start += limit;
				getExistingData(start, limit);
			}else {
				$('.table').DataTable();
			}
		}
	});
}

function managerData(key) {
	var breaker = $("#breaker");
	var lunch = $("#lunch");
	var dinner = $("#dinner");
	var anythingElse  =$("#anythingElse");
	var editRowID = $("#editRowID");

	if (isNotEmpty(breaker) && isNotEmpty(lunch) && isNotEmpty(dinner)) {
		$.ajax({
			url: 'ajax.php',
			method: 'POST',
			datatype: 'text',
			data: {
				key: key,
				breaker: breaker.val(),
				lunch: lunch.val(),
				dinner: dinner.val(),
				anythingElse: anythingElse.val(),
				rowID: editRowID.val()
			}, success: function (response) {
				if (response != "success") {
					alert(response);
				}
				else {
					$("#breaker_"+editRowID.val()).html(breaker.val());
					$("#lunch_"+editRowID.val()).html(lunch.val());
					$("#dinner_"+editRowID.val()).html(dinner.val());
					$("#anythingElse_"+editRowID.val()).html(anythingElse.val());
					breaker.val('');
					lunch.val('');
					dinner.val('');
					anythingElse.val('');
					$("#tableManager").modal('hide');
					$("#manageBtn").attr('value', 'Add').attr('onclick', "managerData('addNew')");
				}
			}
		});
	}
}

function isNotEmpty(caller) {
	if (caller.val() == '') {
		caller.css('border', '1px solid red');
		return false;
	} else
		caller.css('border', '');
	
	return true;
}