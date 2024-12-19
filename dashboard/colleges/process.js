document.addEventListener("DOMContentLoaded", function() {
    const objData = JSON.parse(sessionStorage.getItem('objData'));
    console.log(objData);

    if (objData) {
        document.getElementById('college_id').value = objData.collid;
        document.getElementById('full_name').value = objData.collfullname;
        document.getElementById('short_name').value = objData.collshortname;
    } else {
        window.location.href = '../dashboard-table.php';
    }
});
