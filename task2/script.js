const API = "http://localhost:3000";

async function fetchStudents(query = "") {
    const res = await fetch(`${API}/students${query}`);
    const data = await res.json();
    renderTable(data.data);
}

function renderTable(data) {
    const tableBody = document.getElementById("tableBody");
    tableBody.innerHTML = "";

    data.forEach(student => {
        tableBody.innerHTML += `
            <tr>
                <td>${student.name}</td>
                <td>${student.email}</td>
                <td>${student.dept}</td>
                <td>${student.DOB.split("T")[0]}</td>
            </tr>
        `;
    });
}

function sortName() {
    fetchStudents("?sortBy=name");
}

function sortDOB() {
    fetchStudents("?sortBy=dob");
}

document.getElementById("deptFilter").addEventListener("change", function () {
    const dept = this.value;
    if (dept) {
        fetchStudents(`?dept=${dept}`);
    } else {
        fetchStudents();
    }
});

async function fetchCount() {
    const res = await fetch(`${API}/students/count`);
    const data = await res.json();

    let text = "Department Count: ";
    data.data.forEach(d => {
        text += `${d.dept} = ${d.total}  `;
    });

    document.getElementById("countSection").innerText = text;
}

fetchStudents();
fetchCount();
