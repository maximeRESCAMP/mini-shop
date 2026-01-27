
//selecteur
const select = document.querySelector('#address_country');
const inputZipCode = document.querySelector('#address_zipCode');
const selectCity = document.querySelector('#address_city');
const inputStreet = document.querySelector('#address_street');
const inputCities = document.querySelector('#cities');
const inputAdresses = document.querySelector('#adresses');
const divError = document.getElementById('error-form-zip');

changeCountry();

async function fetchJsonFromApi(url, options = {}) {
    const response = await fetch(url);
    if (!response.ok) {
        throw new Error("Erreur serveur");
    }
    return response.json();
}

function removeAlert(element) {
    element.removeAttribute('role');
    element.classList.remove('alert', 'alert-danger');
    if (element.childElementCount > 0) {
        element.lastChild.remove();
    }
}

function addAlert(element, message) {
    element.setAttribute('role', 'alert');
    element.classList.add('alert', 'alert-danger');
    element.append(message)
}


function changeCountry() {
    select.addEventListener('change', function () {
        inputZipCode.removeAttribute("disabled");
        if (this.value === 'FR') {
            selectCity.setAttribute("disabled", "");
            inputStreet.setAttribute("disabled", "");
            inputZipCode.addEventListener('focusin', function () {
                selectCity.removeAttribute("disabled");
            });
            inputZipCode.focus();
            inputZipCode.addEventListener('change', changeZipCode);
        } else {
            inputZipCode.focus();
            selectCity.removeAttribute("disabled");
            inputStreet.removeAttribute("disabled");
        }
    });
}


async function changeZipCode() {
    try {
        const adresses = await fetchJsonFromApi(`https://data.geopf.fr/geocodage/search?postcode=${this.value}&q=${this.value}`);
        selectCity.addEventListener('change', function () {
            inputStreet.removeAttribute("disabled");
        });
        removeAlert(divError);


        const arrayCities = adresses.features.map((address) => address.properties.city);
        //dédoublone le tableau
        const uniqsCities = [...new Set(arrayCities)];

        //eneleve les ancienen options
        for (let index = inputCities.options.length - 1; index >= 0; index--) {
            inputCities.children[index].remove();
        }

        for (let [label, city] of Object.entries(uniqsCities)) {
            let opt1 = document.createElement("option");
            opt1.value = city;
            inputCities.append(opt1);
        }


        selectCity.focus();
        inputStreet.addEventListener('input', changeStreet);
    } catch (error) {
        addAlert(divError, 'Code postal introuvable')
    }
}

async function changeStreet(event) {
    if (this.value.length > 5) {
        const addresses = await fetchJsonFromApi(`https://data.geopf.fr/geocodage/search?postcode=${inputZipCode.value}&city=${selectCity.value}&q=${this.value}&limit=5`);
        const streets = addresses.features.map((address) => address.properties.name);


        //eneleve les ancienen options
        for (let index = inputAdresses.options.length - 1; index >= 0; index--) {
            inputAdresses.children[index].remove();
        }

        for (let [label, street] of Object.entries(streets)) {
            let opt1 = document.createElement("option");
            opt1.value = street;
            inputAdresses.append(opt1);
        }
    }
}



