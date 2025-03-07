const siteUrl = cityData.root_url;

const populateTable = (data) => {
    const tableBody = document.querySelector('#cities-table-body');
    tableBody.innerHTML = ""; // Clear the table before adding new rows
    console.log('DATA FROM FUNCTION', data);

    if (data.length === 0) {
        tableBody.innerHTML = "<tr><td colspan='3'>No cities found.</td></tr>";
        return;
    }

    data.forEach(city => {
        const row = document.createElement("tr");
        row.innerHTML = `
            <td>${city.city}</td>
            <td>${city.country}</td>
            <td>${city.temperature}</td>
        `;
        tableBody.appendChild(row);
    });
};

const showLoader = () => {
    const tableBody = document.querySelector('#cities-table-body');
    tableBody.innerHTML = "<tr id='loader'><td colspan='3'>Data Loading...</td></tr>";
};

document.addEventListener("DOMContentLoaded", async function () {
    const searchInput = document.getElementById("city-search");
    const resetBtn = document.getElementById("reset");
    let initialDataLoaded = false; // Flaging to ensure initial fetch runs only once

    async function fetchInitialCities() {
        if (initialDataLoaded) return; // Preventing re-fetching initial data
        showLoader();
        try {
            const response = await fetch(`${siteUrl}/wp-json/cities/v1/search`);
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }

            const data = await response.json();
            populateTable(data);
            initialDataLoaded = true; // Mark that initial data is loaded

        } catch (error) {
            console.error("Error fetching city data:", error);
        }
    }

    fetchInitialCities(); // Runing once on page load

    resetBtn.addEventListener('click', ()=>{
        initialDataLoaded = false;
        searchInput.value ='';
        fetchInitialCities();
    });

    let debounceTimeout;
    function debounce(func, delay) {
        return function (...args) {
            clearTimeout(debounceTimeout);
            debounceTimeout = setTimeout(() => func.apply(this, args), delay);
        };
    }

    async function fetchCityData() {
        const query = searchInput.value.trim();
        if (!query) return;

        showLoader();
    
        try {
            const response = await fetch(`${siteUrl}/wp-json/cities/v1/search?term=${query}`);
    
       
            if (response.status === 404) {
                populateTable([]); // Pass empty array to show "No cities found"
                return;
            }
    
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
    
            const data = await response.json();
            console.log("Fetched Data FROM INPUT:", data);
            populateTable(data);
    
        } catch (error) {
            console.error("Error fetching city data:", error);
        }
    }

    searchInput.addEventListener("input", debounce(fetchCityData, 300));
});
