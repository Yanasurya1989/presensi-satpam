navigator.geolocation.getCurrentPosition((position) => {
    let latitude = position.coords.latitude;
    let longitude = position.coords.longitude;
    let photo = document.getElementById("photoInput").files[0];

    let formData = new FormData();
    formData.append("latitude", latitude);
    formData.append("longitude", longitude);
    formData.append("photo", photo);

    fetch("http://localhost:8000/api/attendance", {
        method: "POST",
        headers: {
            Authorization: "Bearer " + localStorage.getItem("token"),
        },
        body: formData,
    })
        .then((response) => response.json())
        .then((data) => alert(data.message))
        .catch((error) => console.error("Error:", error));
});
