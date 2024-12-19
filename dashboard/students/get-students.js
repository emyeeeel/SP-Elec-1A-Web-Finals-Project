let colleges = {};
let programs = {};

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

function loadPrograms() {
    axios.get('get-data.php', { params: { type: 'programs' } })
        .then(function(response) {
            if (response.data && response.data.length > 0) {
                response.data.forEach(function(program) {
                    if (!programs[program.progcollid]) {
                        programs[program.progcollid] = [];
                    }
                    programs[program.progcollid].push(program);
                });
            }
        })
        .catch(function(error) {
            console.error('Error loading program data:', error);
        });
}

function onCollegeChange(event) {
    const selectedCollegeId = event.target.value;
    const programSelect = document.getElementById('program');

    programSelect.innerHTML = '<option value="" disabled selected>Select Program</option>';

    if (programs[selectedCollegeId] && programs[selectedCollegeId].length > 0) {
        programs[selectedCollegeId].forEach(function(program) {
            const option = document.createElement('option');
            option.value = program.progid;
            option.textContent = program.progfullname;
            programSelect.appendChild(option);
        });
    } else {
        const noProgramsOption = document.createElement('option');
        noProgramsOption.value = '';
        noProgramsOption.textContent = 'No programs available';
        programSelect.appendChild(noProgramsOption);
    }
}

document.addEventListener('DOMContentLoaded', function () {
    loadColleges();
    loadPrograms();

    const collegeSelect = document.getElementById('college');
    collegeSelect.addEventListener('change', onCollegeChange);
});
