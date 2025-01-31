fetch("http://localhost:8000/api/attendance/report", {
    method: "GET",
    headers: { Authorization: "Bearer " + localStorage.getItem("token") },
})
    .then((response) => response.json())
    .then((data) => {
        let table = document.getElementById("attendanceTable");
        table.innerHTML = data
            .map(
                (row) =>
                    `<tr>
            <td>${new Date(row.created_at).toLocaleString()}</td>
            <td><img src="http://localhost:8000/storage/${
                row.photo
            }" width="50"></td>
        </tr>`
            )
            .join("");
    });
