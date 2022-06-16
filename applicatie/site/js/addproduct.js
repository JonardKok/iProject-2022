//Plaatje toevoegen functies

function preview() {
	let images = event.target.files;
	let nrOfImages = images.length;
	let carouselElement = document.getElementById("img-container");
	let indicatorElement = document.getElementById("indicator");
	resetCarousel(carouselElement, indicatorElement);
	for (var index = 0; index < nrOfImages; index++) {
		let image = createImageTag(index);
		carouselElement.innerHTML += image;

		let button = createButtonTag(index);
		indicatorElement.innerHTML += button;

		let target = document.getElementById("frame" + index);
		target.src = URL.createObjectURL(images[index]);
	}
}

function createImageTag(index) {
	let image = "";
	if(index === 0 ){
		image = "<div class='carousel-item active'> <img id='frame" + index + "'src='' class='d-block'/> </div>";
	}else{
		image = "<div class='carousel-item'> <img id='frame" + index + "'src='' class='d-block w-100'/> </div>";
	}
	return image;
}

function createButtonTag(index) {
	let button = "";
	if(index === 0 ){
		button = "<button type='button' data-bs-target='#carouselExampleIndicators' data-bs-slide-to='" + index + "' class='active' aria-current='true' aria-label='Slide " + (index+1) + "'></button>";
	}else{
		button = "<button type='button' data-bs-target='#carouselExampleIndicators' data-bs-slide-to='" + index + "' aria-label='Slide " + (index+1) + "'></button>";
	}
	return button;
}

function resetCarousel(carouselElement, indicatorElement) {
	carouselElement.innerHTML = "";
	indicatorElement.innerHTML = "";
}









//Categorieën selecteren functies


const categoryNumberIndex = 0;
const categoryNameIndex = 1;
const categoryParentIndex = 2;


function makeSubmenu(value, index) {

	resetChildRubrics(index);

	let currentCategory = "category" + index;
	let nextCategory = "category" + (index + 1);
	if (value.length == 0) document.getElementById(currentCategory).innerHTML = "<option></option>";
	else {
		let rubricOptions = "";

        let rubricFound = false;

        for (var i = 0; i < rubricArray.length; i++) {
            let rubric = rubricArray[i];
            if (rubric[categoryParentIndex] == value) {
                if (!rubricFound) {
                    rubricOptions += "<option disabled selected>Categorie</option>";
                    rubricFound = true;
                }
                rubricOptions += "<option value='" + rubric[categoryNumberIndex] + "'>" + rubric[categoryNameIndex] + "</option>";
                document.getElementById(nextCategory).style.display = "block";
            }
        }
        document.getElementById(nextCategory).innerHTML = rubricOptions;
    }
    makeSelectElementsVisible();
}

function getRubricIndex(rubricname) {
    for (var i = 0; i < rubricArray.length; i++) {
        let rubric = rubricArray[i];
        if (rubric[categoryNameIndex] == rubricname) {
            return i;
        }
    }
}

function resetSelection() {
	makeParentOptions(); //Maakt het eerste select element
	makeSelectElementsVisible(); //Maakt alle select elementen die niet ingevuld zijn onzichtbaar
}

function makeSelectElementsVisible() {
    for (var i = 2; i < 6; i++) {
        let index = "category" + i;
        let selectElement = document.getElementById(index);
        if (selectElement.options.length == 0) {
            selectElement.style.display = "none";
        } else {
            selectElement.style.display = "block";
        }
    }
}

function resetChildRubrics(index) {
    for (var i = index; i < 3; i++) {
        let elementIndex = "category" + (i + 2);
        document.getElementById(elementIndex).innerHTML = "";
    }
}

function makeParentOptions() {
    let rubricOptions = "<option disabled selected>Categorie</option>";
    for (var i = 0; i < rubricArray.length; i++) {
        let rubric = rubricArray[i];
        if (rubric[categoryParentIndex] == -1) {
            rubricOptions += "<option value='" + rubric[categoryNumberIndex] + "'>" + rubric[categoryNameIndex] + "</option>";
        }
    }
    document.getElementById("category1").innerHTML = rubricOptions
}