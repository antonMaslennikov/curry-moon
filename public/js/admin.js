function readURL(input) {
	if (input.files && input.files[0]) {
		var reader = new FileReader();

		reader.onload = function (e) {
			//$('.preview-image-wrapper').removeClass('hidden');
			$('.preview-image').attr('src', e.target.result).show();
		};

		reader.readAsDataURL(input.files[0]);
	}
}