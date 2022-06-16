function preview() {
	let images = imagesArray;
	let nrOfImages = images.length;
	let carouselElement = document.getElementById("img-container");
	let indicatorElement = document.getElementById("indicator");
	for (var index = 0; index < nrOfImages; index++) {
		console.log(images[index]);
		if (images[index] != "https://iproject36.ip.aimsites.nl/pics/" && images[index] != "pics/") {
			let image = createImageTag(index);
			carouselElement.innerHTML += image;

			if (nrOfImages > 1) {
				let button = createButtonTag(index);
				indicatorElement.innerHTML += button;
			}

			let target = document.getElementById("frame" + index);
			target.src = images[index];
		}
	}
}

function createImageTag(index) {
	let image = "";
	if (index === 0) {
		image = "<div class='carousel-item active rounded img-fluid shadow'> <img id='frame" + index + "'src='' class='carousel-img d-block'/> </div>";
	} else {
		image = "<div class='carousel-item rounded img-fluid shadow'> <img id='frame" + index + "'src='' class='carousel-img d-block w-100'/> </div>";
	}
	return image;
}

function createButtonTag(index) {
	let button = "";
	if (index === 0) {
		button = "<button type='button' data-bs-target='#carouselExampleIndicators' data-bs-slide-to='" + index + "' class='active' aria-current='true' aria-label='Slide " + (index + 1) + "'></button>";
	} else {
		button = "<button type='button' data-bs-target='#carouselExampleIndicators' data-bs-slide-to='" + index + "' aria-label='Slide " + (index + 1) + "'></button>";
	}
	return button;
}