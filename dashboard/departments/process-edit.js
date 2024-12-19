document.addEventListener("DOMContentLoaded", function() {
    const objData = JSON.parse(sessionStorage.getItem('objData'));
    console.log(objData);

    if (objData) {
        document.getElementById('department_id').value = objData.deptid;
        document.getElementById('full_name').value = objData.deptfullname;
        document.getElementById('short_name').value = objData.deptshortname;

        loadColleges(objData.college_name);  
    } else {
        window.location.href = '../dashboard-table.php';
    }
});

let colleges = {};

function loadColleges(selectedCollegeName) {
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

                    if (college.collfullname === selectedCollegeName) {
                        option.selected = true;
                    }
                });
            }
        })
        .catch(function(error) {
            console.error('Error loading college data:', error);
        });
}
