document.getElementById("registerform").addEventListener("submit", function(e) {

    e.preventDefault();

    const name = document.getElementById("name").value;
    const email = document.getElementById("email").value;
    const dob = document.getElementById("dob").value;
    const department = document.getElementById("department").value;
    const phone = document.getElementById("phone").value;

    fetch("http://localhost:5000/addStudent", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            name: name,
            email: email,
            dob: dob,
            department: department,
            phone: phone
        })
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
    })
    .catch(error => {
        console.log(error);
    });

});
