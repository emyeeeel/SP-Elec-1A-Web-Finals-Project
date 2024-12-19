let colleges = {};
let departments = {};

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

function loadDepartments() {
    axios.get('get-data.php', { params: { type: 'departments' } })
        .then(function(response) {
            if (response.data && response.data.length > 0) {
                response.data.forEach(function(department) {
                    if (!departments[department.deptcollid]) {
                        departments[department.deptcollid] = [];
                    }
                    departments[department.deptcollid].push(department);
                });
            }
        })
        .catch(function(error) {
            console.error('Error loading department data:', error);
        });
}

function onCollegeChange(event) {
    const selectedCollegeId = event.target.value;
    const departmentSelect = document.getElementById('department');
    departmentSelect.innerHTML = '<option value="" disabled selected>Select department</option>';

    if (departments[selectedCollegeId] && departments[selectedCollegeId].length > 0) {
        departments[selectedCollegeId].forEach(function(department) {
            const option = document.createElement('option');
            option.value = department.deptid;
            option.textContent = department.deptfullname;
            departmentSelect.appendChild(option);
        });
    } else {
        const noDepartmentsOption = document.createElement('option');
        noDepartmentsOption.value = '';
        noDepartmentsOption.textContent = 'No departments available';
        departmentSelect.appendChild(noDepartmentsOption);
    }
}

document.addEventListener('DOMContentLoaded', function () {
    loadColleges();
    loadDepartments();

    const collegeSelect = document.getElementById('college');
    collegeSelect.addEventListener('change', onCollegeChange);
});
