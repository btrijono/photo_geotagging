// Inisialisasi peta
const map = L.map('map').setView([-6.200000, 106.816666], 2); // Koordinat awal (Jakarta)

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '© OpenStreetMap'
}).addTo(map);

// Fungsi untuk mengunggah dan membaca gambar
document.getElementById('imageUpload').addEventListener('change', function (event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            const img = new Image();
            img.src = e.target.result;
            img.onload = function () {
                EXIF.getData(img, function () {
                    const lat = EXIF.getTag(this, "GPSLatitude");
                    const latRef = EXIF.getTag(this, "GPSLatitudeRef");
                    const lon = EXIF.getTag(this, "GPSLongitude");
                    const lonRef = EXIF.getTag(this, "GPSLongitudeRef");

                    if (lat && lon) {
                        // Konversi lat/lon dari derajat desimal ke format desimal
                        const latitude = latRef === "N" ? lat : -lat;
                        const longitude = lonRef === "E" ? lon : -lon;

                        // Tambahkan marker ke peta
                        const marker = L.marker([latitude, longitude]).addTo(map);
                        marker.bindPopup(file.name).openPopup();
                        map.setView([latitude, longitude], 10); // Pindah ke titik tersebut
                    } else {
                        alert('Gambar tidak memiliki informasi geotagging.');
                    }
                });
            };
        };
        reader.readAsDataURL(file);
    }
});
