const rangeInputVerzendkosten = document.querySelectorAll(".range-input-verzendkosten input"),
    priceInputVerzendkosten = document.querySelectorAll(".price-input-verzendkosten input"),
    rangeVerzendkosten = document.querySelector(".slider-verzendkosten .progress-verzendkosten");
let priceGapVerzendkosten = 1;

priceInputVerzendkosten.forEach(input => {
    input.addEventListener("input", e => {
        let minPrice = parseInt(priceInputVerzendkosten[0].value),
            maxPrice = parseInt(priceInputVerzendkosten[1].value);

        if ((maxPrice - minPrice >= priceGapVerzendkosten) && maxPrice <= rangeInputVerzendkosten[1].max) {
            if (e.target.className === "input-min-verzendkosten") {
                rangeInputVerzendkosten[0].value = minPrice;
                rangeVerzendkosten.style.left = ((minPrice / rangeInputVerzendkosten[0].max) * 100) + "%";
            } else {
                rangeInputVerzendkosten[1].value = maxPrice;
                rangeVerzendkosten.style.right = 100 - (maxPrice / rangeInputVerzendkosten[1].max) * 100 + "%";
            }
        }
    });
});

rangeInputVerzendkosten.forEach(input => {
    input.addEventListener("input", e => {
        let minVal = parseInt(rangeInputVerzendkosten[0].value),
            maxVal = parseInt(rangeInputVerzendkosten[1].value);

        if ((maxVal - minVal) < priceGapVerzendkosten) {
            if (e.target.className === "range-min-verzendkosten") {
                rangeInputVerzendkosten[0].value = maxVal - priceGap
            } else {
                rangeInputVerzendkosten[1].value = minVal + priceGap;
            }
        } else {
            priceInputVerzendkosten[0].value = minVal;
            priceInputVerzendkosten[1].value = maxVal;
            rangeVerzendkosten.style.left = ((minVal / rangeInputVerzendkosten[0].max) * 100) + "%";
            rangeVerzendkosten.style.right = 100 - (maxVal / rangeInputVerzendkosten[1].max) * 100 + "%";
        }
    });
});