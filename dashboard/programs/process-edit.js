document.addEventListener("DOMContentLoaded", function() {
    const objData = JSON.parse(sessionStorage.getItem('objData'));

    if (objData) {
        document.getElementById('program_id').value = objData.progid;
        document.getElementById('full_name').value = objData.progfullname;
        document.getElementById('short_name').value = objData.progshortname;
        loadColleges(objData.college_name);
        loadDepartments();
        loadDepartmentsByDeptName(objData.department_name);
    } else {
        window.location.href = '../dashboard-table.php';
    }
});

let colleges = {};
let departments = {};
let selectedCollegeID = 0;

function loadColleges(selectedCollegeName) {
    axios.get('get-data.php', { params: { type: 'colleges' } })
        .then(function(response) {
            const collegeSelect = document.getElementById('college');
            const addedColleges = new Set();
            collegeSelect.innerHTML = '<option value="" disabled selected>Select College</option>';

            if (response.data && response.data.length > 0) {
                response.data.forEach(function(college) {
                    if (!addedColleges.has(college.collfullname)) {
                        colleges[college.collid] = college.collfullname;
                        const option = document.createElement('option');
                        option.value = college.collid;
                        option.textContent = college.collfullname;
                        collegeSelect.appendChild(option);

                        if (college.collfullname === selectedCollegeName) {
                            option.selected = true;
                            selectedCollegeID = college.collid;
                        }

                        addedColleges.add(college.collfullname);
                    }
                });
            }

            loadDepartments();
        })
        .catch(function(error) {
            console.error('Error loading college data:', error);
        });
}

function loadDepartments() {
    axios.get('get-data.php', { params: { type: 'departments' } })
        .then(function(response) {
            departments = {};

            if (response.data && response.data.length > 0) {
                response.data.forEach(function(department) {
                    const deptCollegeId = department.deptcollid;
                    const departmentName = department.deptfullname;

                    if (!departments[deptCollegeId]) {
                        departments[deptCollegeId] = [];
                    }
                    departments[deptCollegeId].push(department);
                });
            }
        })
        .catch(function(error) {
            console.error('Error loading department data:', error);
        });
}

function loadDepartmentsByDeptName(selectedDepartmentName) {
    axios.get('get-data.php', { params: { type: 'departments' } })
        .then(function(response) {
            const departmentSelect = document.getElementById('department');
            const addedDepartments = new Set();
            departmentSelect.innerHTML = '<option value="" disabled selected>Select Department</option>';

            if (response.data && response.data.length > 0) {
                response.data.forEach(function(department) {
                    const departmentName = department.deptfullname;

                    if (department.deptcollid === selectedCollegeID) {
                        if (!addedDepartments.has(departmentName)) {
                            if (!departments[department.deptcollid]) {
                                departments[department.deptcollid] = [];
                            }
                            departments[department.deptcollid].push(department);

                            addedDepartments.add(departmentName);

                            const option = document.createElement('option');
                            option.value = department.deptid;
                            option.textContent = departmentName;
                            departmentSelect.appendChild(option);

                            if (departmentName === selectedDepartmentName) {
                                option.selected = true;
                            }
                        }
                    }
                });
            }
        })
        .catch(function(error) {
            console.error('Error loading department data:', error);
        });
}

function onCollegeChange(event) {
    const selectedCollegeId = event.target.value;
    selectedCollegeID = selectedCollegeId;
    const departmentSelect = document.getElementById('department');
    departmentSelect.innerHTML = '<option value="" disabled selected>Select Department</option>';

    if (departments[selectedCollegeID]) {
        const addedDepartmentNames = new Set();

        departments[selectedCollegeID].forEach(function(department) {
            const departmentName = department.deptfullname;

            if (!addedDepartmentNames.has(departmentName)) {
                const option = document.createElement('option');
                option.value = department.deptid;
                option.textContent = departmentName;
                departmentSelect.appendChild(option);

                addedDepartmentNames.add(departmentName);
            }
        });
    } else {
        const noDepartmentsOption = document.createElement('option');
        noDepartmentsOption.value = '';
        noDepartmentsOption.textContent = 'No departments available';
        departmentSelect.appendChild(noDepartmentsOption);
    }
}

const collegeSelect = document.getElementById('college');
collegeSelect.addEventListener('change', onCollegeChange);
