let colleges = {};

function loadColleges() {
    axios.get('get-data.php', { params: { type: 'colleges' } })
        .then(function(response) {
            const collegeSelect = document.getElementById('college');
            if (response.data && response.data.length > 0) {
                response.data.forEach(function(college) {
                    colleges[college.collid] = college.collfullname;
                    const option = document.createElement('option');
                    option.value = college.collid;
                    option.textContent = college.collfullname;
                    collegeSelect.appendChild(option);
                });
            }
        })
        .catch(function(error) {
            console.error('Error loading college data:', error);
        });
}

document.addEventListener('DOMContentLoaded', function () {
    loadColleges();
});
