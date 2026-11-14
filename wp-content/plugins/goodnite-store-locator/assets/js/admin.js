/**
 * Goodnite Store Locator - Admin JS
 */
jQuery(document).ready(function ($) {
	var dropzone = $('#goodnite-dropzone');
	var fileInput = $('#dealers_csv');
	var fileNameDisplay = dropzone.find('.file-name-display');

	if (fileInput.length) {
		fileInput.on('change', function () {
			if (this.files && this.files[0]) {
				var file = this.files[0];
				fileNameDisplay.html('<strong>Selected:</strong> ' + file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)');
				dropzone.addClass('has-file');
			}
		});

		// Drag and drop event styling
		dropzone.on('dragover dragenter', function (e) {
			e.preventDefault();
			e.stopPropagation();
			$(this).addClass('dragover');
		});

		dropzone.on('dragleave dragend drop', function (e) {
			e.preventDefault();
			e.stopPropagation();
			$(this).removeClass('dragover');
		});
	}
});
