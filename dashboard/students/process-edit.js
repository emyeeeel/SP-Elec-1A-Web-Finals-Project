document.addEventListener("DOMContentLoaded", function() {
    const objData = JSON.parse(sessionStorage.getItem('objData'));

    if (objData) {
        document.getElementById('student_id').value = objData.studid;
        document.getElementById('first_name').value = objData.studfirstname;
        document.getElementById('middle_name').value = objData.studmidname;
        document.getElementById('last_name').value = objData.studlastname;
        document.getElementById('year').value = objData.studyear;

        loadColleges(objData.college_name, objData);  // Pass objData to loadColleges
    } else {
        window.location.href = '../dashboard-table.php';
    }
});

let colleges = {};
let programs = {};
let selectedCollegeID = 0;

function loadColleges(selectedCollegeName, objData) {
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

                            // Load programs after the college is set
                            loadPrograms(objData);  // Pass objData to loadPrograms  
                        }

                        addedColleges.add(college.collfullname);
                    }
                });
            }
        })
        .catch(function(error) {
            console.error('Error loading college data:', error);
        });
}

function loadPrograms(objData) {
    axios.get('get-data.php', { params: { type: 'programs' } })
        .then(function(response) {
            programs = {};
            if (response.data && response.data.length > 0) {
                response.data.forEach(function(program) {
                    const progCollegeId = program.progcollid;
                    const programName = program.progfullname;

                    if (!programs[progCollegeId]) {
                        programs[progCollegeId] = [];
                    }
                    programs[progCollegeId].push(program);
                });

                // After programs are loaded, load the programs for the selected college and select the program
                loadProgramsByProgName(objData.program_name);  // Pass objData here as well
            }
        })
        .catch(function(error) {
            console.error('Error loading program data:', error);
        });
}

function loadProgramsByProgName(selectedProgramName) {
    const programSelect = document.getElementById('program');
    programSelect.innerHTML = '<option value="" disabled selected>Select Program</option>';

    if (programs[selectedCollegeID] && programs[selectedCollegeID].length > 0) {
        const addedPrograms = new Set();

        programs[selectedCollegeID].forEach(function(program) {
            const programName = program.progfullname;

            if (!addedPrograms.has(programName)) {
                addedPrograms.add(programName);

                const option = document.createElement('option');
                option.value = program.progid;
                option.textContent = programName;
                programSelect.appendChild(option);

                // Select the program if the program name matches the selected program name
                if (programName === selectedProgramName) {
                    option.selected = true;
                }
            }
        });
    } else {
        const noProgramsOption = document.createElement('option');
        noProgramsOption.value = '';
        noProgramsOption.textContent = 'No programs available';
        programSelect.appendChild(noProgramsOption);
    }
}

function onCollegeChange(event) {
    const selectedCollegeId = event.target.value;
    selectedCollegeID = selectedCollegeId;
    const programSelect = document.getElementById('program');
    programSelect.innerHTML = '<option value="" disabled selected>Select Program</option>';

    if (programs[selectedCollegeID]) {
        const addedProgramNames = new Set();

        programs[selectedCollegeID].forEach(function(program) {
            const programName = program.progfullname;

            if (!addedProgramNames.has(programName)) {
                const option = document.createElement('option');
                option.value = program.progid;
                option.textContent = programName;
                programSelect.appendChild(option);

                addedProgramNames.add(programName);
            }
        });
    } else {
        const noProgramsOption = document.createElement('option');
        noProgramsOption.value = '';
        noProgramsOption.textContent = 'No programs available';
        programSelect.appendChild(noProgramsOption);
    }
}

document.getElementById('college').addEventListener('change', onCollegeChange);
