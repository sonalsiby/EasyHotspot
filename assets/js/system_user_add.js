function setValues() {
	$.ajax({
		async: true,
		method: "POST",
		url: 'model/db_fetch_user_details.php',
		data: { 'id': id }
	}).done(function(html) {
		var data = JSON.parse(html)['data'][0];
		$('#em-user-name').val(data['username']),
		$('#em-user-name').attr('readonly', true);
		$('#em-fullname').val(data['name']);
		$('#em-address').val(data['address']);
		$('#em-phone').val(data['phone']);
		$('#em-user-level').val(data['user_level']);
		$('#em-user-status').val(data['status']);
	});
}

function resetPass() {
	iziToast.warning({
		title: 'Password Reset',
		message: 'Are you sure to reset password for the selected user ?',
		overlay: true,
		displayMode: 'once',
		drag: false,
		timeout: 8000,
		position: 'center',
		inputs: [
			['<button class="btn btn-danger" style="margin-right: 10px;" id="modal-reset">Reset', 'click', function (instance, toast, input, e) {
                instance.hide({}, toast);
                $.ajax({
					async: true,
					method: "POST",
					url: 'model/db_reset_user_pass.php',
					data: { 'id': id }
				}).done(function(html) {
					iziToast.success({
						title: 'Password Reset',
						message: 'User password reset successfully to \'password\''
					});
					loadContent('view/system_users.php');
				});
            }, true]
		]
	});
}

function updateUser() {
	showLoader();
	$.ajax({
		async: true,
		method: "POST",
		url: 'model/db_user_update.php',
		data: {
			'id': id,
			'name': $('#em-fullname').val(),
			'address': $('#em-address').val(),
			'phone': $('#em-phone').val(),
			'status': $('#em-user-status').val(),
			'user_level': $('#em-user-level').val(),
		}
	}).done(function(html) {
		iziToast.success({
			title: 'Edit User',
			message: 'User details updated successfully'
		});
		loadContent('view/system_users.php');
	});
}

function addUser() {
	showLoader();
	$.ajax({
		async: true,
		method: "POST",
		url: 'controller/system_user_add.php',
		data: {
			'username': $('#em-user-name').val(),
			'fullname': $('#em-fullname').val(),
			'address': $('#em-address').val(),
			'phone': $('#em-phone').val(),
			'status': $('#em-user-status').val(),
			'user_level': $('#em-user-level').val()
		}
	}).done(function(html) {
		switch (html) {
			case '0':
				iziToast.error({
					title: 'User Account Creation',
					message: 'User Account creation failed. Not a valid username.'
					});
				break;
				
			case '1':
				iziToast.error({
					title: 'User Account Creation',
					message: 'User Account creation failed. Username already exists.'
					});
				break;
			
			case '2':
				iziToast.success({
					title: 'User Account Creation',
					message: 'User Account created successfully.'
					});
				break;
		}
	});
	loadContent('view/system_users.php');		
}

$('#epage-add-user-profile').ready(function() {
	
	if (process_type=='edit') {
		setValues();
		$('#eprocess-btn').on('click', updateUser);
		$('#eprocess-btn').html('Update User')
		$('#ereset-btn').on('click', resetPass);
	} else if (process_type=='add') {
		$('#eprocess-btn').on('click', addUser);
	}
	$('#back-btn').on('click', function() {
		loadContent('view/system_users.php');
	});
	
});
