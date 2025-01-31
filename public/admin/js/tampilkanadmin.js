fetch("http://localhost:8000/api/admin/attendance", {
    method: "GET",
    headers: { Authorization: "Bearer " + localStorage.getItem("token") },
})
    .then((response) => response.json())
    .then((data) => {
        let table = document.getElementById("adminAttendanceTable");
        table.innerHTML = data
            .map(
                (row) =>
                    `<tr>
            <td>${row.user.name}</td>
            <td>${new Date(row.created_at).toLocaleString()}</td>
            <td><img src="http://localhost:8000/storage/${
                row.photo
            }" width="50"></td>
        </tr>`
            )
            .join("");
    });
