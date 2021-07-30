(function (factory) {
	/* global define */
	if (typeof define === 'function' && define.amd) {
		// AMD. Register as an anonymous module.
		define(['jquery'], factory);
	} else {
		// Browser globals: jQuery
		factory(window.jQuery);
	}
}(function ($) {

	// predefined settings
	var max_width = 800,
		max_height = 800,
		quality = 0.9;

	// template
	var template = $.summernote.renderer.getTemplate();


	/**
	 * @class plugin.resizedDataImage
	 *
	 * resizedDataImage Plugin
	 *
	 * dependencies:
	 * exif.js (https://github.com/exif-js/exif-js)
	 *
	 * this blog post helped a lot:
	 * http://chariotsolutions.com/blog/post/take-and-manipulate-photo-with-web-page/
	 */
	$.summernote.addPlugin({
		name: 'resizedDataImage',
		buttons: {
			resizedDataImage: function () {
				return template.button('<i class="fa fa-camera"></i>' +
					'<input type="file" name="file" accept="image/*">', {
					event: 'resizedDataImage',
					hide: true
				});
			}
		},
		events: {
			resizedDataImage: function (event, editor, layoutInfo) {
				var $editable = layoutInfo.editable(),
					$input = $(event.target);
				$input.on('change', function () {
					if (this.files.length === 0) {
						return;
					}

					var imageFile = this.files[0],
						image = new Image(),
						url = window.URL ? window.URL : window.webkitURL;

					image.src = url.createObjectURL(imageFile);
					image.onload = function (e) {
						// release URL object as soon as it is unused
						url.revokeObjectURL(this.src);

						var width = image.width,
							height = image.height,
							canvas = document.createElement('canvas');

						EXIF.getData(imageFile, function () {

							// check if image is rotated by 90¡Æ or 270¡Æ
							if (EXIF.getTag(this, 'Orientation') >= 5) {
								width = image.height;
								height = image.width;
							}

							// apply maximum resolution
							if (width / max_width > height / max_height) {
								if (width > max_width) {
									height *= max_width / width;
									width = max_width;
								}
							} else {
								if (height > max_height) {
									width *= max_height / height;
									height = max_height;
								}
							}
							canvas.width = width;
							canvas.height = height;

							var ctx = canvas.getContext('2d');
							ctx.fillStyle = 'transparent';
							ctx.fillRect(0, 0, canvas.width, canvas.height);

							// transform flipped or rotated images
							// see: http://www.daveperrett.com/articles/2012/07/28/exif-orientation-handling-is-a-ghetto/
							switch (EXIF.getTag(this, 'Orientation')) {
								case 8:
									ctx.transform(0, -1, 1, 0, 0, height); // rotate left
									break;
								case 7:
									ctx.transform(-1, 0, 0, 1, width, 0); // flip vertically
									ctx.transform(0, -1, 1, 0, 0, height); // rotate left
									break;
								case 6:
									ctx.transform(0, 1, -1, 0, width, 0); // rotate right
									break;
								case 5:
									ctx.transform(-1, 0, 0, 1, width, 0); // flip vertically
									ctx.transform(0, 1, -1, 0, width, 0); // rotate right
									break;
								case 4:
									ctx.transform(1, 0, 0, -1, 0, height); // flip horizontally and vertically
									break;
								case 3:
									ctx.transform(-1, 0, 0, -1, width, height); // flip horizontally
									break;
								case 2:
									ctx.transform(-1, 0, 0, 1, width, 0); // flip vertically
									break;
								case 1:
									ctx.transform(1, 0, 0, 1, 0, 0); // no transformation
									break;
								default:
									ctx.transform(1, 0, 0, 1, 0, 0); // no transformation
									break;
							}

							// draw image into the canvas
							if (EXIF.getTag(this, 'Orientation') >= 5) {
								ctx.drawImage(image, 0, 0, height, width);
							} else {
								ctx.drawImage(image, 0, 0, width, height);
							}

							var data = canvas.toDataURL(imageFile.type, quality);
							editor.insertImage($editable, data, imageFile.name);
						});
					};

					// unbind trigger and unset value
					$input.off('change').val('');
				});
			}
		}
	});
}));